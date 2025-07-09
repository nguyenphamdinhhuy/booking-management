<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class rooms_controller extends Controller
{
    public function add_room_form()
    {
        return view('admin.rooms.add_room');
    }

    public function add_room_handle(Request $request)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'nullable|integer|min:1|max:10',
            'number_beds' => 'nullable|integer|min:1|max:10',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'name.required' => 'Tên phòng là bắt buộc',
            'name.max' => 'Tên phòng không được quá 50 ký tự',
            'price_per_night.required' => 'Giá phòng là bắt buộc',
            'price_per_night.numeric' => 'Giá phòng phải là số',
            'price_per_night.min' => 'Giá phòng phải lớn hơn 0',
            'max_guests.integer' => 'Số khách tối đa phải là số nguyên',
            'number_beds.integer' => 'Số giường phải là số nguyên',
            'status.required' => 'Trạng thái là bắt buộc',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được quá 5MB'
        ]);

        try {
            DB::beginTransaction();

            // Xử lý upload hình ảnh
            $imagePath = null;
            if ($request->hasFile('image')) {
                $destinationPath = public_path('uploads/rooms');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $image = $request->file('image');
                $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $fileName);
                $imagePath = 'uploads/rooms/' . $fileName;
            }

            // Lưu vào database
            DB::table('rooms')->insert([
                'name' => $validatedData['name'],
                'price_per_night' => $validatedData['price_per_night'],
                'max_guests' => $validatedData['max_guests'] ?? null,
                'number_beds' => $validatedData['number_beds'] ?? null,
                'images' => $imagePath,
                'status' => $validatedData['status'],
                'description' => $validatedData['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.rooms.create')->with('success', 'Thêm phòng thành công!');
        } catch (\Exception $e) {
            DB::rollback();

            // Xóa ảnh đã upload nếu có lỗi
            if ($imagePath) {
                $absolutePath = public_path($imagePath);
                if (file_exists($absolutePath)) {
                    unlink($absolutePath);
                }
            }

            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function rooms_management(Request $request)
    {
        try {
            // Khởi tạo query builder
            $query = DB::table('rooms')->select('*');

            // Filter theo trạng thái
            if ($request->filled('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Filter theo số khách tối đa
            if ($request->filled('max_guests') && $request->max_guests !== '') {
                if ($request->max_guests == '4') {
                    $query->where('max_guests', '>=', 4);
                } else {
                    $query->where('max_guests', $request->max_guests);
                }
            }

            // Filter theo giá phòng
            if ($request->filled('price_range') && $request->price_range !== '') {
                switch ($request->price_range) {
                    case '0-500000':
                        $query->where('price_per_night', '<', 500000);
                        break;
                    case '500000-1000000':
                        $query->whereBetween('price_per_night', [500000, 1000000]);
                        break;
                    case '1000000-2000000':
                        $query->whereBetween('price_per_night', [1000000, 2000000]);
                        break;
                    case '2000000+':
                        $query->where('price_per_night', '>', 2000000);
                        break;
                }
            }

            // Tìm kiếm theo từ khóa
            if ($request->filled('search') && $request->search !== '') {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', $searchTerm)
                        ->orWhere('description', 'LIKE', $searchTerm);
                });
            }

            // Sắp xếp và lấy kết quả
            $rooms = $query->orderBy('created_at', 'desc')->get();

            // Xử lý dữ liệu format
            foreach ($rooms as $room) {
                // Format giá tiền
                $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';

                // Format ngày tạo
                if ($room->created_at) {
                    $room->formatted_created_at = date('d/m/Y', strtotime($room->created_at));
                }

                // Tạo rating giả (có thể thay bằng dữ liệu thật từ bảng reviews)
                $room->rating = round(rand(80, 95) / 10, 1);
                $room->review_count = rand(100, 2000);

                // Tạo discount giả
                $room->discount_percent = rand(10, 30);
                $room->old_price = $room->price_per_night * (1 + $room->discount_percent / 100);
                $room->formatted_old_price = number_format($room->old_price, 0, ',', '.') . ' VND';
            }

            // Nếu là AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rooms' => $rooms,
                    'count' => $rooms->count()
                ]);
            }

            return view('admin.rooms.rooms_management', compact('rooms'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi khi tìm kiếm: ' . $e->getMessage()
                ]);
            }

            return view('admin.rooms.rooms_management')
                ->with('error', 'Có lỗi khi load danh sách phòng: ' . $e->getMessage())
                ->with('rooms', collect([]));
        }
    }

    public function view_room($id)
    {
        try {
            $room = DB::table('rooms')->where('r_id', $id)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Phòng không tồn tại!');
            }

            // Format dữ liệu để hiển thị
            $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';

            if ($room->created_at) {
                $room->formatted_created_at = date('d/m/Y H:i', strtotime($room->created_at));
            }

            return view('admin.rooms.view_room', compact('room'));
        } catch (\Exception $e) {
            return redirect()->route('admin.rooms.management')
                ->with('error', 'Có lỗi khi load thông tin phòng: ' . $e->getMessage());
        }
    }

    public function delete_room($id)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra phòng có tồn tại không
            $room = DB::table('rooms')->where('r_id', $id)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Phòng không tồn tại!');
            }

            // Xóa ảnh khỏi thư mục nếu có
            if ($room->images) {
                $imagePath = public_path($room->images);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Xóa phòng khỏi database
            $deleted = DB::table('rooms')->where('r_id', $id)->delete();

            if ($deleted) {
                DB::commit();
                return redirect()->route('admin.rooms.management')
                    ->with('success', 'Xóa phòng thành công!');
            } else {
                DB::rollback();
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Không thể xóa phòng!');
            }
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.rooms.management')
                ->with('error', 'Có lỗi xảy ra khi xóa phòng: ' . $e->getMessage());
        }
    }

    public function edit_room_form($id)
    {
        try {
            $room = DB::table('rooms')->where('r_id', $id)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Phòng không tồn tại!');
            }

            return view('admin.rooms.edit_room', compact('room'));
        } catch (\Exception $e) {
            return redirect()->route('admin.rooms.management')
                ->with('error', 'Có lỗi khi load thông tin phòng: ' . $e->getMessage());
        }
    }

    public function edit_room_handle(Request $request, $id)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'nullable|integer|min:1|max:10',
            'number_beds' => 'nullable|integer|min:1|max:10',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'name.required' => 'Tên phòng là bắt buộc',
            'name.max' => 'Tên phòng không được quá 50 ký tự',
            'price_per_night.required' => 'Giá phòng là bắt buộc',
            'price_per_night.numeric' => 'Giá phòng phải là số',
            'price_per_night.min' => 'Giá phòng phải lớn hơn 0',
            'max_guests.integer' => 'Số khách tối đa phải là số nguyên',
            'number_beds.integer' => 'Số giường phải là số nguyên',
            'status.required' => 'Trạng thái là bắt buộc',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được quá 5MB'
        ]);

        try {
            DB::beginTransaction();

            // Kiểm tra phòng có tồn tại không
            $room = DB::table('rooms')->where('r_id', $id)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Phòng không tồn tại!');
            }

            // Xử lý upload ảnh mới (nếu có)
            $imagePath = $room->images; // Giữ ảnh cũ
            $oldImagePath = null;

            if ($request->hasFile('image')) {
                // Lưu đường dẫn ảnh cũ để xóa sau
                $oldImagePath = $room->images;

                // Upload ảnh mới
                $destinationPath = public_path('uploads/rooms');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $image = $request->file('image');
                $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $fileName);
                $imagePath = 'uploads/rooms/' . $fileName;
            }

            // Cập nhật thông tin phòng
            $updated = DB::table('rooms')->where('r_id', $id)->update([
                'name' => $validatedData['name'],
                'price_per_night' => $validatedData['price_per_night'],
                'max_guests' => $validatedData['max_guests'] ?? null,
                'number_beds' => $validatedData['number_beds'] ?? null,
                'images' => $imagePath,
                'status' => $validatedData['status'],
                'description' => $validatedData['description'] ?? null,
                'updated_at' => now()
            ]);

            if ($updated) {
                // Xóa ảnh cũ nếu có ảnh mới
                if ($oldImagePath && $imagePath !== $oldImagePath) {
                    $absoluteOldPath = public_path($oldImagePath);
                    if (file_exists($absoluteOldPath)) {
                        unlink($absoluteOldPath);
                    }
                }

                DB::commit();
                return redirect()->route('admin.rooms.management')
                    ->with('success', 'Cập nhật phòng thành công!');
            } else {
                DB::rollback();
                return redirect()->back()->withInput()
                    ->with('error', 'Không có thay đổi nào được thực hiện!');
            }
        } catch (\Exception $e) {
            DB::rollback();

            // Xóa ảnh mới upload nếu có lỗi
            if (isset($imagePath) && $imagePath !== $room->images) {
                $absolutePath = public_path($imagePath);
                if (file_exists($absolutePath)) {
                    unlink($absolutePath);
                }
            }

            return redirect()->back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function user_home(Request $request)
    {
        try {
            // Khởi tạo query builder - chỉ lấy phòng có status = 1 (active) và available = true
            $query = DB::table('rooms')
                ->select('*')
                ->where('status', 1)
                ->where('available', true);

            // Filter theo số khách (nếu user chọn)
            if ($request->filled('guests') && $request->guests > 0) {
                $query->where('max_guests', '>=', $request->guests);
            }

            // Filter theo khoảng giá
            if ($request->filled('min_price') && $request->min_price > 0) {
                $query->where('price_per_night', '>=', $request->min_price);
            }

            if ($request->filled('max_price') && $request->max_price > 0) {
                $query->where('price_per_night', '<=', $request->max_price);
            }

            // Filter theo vị trí


            // Tìm kiếm theo từ khóa
            if ($request->filled('search') && $request->search !== '') {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', $searchTerm)
                        ->orWhere('description', 'LIKE', $searchTerm);
                });
            }

            // Sắp xếp theo tiêu chí
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            switch ($sortBy) {
                case 'price_low':
                    $query->orderBy('price_per_night', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price_per_night', 'desc');
                    break;
                case 'popular':
                    // Sắp xếp ngẫu nhiên để mô phỏng độ phổ biến
                    $query->inRandomOrder();
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            // Phân trang hoặc limit
            $perPage = $request->get('per_page', 12);
            $rooms = $query->limit($perPage)->get();

            // Xử lý format dữ liệu cho từng phòng
            foreach ($rooms as $room) {
                // Format giá tiền
                $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';

                // Tạo giá cũ với discount (có thể lấy từ bảng promotions thực tế)
                $room->discount_percent = rand(10, 30);
                $room->old_price = $room->price_per_night * (1 + $room->discount_percent / 100);
                $room->formatted_old_price = number_format($room->old_price, 0, ',', '.') . ' VND';

                // Tạo rating giả (trong thực tế nên lấy từ bảng reviews)
                $room->rating = round(rand(40, 50) / 10, 1); // 4.0 - 5.0
                $room->review_count = rand(50, 500);

                // Format ngày tạo
                if ($room->created_at) {
                    $room->formatted_created_at = date('d/m/Y', strtotime($room->created_at));
                }

                // Xử lý hình ảnh - kiểm tra file có tồn tại không
                if ($room->images) {
                    $imagePath = public_path($room->images);
                    if (!file_exists($imagePath)) {
                        $room->images = null; // Set null nếu file không tồn tại
                    }
                }

                // Tạo URL cho hình ảnh mặc định nếu không có ảnh
                if (!$room->images) {
                    $room->images = 'assets/images/default-room.jpg'; // Đường dẫn ảnh mặc định
                }

                // Thêm thông tin bổ sung
                $room->is_new = (strtotime($room->created_at) > strtotime('-7 days'));
                $room->facilities = ['WiFi', 'Điều hòa', 'TV', 'Tủ lạnh']; // Có thể lấy từ bảng facilities
            }

            // Lấy thống kê tổng quan
            $totalRooms = DB::table('rooms')->where('status', 1)->where('available', true)->count();
            $avgPrice = DB::table('rooms')->where('status', 1)->where('available', true)->avg('price_per_night');

            $stats = [
                'total_rooms' => $totalRooms,
                'avg_price' => number_format($avgPrice, 0, ',', '.') . ' VND',
                'min_price' => number_format(DB::table('rooms')->where('status', 1)->where('available', true)->min('price_per_night'), 0, ',', '.') . ' VND',
                'max_price' => number_format(DB::table('rooms')->where('status', 1)->where('available', true)->max('price_per_night'), 0, ',', '.') . ' VND'
            ];

            // Nếu là AJAX request (cho filter động)
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rooms' => $rooms,
                    'stats' => $stats,
                    'count' => $rooms->count()
                ]);
            }

            // Trả về view
            return view('user.home', compact('rooms', 'stats'));
        } catch (\Exception $e) {
            // Log lỗi
            \Log::error('Error loading rooms for user home: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi khi tải danh sách phòng'
                ]);
            }

            return view('user.home')
                ->with('error', 'Có lỗi khi tải danh sách phòng')
                ->with('rooms', collect([]))
                ->with('stats', [
                    'total_rooms' => 0,
                    'avg_price' => '0 VND',
                    'min_price' => '0 VND',
                    'max_price' => '0 VND'
                ]);
        }
    }

    // Method bổ sung để lấy phòng nổi bật
    public function get_featured_rooms($limit = 6)
    {
        try {
            $featuredRooms = DB::table('rooms')
                ->select('*')
                ->where('status', 1)
                ->where('available', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            // Format dữ liệu tương tự như method user_home
            foreach ($featuredRooms as $room) {
                $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';
                $room->rating = round(rand(40, 50) / 10, 1);
                $room->review_count = rand(50, 500);

                if (!$room->images || !file_exists(public_path($room->images))) {
                    $room->images = 'assets/images/default-room.jpg';
                }
            }

            return $featuredRooms;
        } catch (\Exception $e) {
            \Log::error('Error loading featured rooms: ' . $e->getMessage());
            return collect([]);
        }
    }

    // Method để lấy chi tiết một phòng
    public function room_detail($id)
    {
        try {
            $room = DB::table('rooms')
                ->where('r_id', $id)
                ->where('status', 1)
                ->where('available', true)
                ->first();

            if (!$room) {
                return redirect()->route('user.home')
                    ->with('error', 'Phòng không tồn tại hoặc không khả dụng!');
            }

            // Format dữ liệu chi tiết
            $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';
            $room->rating = round(rand(40, 50) / 10, 1);
            $room->review_count = rand(50, 500);

            if ($room->created_at) {
                $room->formatted_created_at = date('d/m/Y H:i', strtotime($room->created_at));
            }

            if (!$room->images || !file_exists(public_path($room->images))) {
                $room->images = 'assets/images/default-room.jpg';
            }

            // Lấy phòng liên quan (cùng khoảng giá)
            $relatedRooms = DB::table('rooms')
                ->where('r_id', '!=', $id)
                ->where('status', 1)
                ->where('available', true)
                ->whereBetween('price_per_night', [
                    $room->price_per_night * 0.8,
                    $room->price_per_night * 1.2
                ])
                ->limit(4)
                ->get();

            foreach ($relatedRooms as $relatedRoom) {
                $relatedRoom->formatted_price = number_format($relatedRoom->price_per_night, 0, ',', '.') . ' VND';
                $relatedRoom->rating = round(rand(40, 50) / 10, 1);

                if (!$relatedRoom->images || !file_exists(public_path($relatedRoom->images))) {
                    $relatedRoom->images = 'assets/images/default-room.jpg';
                }
            }

            return view('user.room_detail', compact('room', 'relatedRooms'));
        } catch (\Exception $e) {
            \Log::error('Error loading room detail: ' . $e->getMessage());
            return redirect()->route('user.home')
                ->with('error', 'Có lỗi khi tải thông tin phòng');
        }
    }

    public function payment(Request $request)
    {
        $user = auth()->user();
        $room = null;
        $roomId = $request->input('r_id');
        if ($roomId) {
            $room = DB::table('rooms')->where('r_id', $roomId)->first();
        }
        $checkin = $request->input('checkin');
        $checkout = $request->input('checkout');
        $guests = $request->input('guests');
        $discount_code = $request->input('discount_code');
        $voucher = null;
        $discount_percent = 0;
        $discount_amount = 0;
        $total_price = 0;

        // Tính số đêm
        if ($room && $checkin && $checkout) {
            $nights = (strtotime($checkout) - strtotime($checkin)) / 86400;
            if ($nights < 1) $nights = 1;
            $total_price = $nights * $room->price_per_night;

            // Kiểm tra mã giảm giá
            if ($discount_code) {
                $voucher = DB::table('vouchers')
                    ->where('v_code', $discount_code)
                    ->where('status', 1)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();
                if ($voucher) {
                    $discount_percent = $voucher->discount_percent;
                    $discount_amount = round($total_price * $discount_percent / 100);
                }
            }
        }

        return view('user.payment', [
            'user' => $user,
            'room' => $room,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'guests' => $guests,
            'total_price' => $total_price,
            'discount_code' => $discount_code,
            'discount_percent' => $discount_percent,
            'discount_amount' => $discount_amount,
            'voucher' => $voucher,
        ]);
    }

    public function processPayment(Request $request)
    {
        try {
            // Validate dữ liệu
            $request->validate([
                'r_id' => 'required|exists:rooms,r_id',
                'checkin' => 'required|date',
                'checkout' => 'required|date|after:checkin',
                'guests' => 'required|integer|min:1',
                'total_price' => 'required|numeric|min:0',
                'payment_method' => 'required|string'
            ]);

            $user = Auth::user();
            $room = DB::table('rooms')->where('r_id', $request->r_id)->first();

            if (!$room) {
                return redirect()->back()->with('error', 'Phòng không tồn tại!');
            }

            // Tính toán giá tiền
            $nights = (strtotime($request->checkout) - strtotime($request->checkin)) / 86400;
            if ($nights < 1) $nights = 1;

            $totalPrice = $nights * $room->price_per_night;
            $discountAmount = $request->discount_amount ?? 0;
            $finalAmount = $totalPrice - $discountAmount;

            // Xử lý theo phương thức thanh toán
            switch ($request->payment_method) {
                case 'vnpay':
                    return $this->processVNPayPayment($request, $user, $room, $finalAmount);
                    break;
                case 'momo':
                    return $this->processMoMoPayment($request, $user, $room, $finalAmount);
                    break;
                case 'bank':
                    return $this->processBankTransfer($request, $user, $room, $finalAmount);
                    break;
                default:
                    return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ!');
            }
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý thanh toán!');
        }
    }

    private function processVNPayPayment($request, $user, $room, $amount)
    {
        // Thông tin VNPay
        $vnp_TmnCode = "1TFT191D";
        $vnp_HashSecret = "1OVMPGLZGJNAJW4PYC2SRK4MGSI1PWJQ";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_ReturnUrl = route('vnpay.return');

        // Tạo mã đơn hàng duy nhất
        $vnp_TxnRef = 'BOOKING_' . time() . '_' . $user->id;

        // Lưu thông tin booking tạm thời vào session
        session([
            'booking_data' => [
                'u_id' => $user->id,
                'r_id' => $request->r_id,
                'check_in_date' => $request->checkin,
                'check_out_date' => $request->checkout,
                'guests' => $request->guests,
                'total_price' => $amount,
                'discount_code' => $request->discount_code,
                'discount_amount' => $request->discount_amount,
                'payment_method' => 'vnpay',
                'vnp_TxnRef' => $vnp_TxnRef
            ]
        ]);

        // Tạo các tham số cho VNPay
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $amount * 100, // VNPay yêu cầu amount * 100
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $request->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan dat phong " . $room->name,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // Sắp xếp tham số
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        // Tạo secure hash
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Redirect đến VNPay
        return redirect($vnp_Url);
    }

    public function bookingSuccess($id)
    {
        try {
            // Lấy thông tin booking
            $booking = DB::table('booking')->where('b_id', $id)->first();

            if (!$booking) {
                return redirect()->route('index')->with('error', 'Không tìm thấy thông tin đặt phòng!');
            }

            // Kiểm tra quyền truy cập - chỉ user đã đặt mới được xem
            if ($booking->u_id != Auth::id()) {
                return redirect()->route('index')->with('error', 'Bạn không có quyền xem thông tin này!');
            }

            // Lấy thông tin user
            $user = DB::table('users')->where('id', $booking->u_id)->first();

            // Lấy thông tin phòng từ booking_details hoặc từ session
            $room = null;
            $details = null;
            $voucher = null;

            // Kiểm tra nếu có bảng booking_details
            $bookingDetails = DB::table('booking_details')->where('b_id', $id)->first();

            if ($bookingDetails) {
                $room = DB::table('rooms')->where('r_id', $bookingDetails->r_id)->first();
                $details = $bookingDetails;

                // Lấy thông tin voucher nếu có

            } else {
                // Fallback: lấy từ session nếu vẫn còn
                $bookingData = session('booking_data');
                if ($bookingData) {
                    $room = DB::table('rooms')->where('r_id', $bookingData['r_id'])->first();
                    $details = (object) $bookingData;

                    if ($bookingData['discount_code']) {
                        $voucher = DB::table('vouchers')->where('v_code', $bookingData['discount_code'])->first();
                    }
                }
            }

            // Format dữ liệu
            if ($room) {
                $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';
            }

            // Tính số đêm
            $nights = 1;
            if ($booking->check_in_date && $booking->check_out_date) {
                $nights = (strtotime($booking->check_out_date) - strtotime($booking->check_in_date)) / 86400;
                if ($nights < 1) $nights = 1;
            }

            // Format ngày tháng
            $booking->formatted_check_in = date('d/m/Y', strtotime($booking->check_in_date));
            $booking->formatted_check_out = date('d/m/Y', strtotime($booking->check_out_date));
            $booking->formatted_total_price = number_format($booking->total_price, 0, ',', '.') . ' VND';
            $booking->nights = $nights;

            return view('user.booking-success', compact('booking', 'user', 'room', 'details', 'voucher'));
        } catch (\Exception $e) {
            Log::error('Booking success page error: ' . $e->getMessage());
            return redirect()->route('index')->with('error', 'Có lỗi xảy ra khi tải thông tin đặt phòng!');
        }
    }

    // Cập nhật lại phần xử lý VNPay return
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "1OVMPGLZGJNAJW4PYC2SRK4MGSI1PWJQ";

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        try {
            if ($secureHash == $vnp_SecureHash) {
                $bookingData = session('booking_data');

                if (!$bookingData) {
                    return redirect()->route('index')->with('error', 'Không tìm thấy thông tin đặt phòng!');
                }

                if ($request->vnp_ResponseCode == '00') {
                    // Thanh toán thành công
                    DB::beginTransaction();

                    // Tạo mã đơn b_id random 8 ký tự
                    $bookingId = strtoupper(Str::random(8));

                    // Thêm booking
                    DB::table('booking')->insert([
                        'b_id' => $bookingId,
                        'u_id' => $bookingData['u_id'],
                        'check_in_date' => $bookingData['check_in_date'],
                        'check_out_date' => $bookingData['check_out_date'],
                        'status' => 1,
                        'payment_status' => 1,
                        'total_price' => $bookingData['total_price'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);



                    // Thêm vào booking_details
                    DB::table('booking_details')->insert([
                        'b_id' => $bookingId,
                        'r_id' => $bookingData['r_id'],
                        'description' => DB::table('rooms')->where('r_id', $bookingData['r_id'])->value('description'),
                    ]);

                    // Thêm vào payment




                    // Cập nhật trạng thái phòng (nếu cần)
                    DB::table('rooms')
                        ->where('r_id', $bookingData['r_id'])
                        ->update(['available' => false]);

                    DB::commit();

                    // Lưu lại thông tin booking vào session để hiển thị ở trang success
                    session(['last_booking' => [
                        'b_id' => $bookingId,
                        'r_id' => $bookingData['r_id'],
                        'guests' => $bookingData['guests'],
                        'discount_code' => $bookingData['discount_code'],
                        'discount_amount' => $bookingData['discount_amount'],
                        'payment_method' => $bookingData['payment_method'],
                        'vnp_transaction_id' => $request->vnp_TransactionNo ?? null
                    ]]);

                    // Xóa session booking_data
                    session()->forget('booking_data');

                    return redirect()->route('booking.success', ['id' => $bookingId])
                        ->with('success', 'Thanh toán thành công! Đặt phòng của bạn đã được xác nhận.');
                } else {
                    // Thanh toán thất bại
                    session()->forget('booking_data');
                    return redirect()->route('payment')
                        ->with('error', 'Thanh toán không thành công. Vui lòng thử lại!');
                }
            } else {
                return redirect()->route('index')->with('error', 'Chữ ký không hợp lệ!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay return error: ' . $e->getMessage());
            return redirect()->route('index')->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán!');
        }
    }

    private function processMoMoPayment($request, $user, $room, $amount)
    {
        // Implement MoMo payment logic here
        return redirect()->back()->with('error', 'Tính năng thanh toán MoMo đang được phát triển!');
    }

    private function processBankTransfer($request, $user, $room, $amount)
    {
        // Implement bank transfer logic here
        return redirect()->back()->with('error', 'Tính năng chuyển khoản ngân hàng đang được phát triển!');
    }

    public function getBookingHistory($userId)
    {
        try {
            $bookings = DB::table('booking as b')
                ->join('booking_details as bd', 'b.b_id', '=', 'bd.b_id')
                ->join('rooms as r', 'bd.r_id', '=', 'r.r_id')
                ->select(
                    'b.b_id as id',
                    'b.check_in_date as checkin_date',
                    'b.check_out_date as checkout_date',
                    'b.status',
                    'b.total_price as price',
                    'b.created_at',
                    'b.payment_status',
                    'r.name as room_type',
                    'r.price_per_night',
                    'r.max_guests as guests',
                    'r.number_beds',
                    'r.images as images',
                    'bd.description as booking_description',
                    // Thêm các trường giả định cho hotel (có thể lấy từ bảng hotels nếu có)
                    DB::raw("'Hotel Name' as hotel_name"),
                    DB::raw("'Hotel Address' as hotel_address"),
                    DB::raw("'4.5' as rating"),
                    DB::raw("0 as refund_price")
                )
                ->where('b.u_id', $userId)
                ->orderBy('b.created_at', 'desc')
                ->get();

            // Transform data để phù hợp với view
            $transformedBookings = collect();

            foreach ($bookings as $booking) {
                // Chuyển đổi status từ tinyint sang string
                $statusMap = [
                    0 => 'cancelled',
                    1 => 'completed',
                    2 => 'confirmed'
                ];

                $booking->status = $statusMap[$booking->status] ?? 'confirmed';

                // Xử lý image_url từ trường images
                if ($booking->images) {
                    $images = explode(',', $booking->images);
                    $booking->image_url = asset('storage/' . trim($images[0]));
                }

                // Thêm canceled_at nếu status là cancelled
                if ($booking->status === 'cancelled') {
                    $booking->canceled_at = $booking->created_at;
                }

                $transformedBookings->push($booking);
            }

            return view('user.booking_bill', ['bookings' => $transformedBookings]);
        } catch (Exception $e) {
            return back()->with('error', 'Không thể tải lịch sử đặt phòng: ' . $e->getMessage());
        }
    }

    // Lấy thống kê booking cho user
    public function getBookingStats($userId)
    {
        $stats = DB::table('booking')
            ->select(
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completed_bookings'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as cancelled_bookings'),
                DB::raw('SUM(total_price) as total_spent')
            )
            ->where('u_id', $userId)
            ->first();

        return $stats;
    }
}
