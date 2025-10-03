<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class roomTypeUserContoller extends Controller
{
    /**
     * Hiển thị chi tiết loại phòng
     */
    public function show($id)
    {
        try {
            // Lấy thông tin chi tiết loại phòng từ bảng room_types
            $roomType = DB::table('room_types')
                ->where('rt_id', $id)
                ->where('status', 1) // Chỉ lấy loại phòng đang hoạt động
                ->first();

            if (!$roomType) {
                abort(404, 'Loại phòng không tồn tại hoặc đã bị tạm dừng');
            }

            // Xử lý dữ liệu JSON cho amenities và images
            $roomType->amenities = json_decode($roomType->amenities, true) ?? [];
            $roomType->images = json_decode($roomType->images, true) ?? [];

            // Đếm số phòng thuộc loại này (giả sử có bảng rooms với cột rt_id)
            $availableRooms = 5; // Mặc định là 5 phòng, có thể thay đổi theo logic thực tế

            // Nếu có bảng rooms liên kết với room_types
            if (DB::getSchemaBuilder()->hasTable('rooms')) {
                $availableRooms = DB::table('rooms')
                    ->where('rt_id', $id) // Sử dụng rt_id thay vì room_type_id
                    ->where('status', 1)
                    ->count();

                // Nếu không có phòng nào, set mặc định
                if ($availableRooms == 0) {
                    $availableRooms = 3;
                }
            }

            // Lấy các đánh giá (giả định có bảng reviews)
            $reviews = collect([]);
            $averageRating = 0;
            $totalReviews = 0;

            if (DB::getSchemaBuilder()->hasTable('reviews') && DB::getSchemaBuilder()->hasTable('bookings')) {
                $reviews = DB::table('reviews')
                    ->join('bookings', 'reviews.booking_id', '=', 'bookings.b_id')
                    ->join('users', 'bookings.user_id', '=', 'users.id')
                    ->where('bookings.rt_id', $id) // Giả sử bookings có rt_id
                    ->select(
                        'reviews.*',
                        'users.name as customer_name',
                        'bookings.check_in_date',
                        'bookings.check_out_date'
                    )
                    ->orderBy('reviews.created_at', 'desc')
                    ->limit(10)
                    ->get();

                $totalReviews = $reviews->count();
                if ($totalReviews > 0) {
                    $totalRating = $reviews->sum('rating');
                    $averageRating = round($totalRating / $totalReviews, 1);
                }
            }

            // Lấy các loại phòng khác để gợi ý
            $suggestedRoomTypes = DB::table('room_types')
                ->where('rt_id', '!=', $id)
                ->where('status', 1)
                ->limit(3)
                ->get();

            // Xử lý JSON cho suggested room types
            foreach ($suggestedRoomTypes as $suggested) {
                $suggested->amenities = json_decode($suggested->amenities, true) ?? [];
                $suggested->images = json_decode($suggested->images, true) ?? [];
            }

            $services = DB::table('services')
                ->where('is_available', 1)
                ->select('s_id', 'name', 'price', 'category_id')
                ->orderBy('category_id')
                ->orderBy('name')
                ->get();

            // Truyền sang view
            return view('user.room_type_detail', compact(
                'roomType',
                'availableRooms',
                'reviews',
                'averageRating',
                'totalReviews',
                'suggestedRoomTypes',
                'services'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải thông tin loại phòng: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị danh sách loại phòng
     */
    public function index(Request $request)
    {
        $query = DB::table('room_types')->where('status', 1);

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Lọc theo số khách
        if ($request->filled('max_guests')) {
            if ($request->max_guests == '4') {
                $query->where('max_guests', '>=', 4);
            } else {
                $query->where('max_guests', '>=', $request->max_guests);
            }
        }

        $roomTypes = $query->orderBy('base_price', 'asc')->get();

        // Xử lý dữ liệu JSON cho mỗi loại phòng
        foreach ($roomTypes as $roomType) {
            $roomType->amenities = json_decode($roomType->amenities, true) ?? [];
            $roomType->images = json_decode($roomType->images, true) ?? [];
        }

        return view('room-types.index', compact('roomTypes'));
    }

    /**
     * Kiểm tra tính khả dụng trong khoảng thời gian
     */
    /**
     * Validate thông tin đặt phòng trước khi chuyển đến trang thanh toán
     */
    public function validateBooking(Request $request)
    {
        try {
            $request->validate([
                'room_type_id' => 'required|integer',
                'check_in'     => 'required|date|after_or_equal:today',
                'check_out'    => 'required|date|after:check_in',
                'guests'       => 'required|integer|min:1',
                // services[...] để payment() kiểm tra sâu hơn; ở đây chỉ cần sanitize nhẹ
            ]);

            $rt_id       = (int) $request->room_type_id;
            $checkIn     = \Carbon\Carbon::parse($request->check_in);
            $checkOut    = \Carbon\Carbon::parse($request->check_out);
            $guests      = (int) $request->guests;
            $discountCode = $request->discount_code;

            // 1) Kiểm tra loại phòng
            $roomType = DB::table('room_types')
                ->where('rt_id', $rt_id)
                ->where('status', 1)
                ->first();
            if (!$roomType) {
                return back()->with('error', 'Loại phòng không tồn tại hoặc đã ngừng hoạt động!');
            }

            // 2) Giới hạn khách
            if ($guests > (int)$roomType->max_guests) {
                return back()->with('error', 'Số khách vượt quá giới hạn của loại phòng này! Tối đa: ' . $roomType->max_guests . ' khách');
            }

            // 3) Phòng trống
            $availabilityCheck = $this->checkAvailabilityInternal($rt_id, $checkIn, $checkOut);
            if (!($availabilityCheck['available'] ?? false)) {
                return back()->with('error', 'Rất tiếc, không còn phòng trống trong thời gian bạn chọn. Vui lòng đổi ngày hoặc loại phòng!');
            }

            // 4) Lấy & làm sạch dịch vụ từ form
            $rawServices = $request->input('services', []); // dạng services[SID][field]
            $services = [];
            foreach ($rawServices as $sid => $svc) {
                $sid          = (int)($svc['s_id'] ?? $sid);
                $qty          = (int)($svc['quantity'] ?? 0);
                $unitPrice    = (int)($svc['unit_price'] ?? 0);
                $pricingModel = (int)($svc['pricing_model'] ?? 0);

                if ($sid > 0 && $qty > 0 && $unitPrice >= 0) {
                    $services[$sid] = [
                        's_id'          => $sid,
                        'quantity'      => $qty,
                        'unit_price'    => $unitPrice,
                        'pricing_model' => $pricingModel, // 0=once (mặc định)
                    ];
                }
            }

            // 5) Tổng dịch vụ (nếu chưa có từ client, tự tính)
            $services_total = (int)($request->input('services_total', 0));
            if ($services_total <= 0 && !empty($services)) {
                $services_total = 0;
                foreach ($services as $svc) {
                    // hiện tại pricing_model = once
                    $services_total += $svc['quantity'] * $svc['unit_price'];
                }
            }

            // 6) Dữ liệu chuyển sang payment (GIỮ ĐÚNG KEY MÀ HÀM payment() ĐANG ĐỌC)
            $paymentData = [
                'rt_id'         => $rt_id,
                'checkin'       => $checkIn->format('Y-m-d'),
                'checkout'      => $checkOut->format('Y-m-d'),
                'guests'        => $guests,
                'discount_code' => $discountCode,
                'services_total' => $services_total,
                // mảng services[...] phải truyền đúng cấu trúc lồng
                'services'      => $services,
            ];

            Log::info('Redirect to payment with data', $paymentData);

            // 7) Redirect GET sang /payment kèm đầy đủ query (bao gồm mảng services[...])
            return redirect()->route('payment', $paymentData);
        } catch (\Exception $e) {
            Log::error('Validate booking error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Internal method để kiểm tra tình trạng phòng (dùng chung)
     */
    private function checkAvailabilityInternal($rt_id, $checkIn, $checkOut)
    {
        // Lấy tổng số phòng của loại phòng này
        $totalRooms = DB::table('rooms')
            ->where('rt_id', $rt_id)
            ->where('status', 1)
            ->where('available', 1)
            ->count();

        if ($totalRooms == 0) {
            return [
                'available' => false,
                'available_rooms' => 0,
                'total_rooms' => 0
            ];
        }

        // Tìm các phòng đã được đặt hoặc assigned trong khoảng thời gian này
        $bookedRooms = DB::table('booking_details as bd')
            ->join('booking as b', 'bd.b_id', '=', 'b.b_id')
            ->where(function ($query) use ($rt_id) {
                // Kiểm tra cả rt_id (booking loại phòng) và r_id_assigned (phòng đã được assign)
                $query->where('bd.rt_id', $rt_id)
                    ->orWhereIn('bd.r_id_assigned', function ($subQuery) use ($rt_id) {
                        $subQuery->select('r_id')
                            ->from('rooms')
                            ->where('rt_id', $rt_id);
                    });
            })
            ->where('b.status', '!=', 4) // không tính booking đã hủy (4 = cancelled)
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    // Kiểm tra overlap: booking nào có thời gian giao nhau với khoảng thời gian cần check
                    $q->where(function ($overlap) use ($checkIn, $checkOut) {
                        $overlap->where('b.check_in_date', '<', $checkOut->format('Y-m-d'))
                            ->where('b.check_out_date', '>', $checkIn->format('Y-m-d'));
                    });
                });
            })
            ->distinct()
            ->count('bd.b_id'); // Count distinct bookings, not rooms

        // Số phòng khả dụng = tổng số phòng - số booking trong thời gian đó
        $availableRooms = $totalRooms - $bookedRooms;

        return [
            'available' => $availableRooms > 0,
            'available_rooms' => max(0, $availableRooms),
            'total_rooms' => $totalRooms,
            'booked_bookings' => $bookedRooms
        ];
    }
}
