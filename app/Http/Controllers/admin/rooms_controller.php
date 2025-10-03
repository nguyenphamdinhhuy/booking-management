<?php

namespace App\Http\Controllers\admin;

use DateTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
class rooms_controller extends Controller
{
    public function search(Request $request)
    {
        try {
            // 1) Validate
            $validated = $request->validate([
                'check_in_date'  => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'guests'         => 'required|integer|min:1|max:10'
            ], [
                'check_in_date.required'  => 'Vui lòng chọn ngày nhận phòng',
                'check_in_date.after_or_equal' => 'Ngày nhận phòng phải từ hôm nay trở đi',
                'check_out_date.required' => 'Vui lòng chọn ngày trả phòng',
                'check_out_date.after'    => 'Ngày trả phòng phải sau ngày nhận phòng',
                'guests.required'         => 'Vui lòng nhập số lượng khách',
                'guests.min'              => 'Số lượng khách tối thiểu là 1',
                'guests.max'              => 'Số lượng khách tối đa là 10',
            ]);

            $checkInDate  = $validated['check_in_date'];
            $checkOutDate = $validated['check_out_date'];
            $guests       = $validated['guests'];

            // 2) Tính số đêm
            $checkIn  = new \DateTime($checkInDate);
            $checkOut = new \DateTime($checkOutDate);
            $nights   = $checkIn->diff($checkOut)->days;

            // 3) Tìm LOẠI PHÒNG khả dụng
            // - room_types.active & not deleted
            // - đủ sức chứa
            // - TỒN TẠI ÍT NHẤT 1 phòng con thuộc loại đó đang available và không bị trùng booking trong khoảng ngày
            $roomTypes = DB::table('room_types as rt')
                ->select([
                    // Alias để reuse view hiện tại
                    'rt.rt_id as r_id',
                    'rt.type_name as name',
                    'rt.description',
                    'rt.base_price as price_per_night',
                    'rt.max_guests',
                    'rt.number_beds',
                    'rt.images',
                    DB::raw('4.5 as rating') // placeholder, tùy bạn thay bằng điểm thực
                ])
                ->where('rt.status', 1)
                ->where('rt.is_delete', 0)
                ->where('rt.max_guests', '>=', $guests)
                ->whereExists(function ($q) use ($checkInDate, $checkOutDate) {
                    // Cần có ít nhất 1 phòng con trong loại này thỏa điều kiện
                    $q->select(DB::raw(1))
                        ->from('rooms as r')
                        ->whereColumn('r.rt_id', 'rt.rt_id')
                        ->where('r.status', 1)
                        ->where('r.is_delete', 0)
                        ->where('r.available', 1)
                        ->whereNotExists(function ($sub) use ($checkInDate, $checkOutDate) {
                            // Phòng không bị trùng booking đã ACTIVE + PAID trong khoảng ngày
                            $sub->select(DB::raw(1))
                                ->from('booking_details as bd')
                                ->join('booking as b', 'bd.b_id', '=', 'b.b_id')
                                ->whereColumn('bd.r_id', 'r.r_id')
                                ->where('b.status', 1)          // đơn đang active
                                ->where('b.payment_status', 1)  // đã thanh toán
                                ->where(function ($dateQ) use ($checkInDate, $checkOutDate) {
                                    $dateQ->where(function ($q1) use ($checkInDate, $checkOutDate) {
                                        $q1->where('b.check_in_date', '>=', $checkInDate)
                                            ->where('b.check_in_date', '<',  $checkOutDate);
                                    })
                                        ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                            $q2->where('b.check_out_date', '>',  $checkInDate)
                                                ->where('b.check_out_date', '<=', $checkOutDate);
                                        })
                                        ->orWhere(function ($q3) use ($checkInDate, $checkOutDate) {
                                            $q3->where('b.check_in_date', '<=', $checkInDate)
                                                ->where('b.check_out_date', '>=', $checkOutDate);
                                        });
                                });
                        });
                })
                ->orderBy('rt.base_price', 'asc')
                ->paginate(12);

            // 4) Thống kê + tính thêm total_price (nếu cần hiển thị)
            $total = $roomTypes->total();
            $stats = [
                'total_rooms'     => $total,
                'available_rooms' => $total,
                'unavailable_rooms' => 0,
                'nights'          => $nights,
                'check_in_date'   => $checkInDate,
                'check_out_date'  => $checkOutDate,
                'guests'          => $guests,
            ];

            foreach ($roomTypes as $rt) {
                $rt->total_price = $rt->price_per_night * $nights; // nếu cần dùng
            }

            // 5) Đổ ra view cũ (tận dụng alias để không phải sửa nhiều)
            // Lưu ý: view hiện link tới route rooms_detail theo r_id (vốn là room_id).
            // Vì ta đang list theo LOẠI PHÒNG, bạn nên đổi route trong view cho đúng (ví dụ roomType.detail).
            return view('user.search_result', [
                'roomTypes' => $roomTypes,
                'stats'     => $stats,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // dd($e); // bật khi cần debug
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tìm kiếm phòng. Vui lòng thử lại.')
                ->withInput();
        }
    }


    public function add_room_form()
    {
        // Lấy danh sách loại phòng để hiển thị trong dropdown
        $roomTypes = DB::table('room_types')
            ->where('status', 1)
            ->orderBy('type_name', 'asc')
            ->get();

        return view('admin.rooms.add_room', compact('roomTypes'));
    }

    public function add_room_handle(Request $request)
    {
        // Chuẩn hoá tên phòng: trim + gộp khoảng trắng
        if ($request->filled('name')) {
            $normalizedName = preg_replace('/\s+/', ' ', trim($request->input('name')));
            $request->merge(['name' => $normalizedName]);
        }

        // Validate dữ liệu + unique theo cặp (rt_id, name), bỏ qua phòng đã xoá (is_delete=1)
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms', 'name')->where(
                    fn($q) =>
                    $q->where('rt_id', $request->input('rt_id'))
                        ->where('is_delete', 0)
                ),
            ],
            'rt_id'            => 'required|integer|exists:room_types,rt_id',
            'price_per_night'  => 'required|numeric|min:0',
            'max_guests'       => 'nullable|integer|min:1|max:10',
            'number_beds'      => 'nullable|integer|min:1|max:10',
            'status'           => 'required|boolean',
            'description'      => 'nullable|string',
            'images'           => 'nullable|array|max:10',
            'images.*'         => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'name.required'            => 'Tên phòng là bắt buộc',
            'name.max'                 => 'Tên phòng không được quá 50 ký tự',
            'name.unique'              => 'Tên phòng đã tồn tại trong loại phòng này.',
            'rt_id.required'           => 'Loại phòng là bắt buộc',
            'rt_id.exists'             => 'Loại phòng không tồn tại',
            'price_per_night.required' => 'Giá phòng là bắt buộc',
            'price_per_night.numeric'  => 'Giá phòng phải là số',
            'price_per_night.min'      => 'Giá phòng phải lớn hơn hoặc bằng 0',
            'max_guests.integer'       => 'Số khách tối đa phải là số nguyên',
            'number_beds.integer'      => 'Số giường phải là số nguyên',
            'status.required'          => 'Trạng thái là bắt buộc',
            'images.array'             => 'Danh sách hình ảnh không hợp lệ',
            'images.max'               => 'Chỉ được upload tối đa 10 hình ảnh',
            'images.*.image'           => 'File phải là hình ảnh',
            'images.*.mimes'           => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'images.*.max'             => 'Kích thước mỗi hình ảnh không được quá 5MB',
        ]);

        $uploadedImages = []; // để dọn dẹp khi lỗi

        try {
            DB::beginTransaction();

            // Lấy room_type để lấy mặc định nếu form không nhập
            $roomType = DB::table('room_types')->where('rt_id', $validated['rt_id'])->first();

            $maxGuests  = $validated['max_guests']  ?? ($roomType->max_guests  ?? null);
            $numberBeds = $validated['number_beds'] ?? ($roomType->number_beds ?? null);

            // Tạo phòng
            $roomId = DB::table('rooms')->insertGetId([
                'name'            => $validated['name'],
                'rt_id'           => (int)$validated['rt_id'],
                'price_per_night' => (float)$validated['price_per_night'],
                'max_guests'      => $maxGuests,
                'number_beds'     => $numberBeds,
                'images'          => null, // cập nhật sau với ảnh đầu tiên
                'status'          => (int)$validated['status'],
                'description'     => $validated['description'] ?? null,
                'available'       => 1,
                'is_delete'       => 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Upload nhiều ảnh
            $firstImagePath = null;
            if ($request->hasFile('images')) {
                $destinationPath = public_path('uploads/rooms');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                foreach ($request->file('images') as $index => $image) {
                    $fileName  = time() . '_' . Str::random(10) . '_' . ($index + 1) . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $fileName);
                    $imagePath = 'uploads/rooms/' . $fileName;

                    DB::table('images')->insert([
                        'r_id'       => $roomId,
                        'image_path' => $imagePath,
                        'created_at' => now(),
                    ]);

                    $uploadedImages[] = $imagePath;

                    if ($index === 0) {
                        $firstImagePath = $imagePath;
                    }
                }

                if ($firstImagePath) {
                    DB::table('rooms')->where('r_id', $roomId)->update([
                        'images'     => $firstImagePath,
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.rooms.create')
                ->with('success', 'Thêm phòng thành công với ' . count($uploadedImages) . ' hình ảnh!');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Dọn file đã upload
            foreach ($uploadedImages as $path) {
                $abs = public_path($path);
                if (is_file($abs)) {
                    @unlink($abs);
                }
            }

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function rooms_management(Request $request)
    {
        try {
            // Khởi tạo query builder với join để lấy thêm thông tin ảnh và loại phòng
            $query = DB::table('rooms as r')
                ->leftJoin('images as i', 'r.r_id', '=', 'i.r_id')
                ->leftJoin('room_types as rt', 'r.rt_id', '=', 'rt.rt_id')
                ->select(
                    'r.*',
                    'rt.type_name',
                    'rt.room_size',
                    'rt.amenities as room_type_amenities',
                    'rt.base_price',
                    DB::raw('GROUP_CONCAT(i.image_path) as all_images'),
                    DB::raw('COUNT(i.id) as image_count')
                )
                ->where('r.is_delete', 0) // Chỉ lấy những phòng chưa bị xóa mềm
                ->groupBy(
                    'r.r_id',
                    'r.name',
                    'r.rt_id',
                    'r.price_per_night',
                    'r.max_guests',
                    'r.number_beds',
                    'r.images',
                    'r.status',
                    'r.description',
                    'r.created_at',
                    'r.updated_at',
                    'r.available',
                    'r.is_delete',
                    'rt.type_name',
                    'rt.room_size',
                    'rt.amenities',
                    'rt.base_price'
                );

            // Filter theo trạng thái
            if ($request->filled('status') && $request->status !== '') {
                $query->where('r.status', $request->status);
            }

            // Filter theo loại phòng
            if ($request->filled('room_type') && $request->room_type !== '') {
                $query->where('r.rt_id', $request->room_type);
            }

            // Filter theo số khách tối đa
            if ($request->filled('max_guests') && $request->max_guests !== '') {
                if ($request->max_guests == '4') {
                    $query->where('r.max_guests', '>=', 4);
                } else {
                    $query->where('r.max_guests', $request->max_guests);
                }
            }

            // Filter theo giá phòng
            if ($request->filled('price_range') && $request->price_range !== '') {
                switch ($request->price_range) {
                    case '0-500000':
                        $query->where('r.price_per_night', '<', 500000);
                        break;
                    case '500000-1000000':
                        $query->whereBetween('r.price_per_night', [500000, 1000000]);
                        break;
                    case '1000000-2000000':
                        $query->whereBetween('r.price_per_night', [1000000, 2000000]);
                        break;
                    case '2000000+':
                        $query->where('r.price_per_night', '>', 2000000);
                        break;
                }
            }

            // Tìm kiếm theo từ khóa
            if ($request->filled('search') && $request->search !== '') {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('r.name', 'LIKE', $searchTerm)
                        ->orWhere('r.description', 'LIKE', $searchTerm)
                        ->orWhere('rt.type_name', 'LIKE', $searchTerm);
                });
            }

            // Sắp xếp và lấy kết quả
            $rooms = $query->orderBy('r.created_at', 'desc')->paginate(10);

            // Lấy danh sách loại phòng cho dropdown filter
            $roomTypes = DB::table('room_types')
                ->where('status', 1)
                ->orderBy('type_name', 'asc')
                ->get();

            // Xử lý dữ liệu format
            foreach ($rooms as $room) {
                // Format giá tiền
                $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';

                // Format giá cơ bản của loại phòng (nếu có)
                if ($room->base_price) {
                    $room->formatted_base_price = number_format($room->base_price, 0, ',', '.') . ' VND';
                }

                // Format ngày tạo
                if ($room->created_at) {
                    $room->formatted_created_at = date('d/m/Y', strtotime($room->created_at));
                }

                // Xử lý danh sách ảnh
                $room->image_list = [];
                if ($room->all_images) {
                    $room->image_list = explode(',', $room->all_images);
                }

                // Hiển thị tên loại phòng hoặc "Không xác định" nếu không có
                $room->room_type_display = $room->type_name ?? 'Không xác định';

                // Xử lý tiện nghi
                $room->amenities_list = [];
                if ($room->room_type_amenities) {
                    $room->amenities_list = array_map('trim', explode(',', $room->room_type_amenities));
                }

                // Tạo rating giả (có thể thay bằng dữ liệu thật từ bảng reviews)
                $room->rating = round(rand(80, 95) / 10, 1);
                $room->review_count = rand(100, 2000);
            }

            // Nếu là AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rooms' => $rooms,
                    'count' => $rooms->count()
                ]);
            }

            return view('admin.rooms.rooms_management', compact('rooms', 'roomTypes'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi khi tìm kiếm: ' . $e->getMessage()
                ]);
            }

            return view('admin.rooms.rooms_management')
                ->with('error', 'Có lỗi khi load danh sách phòng: ' . $e->getMessage())
                ->with('rooms', collect([]))
                ->with('roomTypes', collect([]));
        }
    }

    // Xóa mềm phòng (chuyển is_delete = 1)
    public function delete_room($id)
    {
        try {
            // Kiểm tra phòng có tồn tại không
            $room = DB::table('rooms')->where('r_id', $id)->where('is_delete', 0)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Phòng không tồn tại!');
            }

            // Cập nhật trạng thái is_delete = 1 (xóa mềm)
            $updated = DB::table('rooms')
                ->where('r_id', $id)
                ->update([
                    'is_delete' => 1,
                    'updated_at' => now()
                ]);

            if ($updated) {
                return redirect()->route('admin.rooms.management')
                    ->with('success', 'Chuyển phòng vào thùng rác thành công!');
            } else {
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Không thể chuyển phòng vào thùng rác!');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.rooms.management')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Hiển thị thùng rác
    public function trash(Request $request)
    {
        try {
            // Query lấy các phòng đã bị xóa mềm (is_delete = 1)
            $query = DB::table('rooms as r')
                ->leftJoin('images as i', 'r.r_id', '=', 'i.r_id')
                ->leftJoin('room_types as rt', 'r.rt_id', '=', 'rt.rt_id')
                ->select(
                    'r.*',
                    'rt.type_name',
                    'rt.room_size',
                    'rt.amenities as room_type_amenities',
                    'rt.base_price',
                    DB::raw('GROUP_CONCAT(i.image_path) as all_images'),
                    DB::raw('COUNT(i.id) as image_count')
                )
                ->where('r.is_delete', 1) // Chỉ lấy những phòng đã bị xóa mềm
                ->groupBy(
                    'r.r_id',
                    'r.name',
                    'r.rt_id',
                    'r.price_per_night',
                    'r.max_guests',
                    'r.number_beds',
                    'r.images',
                    'r.status',
                    'r.description',
                    'r.created_at',
                    'r.updated_at',
                    'r.available',
                    'r.is_delete',
                    'rt.type_name',
                    'rt.room_size',
                    'rt.amenities',
                    'rt.base_price'
                );

            // Áp dụng các filter tương tự như rooms_management
            if ($request->filled('status') && $request->status !== '') {
                $query->where('r.status', $request->status);
            }

            if ($request->filled('room_type') && $request->room_type !== '') {
                $query->where('r.rt_id', $request->room_type);
            }

            if ($request->filled('max_guests') && $request->max_guests !== '') {
                if ($request->max_guests == '4') {
                    $query->where('r.max_guests', '>=', 4);
                } else {
                    $query->where('r.max_guests', $request->max_guests);
                }
            }

            if ($request->filled('price_range') && $request->price_range !== '') {
                switch ($request->price_range) {
                    case '0-500000':
                        $query->where('r.price_per_night', '<', 500000);
                        break;
                    case '500000-1000000':
                        $query->whereBetween('r.price_per_night', [500000, 1000000]);
                        break;
                    case '1000000-2000000':
                        $query->whereBetween('r.price_per_night', [1000000, 2000000]);
                        break;
                    case '2000000+':
                        $query->where('r.price_per_night', '>', 2000000);
                        break;
                }
            }

            if ($request->filled('search') && $request->search !== '') {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('r.name', 'LIKE', $searchTerm)
                        ->orWhere('r.description', 'LIKE', $searchTerm)
                        ->orWhere('rt.type_name', 'LIKE', $searchTerm);
                });
            }

            $rooms = $query->orderBy('r.updated_at', 'desc')->get();

            // Lấy danh sách loại phòng cho dropdown filter
            $roomTypes = DB::table('room_types')
                ->where('status', 1)
                ->orderBy('type_name', 'asc')
                ->get();

            // Xử lý dữ liệu format (tương tự như rooms_management)
            foreach ($rooms as $room) {
                $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';

                if ($room->base_price) {
                    $room->formatted_base_price = number_format($room->base_price, 0, ',', '.') . ' VND';
                }

                if ($room->updated_at) {
                    $room->formatted_deleted_at = date('d/m/Y H:i', strtotime($room->updated_at));
                }

                $room->image_list = [];
                if ($room->all_images) {
                    $room->image_list = explode(',', $room->all_images);
                }

                $room->room_type_display = $room->type_name ?? 'Không xác định';

                $room->amenities_list = [];
                if ($room->room_type_amenities) {
                    $room->amenities_list = array_map('trim', explode(',', $room->room_type_amenities));
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rooms' => $rooms,
                    'count' => $rooms->count()
                ]);
            }

            return view('admin.rooms.trash', compact('rooms', 'roomTypes'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi khi tải thùng rác: ' . $e->getMessage()
                ]);
            }

            return view('admin.rooms.trash')
                ->with('error', 'Có lỗi khi load thùng rác: ' . $e->getMessage())
                ->with('rooms', collect([]))
                ->with('roomTypes', collect([]));
        }
    }

    // Khôi phục phòng từ thùng rác
    public function restore($id)
    {
        try {
            $room = DB::table('rooms')->where('r_id', $id)->where('is_delete', 1)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.trash')
                    ->with('error', 'Phòng không tồn tại trong thùng rác!');
            }

            $updated = DB::table('rooms')
                ->where('r_id', $id)
                ->update([
                    'is_delete' => 0,
                    'updated_at' => now()
                ]);

            if ($updated) {
                return redirect()->route('admin.rooms.trash')
                    ->with('success', 'Khôi phục phòng thành công!');
            } else {
                return redirect()->route('admin.rooms.trash')
                    ->with('error', 'Không thể khôi phục phòng!');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.rooms.trash')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Khôi phục tất cả phòng từ thùng rác
    public function restoreAll()
    {
        try {
            $updated = DB::table('rooms')
                ->where('is_delete', 1)
                ->update([
                    'is_delete' => 0,
                    'updated_at' => now()
                ]);

            if ($updated > 0) {
                return redirect()->route('admin.rooms.trash')
                    ->with('success', "Đã khôi phục {$updated} phòng thành công!");
            } else {
                return redirect()->route('admin.rooms.trash')
                    ->with('error', 'Không có phòng nào để khôi phục!');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.rooms.trash')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Xóa vĩnh viễn một phòng
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $room = DB::table('rooms')->where('r_id', $id)->where('is_delete', 1)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.trash')
                    ->with('error', 'Phòng không tồn tại trong thùng rác!');
            }

            // Xóa ảnh khỏi thư mục nếu có
            if ($room->images) {
                $imagePath = public_path($room->images);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Xóa ảnh trong bảng images liên quan
            DB::table('images')->where('r_id', $id)->delete();

            // Xóa phòng khỏi database
            $deleted = DB::table('rooms')->where('r_id', $id)->delete();

            if ($deleted) {
                DB::commit();
                return redirect()->route('admin.rooms.trash')
                    ->with('success', 'Xóa vĩnh viễn phòng thành công!');
            } else {
                DB::rollback();
                return redirect()->route('admin.rooms.trash')
                    ->with('error', 'Không thể xóa vĩnh viễn phòng!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.rooms.trash')
                ->with('error', 'Có lỗi xảy ra: ' . 'Phòng đã được đặt, không thể xóa');
        }
    }

    // Xóa vĩnh viễn tất cả phòng trong thùng rác
    public function forceDeleteAll()
    {
        try {
            DB::beginTransaction();

            $rooms = DB::table('rooms')->where('is_delete', 1)->get();

            if ($rooms->isEmpty()) {
                return redirect()->route('admin.rooms.trash')
                    ->with('error', 'Không có phòng nào trong thùng rác để xóa!');
            }

            $deletedCount = 0;
            foreach ($rooms as $room) {
                // Xóa ảnh khỏi thư mục nếu có
                if ($room->images) {
                    $imagePath = public_path($room->images);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // Xóa ảnh trong bảng images liên quan
                DB::table('images')->where('r_id', $room->r_id)->delete();

                // Xóa phòng
                DB::table('rooms')->where('r_id', $room->r_id)->delete();
                $deletedCount++;
            }

            DB::commit();
            return redirect()->route('admin.rooms.trash')
                ->with('success', "Đã xóa vĩnh viễn {$deletedCount} phòng thành công!");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.rooms.trash')
                ->with('error', 'Có lỗi xảy ra: ' . 'Phòng đã được đặt, không thể xóa');
        }
    }

    // public function view_room($id)
    // {
    //     try {
    //         $room = DB::table('rooms')->where('r_id', $id)->first();

    //         if (!$room) {
    //             return redirect()->route('admin.rooms.management')
    //                 ->with('error', 'Phòng không tồn tại!');
    //         }

    //         // Format dữ liệu để hiển thị
    //         $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';

    //         if ($room->created_at) {
    //             $room->formatted_created_at = date('d/m/Y H:i', strtotime($room->created_at));
    //         }

    //         return view('admin.rooms.view_room', compact('room'));
    //     } catch (\Exception $e) {
    //         return redirect()->route('admin.rooms.management')
    //             ->with('error', 'Có lỗi khi load thông tin phòng: ' . $e->getMessage());
    //     }
    // }

    public function edit_room_handle(Request $request, $id)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'rt_id' => 'required|integer|exists:room_types,rt_id', // Thêm validation cho loại phòng
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'nullable|integer|min:1|max:10',
            'number_beds' => 'nullable|integer|min:1|max:10',
            'status' => 'required|boolean',
            'available' => 'required|boolean', // Thêm trường available
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'delete_images' => 'nullable|string',
            'main_image' => 'nullable|string'
        ], [
            'name.required' => 'Tên phòng là bắt buộc',
            'name.max' => 'Tên phòng không được quá 50 ký tự',
            'rt_id.required' => 'Loại phòng là bắt buộc',
            'rt_id.exists' => 'Loại phòng không hợp lệ',
            'price_per_night.required' => 'Giá phòng là bắt buộc',
            'price_per_night.numeric' => 'Giá phòng phải là số',
            'price_per_night.min' => 'Giá phòng phải lớn hơn 0',
            'max_guests.integer' => 'Số khách tối đa phải là số nguyên',
            'number_beds.integer' => 'Số giường phải là số nguyên',
            'status.required' => 'Trạng thái là bắt buộc',
            'available.required' => 'Tình trạng sẵn sàng là bắt buộc',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'images.*.max' => 'Kích thước hình ảnh không được quá 5MB'
        ]);

        try {
            DB::beginTransaction();

            // Kiểm tra phòng có tồn tại không
            $room = DB::table('rooms')->where('r_id', $id)->first();

            if (!$room) {
                return redirect()->route('admin.rooms.management')
                    ->with('error', 'Phòng không tồn tại!');
            }

            // Xử lý xóa ảnh cũ
            if ($request->filled('delete_images')) {
                $deleteImageIds = explode(',', $request->delete_images);
                $deleteImages = DB::table('images')
                    ->whereIn('id', $deleteImageIds)
                    ->where('r_id', $id)
                    ->get();

                foreach ($deleteImages as $img) {
                    // Xóa file vật lý
                    $absolutePath = public_path($img->image_path);
                    if (file_exists($absolutePath)) {
                        unlink($absolutePath);
                    }
                }

                // Xóa trong database
                DB::table('images')
                    ->whereIn('id', $deleteImageIds)
                    ->where('r_id', $id)
                    ->delete();
            }

            // Xử lý upload ảnh mới
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                $destinationPath = public_path('uploads/rooms');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $fileName);
                    $imagePath = 'uploads/rooms/' . $fileName;

                    // Lưu vào database
                    $imageId = DB::table('images')->insertGetId([
                        'r_id' => $id,
                        'image_path' => $imagePath,
                        'created_at' => now()
                    ]);

                    $uploadedImages[] = [
                        'id' => $imageId,
                        'path' => $imagePath
                    ];
                }
            }

            // Xác định ảnh chính
            $mainImagePath = $room->images; // Giữ ảnh chính cũ

            // Nếu có chọn ảnh chính mới
            if ($request->filled('main_image')) {
                $mainImage = DB::table('images')
                    ->where('id', $request->main_image)
                    ->where('r_id', $id)
                    ->first();

                if ($mainImage) {
                    $mainImagePath = $mainImage->image_path;
                }
            } elseif (empty($mainImagePath) && !empty($uploadedImages)) {
                // Nếu không có ảnh chính và vừa upload ảnh mới, chọn ảnh đầu tiên làm ảnh chính
                $mainImagePath = $uploadedImages[0]['path'];
            }

            // Cập nhật thông tin phòng
            $updated = DB::table('rooms')->where('r_id', $id)->update([
                'name' => $validatedData['name'],
                'rt_id' => $validatedData['rt_id'], // Thêm cập nhật loại phòng
                'price_per_night' => $validatedData['price_per_night'],
                'max_guests' => $validatedData['max_guests'] ?? null,
                'number_beds' => $validatedData['number_beds'] ?? null,
                'images' => $mainImagePath, // Ảnh chính
                'status' => $validatedData['status'],
                'available' => $validatedData['available'], // Thêm cập nhật available
                'description' => $validatedData['description'] ?? null,
                'updated_at' => now()
            ]);

            if ($updated || !empty($uploadedImages) || $request->filled('delete_images')) {
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
            if (!empty($uploadedImages)) {
                foreach ($uploadedImages as $img) {
                    $absolutePath = public_path($img['path']);
                    if (file_exists($absolutePath)) {
                        unlink($absolutePath);
                    }
                }

                // Xóa trong database
                $imageIds = array_column($uploadedImages, 'id');
                DB::table('images')->whereIn('id', $imageIds)->delete();
            }

            return redirect()->back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Cập nhật method để lấy danh sách ảnh của phòng và loại phòng
    public function edit_room_form($id)
    {
        // Lấy thông tin phòng với join để có tên loại phòng
        $room = DB::table('rooms')
            ->leftJoin('room_types', 'rooms.rt_id', '=', 'room_types.rt_id')
            ->select('rooms.*', 'room_types.type_name')
            ->where('rooms.r_id', $id)
            ->first();

        if (!$room) {
            return redirect()->route('admin.rooms.management')
                ->with('error', 'Phòng không tồn tại!');
        }

        // Lấy tất cả loại phòng để hiển thị trong dropdown
        $roomTypes = DB::table('room_types')
            ->where('status', 1) // Chỉ lấy loại phòng đang hoạt động
            ->orderBy('type_name', 'asc')
            ->get();

        // Lấy tất cả ảnh của phòng
        $roomImages = DB::table('images')
            ->where('r_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.rooms.edit_room', compact('room', 'roomImages', 'roomTypes'));
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
            $rooms = $query->limit(16)->get();

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
            $serviceCategories = ServiceCategory::with('services')->get();

            // Lấy danh sách dịch vụ (ví dụ 6 dịch vụ có sẵn)
            $services = ServiceCategory::all();


            // Trả về view
            return view('user.home', compact('rooms', 'stats', 'services', 'serviceCategories'));
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




    public function all_rooms(Request $request)
    {
        try {
            // Validate nhẹ các tham số lọc (optional)
            $request->validate([
                'check_in_date'  => 'nullable|date|after_or_equal:today',
                'check_out_date' => 'nullable|date|after:check_in_date',
                'availability'   => 'nullable|in:all,available,unavailable',
                'guests'         => 'nullable|integer|min:1|max:10',
                'min_price'      => 'nullable|numeric|min:0',
                'max_price'      => 'nullable|numeric|min:0',
                'sort_by'        => 'nullable|string',
                'per_page'       => 'nullable|integer|min:1|max:100',
            ]);

            $checkInDate  = $request->input('check_in_date');
            $checkOutDate = $request->input('check_out_date');
            $hasDates     = $checkInDate && $checkOutDate;
            $guests       = $request->input('guests');

            // Base query: TẤT CẢ loại phòng đang hoạt động
            $baseQuery = DB::table('room_types as rt')
                ->where('rt.status', 1)
                ->where('rt.is_delete', 0);

            // Lọc sức chứa từ loại phòng
            if ($guests) {
                $baseQuery->where('rt.max_guests', '>=', $guests);
            }

            // Lọc theo khoảng giá (base_price)
            if ($request->filled('min_price')) {
                $baseQuery->where('rt.base_price', '>=', (int)$request->min_price);
            }
            if ($request->filled('max_price')) {
                $baseQuery->where('rt.base_price', '<=', (int)$request->max_price);
            }

            // Tìm kiếm theo từ khóa trên loại phòng
            if ($request->filled('search') && $request->search !== '') {
                $kw = '%' . $request->search . '%';
                $baseQuery->where(function ($q) use ($kw) {
                    $q->where('rt.type_name', 'like', $kw)
                        ->orWhere('rt.description', 'like', $kw);
                });
            }

            // Query chính để lấy danh sách loại phòng + đếm số phòng con AVAILABLE theo điều kiện
            $rtQuery = clone $baseQuery;

            // Chọn dữ liệu và alias để khớp view cũ (đang dùng $room->name, price_per_night, ...)
            $rtQuery->select([
                'rt.rt_id as r_id',
                'rt.type_name as name',
                'rt.description',
                DB::raw('rt.base_price as price_per_night'),
                'rt.max_guests',
                'rt.number_beds',
                'rt.images',
                'rt.created_at',
            ]);

            // Tính số phòng con có thể dùng (available_rooms_count) cho từng loại
            $rtQuery->selectSub(function ($q) use ($hasDates, $checkInDate, $checkOutDate, $guests) {
                $q->from('rooms as r')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('r.rt_id', 'rt.rt_id')
                    ->where('r.status', 1)
                    ->where('r.is_delete', 0);

                if ($guests) {
                    $q->where('r.max_guests', '>=', $guests);
                }

                if ($hasDates) {
                    // Đang lọc theo ngày: phòng con phải còn trống trong khoảng này
                    $q->where('r.available', 1)
                        ->whereNotExists(function ($sub) use ($checkInDate, $checkOutDate) {
                            $sub->from('booking_details as bd')
                                ->select(DB::raw(1))
                                ->join('booking as b', 'bd.b_id', '=', 'b.b_id')
                                ->whereColumn('bd.r_id', 'r.r_id')
                                // Chỉ tính các booking "đang giữ phòng": đã thanh toán và còn hiệu lực
                                ->where('b.payment_status', 1)
                                ->where(function ($dateQ) use ($checkInDate, $checkOutDate) {
                                    // overlap 3 trường hợp
                                    $dateQ->where(function ($q1) use ($checkInDate, $checkOutDate) {
                                        $q1->where('b.check_in_date', '>=', $checkInDate)
                                            ->where('b.check_in_date', '<',  $checkOutDate);
                                    })
                                        ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                            $q2->where('b.check_out_date', '>',  $checkInDate)
                                                ->where('b.check_out_date', '<=', $checkOutDate);
                                        })
                                        ->orWhere(function ($q3) use ($checkInDate, $checkOutDate) {
                                            $q3->where('b.check_in_date', '<=', $checkInDate)
                                                ->where('b.check_out_date', '>=', $checkOutDate);
                                        });
                                });
                        });
                } else {
                    // Không lọc theo ngày: coi available theo cờ hiện tại của phòng
                    $q->where('r.available', 1);
                }
            }, 'available_rooms_count');

            // Lọc theo trạng thái "còn phòng / hết phòng" ở cấp loại phòng
            if ($request->filled('availability') && $request->availability !== 'all') {
                if ($request->availability === 'available') {
                    $rtQuery->having('available_rooms_count', '>', 0);
                } elseif ($request->availability === 'unavailable') {
                    $rtQuery->having('available_rooms_count', '=', 0);
                }
            }

            // Sắp xếp
            $sortBy    = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            switch ($sortBy) {
                case 'price_low':
                case 'price-asc':
                    $rtQuery->orderBy('price_per_night', 'asc');
                    break;
                case 'price_high':
                case 'price-desc':
                    $rtQuery->orderBy('price_per_night', 'desc');
                    break;
                case 'rating-desc': // tạm dùng created_at (nếu bạn có bảng reviews thì thay bằng avg rating)
                    $rtQuery->orderBy('created_at', 'desc');
                    break;
                case 'name-asc':
                    $rtQuery->orderBy('name', 'asc');
                    break;
                case 'popular':
                    $rtQuery->inRandomOrder();
                    break;
                default:
                    $rtQuery->orderBy('created_at', 'desc');
            }

            // Phân trang
            $perPage = (int) $request->get('per_page', 20);
            $roomTypes = $rtQuery->paginate($perPage)->withQueryString();

            // Map dữ liệu để khớp view "rooms"
            foreach ($roomTypes as $rt) {
                // --- ẢNH: chuẩn hóa thành $rt->image_url ---
                $imgCandidate = null;

                if (!empty($rt->images)) {
                    // 1) Nếu là JSON array -> lấy phần tử đầu
                    $decoded = json_decode($rt->images, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && count($decoded)) {
                        $imgCandidate = trim((string)$decoded[0]);
                    } else {
                        // 2) Nếu là chuỗi nhiều ảnh phân tách dấu phẩy -> lấy ảnh đầu
                        if (strpos($rt->images, ',') !== false) {
                            $parts = array_map('trim', explode(',', $rt->images));
                            $imgCandidate = trim((string)($parts[0] ?? ''));
                        } else {
                            // 3) Chuỗi ảnh đơn
                            $imgCandidate = trim((string)$rt->images);
                        }
                    }
                }

                // Mặc định ảnh fallback
                $rt->image_url = asset('assets/images/default-room.jpg');

                if ($imgCandidate) {
                    if (Str::startsWith($imgCandidate, ['http://', 'https://'])) {
                        // URL tuyệt đối
                        $rt->image_url = $imgCandidate;
                    } elseif (Storage::disk('public')->exists($imgCandidate)) {
                        // Nằm trong storage/app/public (đã storage:link)
                        $rt->image_url = Storage::url($imgCandidate);
                    } elseif (file_exists(public_path($imgCandidate))) {
                        // Nằm trực tiếp trong public/
                        $rt->image_url = asset($imgCandidate);
                    } elseif (Str::startsWith($imgCandidate, ['storage/', 'uploads/', 'images/'])) {
                        // Trường hợp đường dẫn tương đối đã “web-accessible”
                        $rt->image_url = asset($imgCandidate);
                    }
                }

                // --- Các field khác của bạn giữ nguyên ---
                $rt->available = ((int)($rt->available_rooms_count ?? 0)) > 0;
                $rt->formatted_price = number_format((int)$rt->price_per_night, 0, ',', '.') . ' VND';
                $rt->discount_percent = rand(10, 30);
                $rt->old_price = (int)$rt->price_per_night * (1 + $rt->discount_percent / 100);
                $rt->formatted_old_price = number_format($rt->old_price, 0, ',', '.') . ' VND';
                $rt->rating = round(rand(40, 50) / 10, 1);
                $rt->review_count = rand(50, 500);
                $rt->css_class = $rt->available ? 'room-available' : 'room-unavailable';
            }


            // Thống kê tổng quan (tính theo loại phòng)
            $totalTypes = (clone $baseQuery)->count();

            // Đếm loại phòng còn trống theo điều kiện hiện tại
            $availableTypesCount = (clone $baseQuery)
                ->whereExists(function ($q) use ($hasDates, $checkInDate, $checkOutDate, $guests) {
                    $q->from('rooms as r')->select(DB::raw(1))
                        ->whereColumn('r.rt_id', 'rt.rt_id')
                        ->where('r.status', 1)
                        ->where('r.is_delete', 0)
                        ->where('r.available', 1);

                    if ($guests) {
                        $q->where('r.max_guests', '>=', $guests);
                    }

                    if ($hasDates) {
                        $q->whereNotExists(function ($sub) use ($checkInDate, $checkOutDate) {
                            $sub->from('booking_details as bd')->select(DB::raw(1))
                                ->join('booking as b', 'bd.b_id', '=', 'b.b_id')
                                ->whereColumn('bd.r_id', 'r.r_id')
                                ->where('b.payment_status', 1)
                                ->where(function ($dateQ) use ($checkInDate, $checkOutDate) {
                                    $dateQ->where(function ($q1) use ($checkInDate, $checkOutDate) {
                                        $q1->where('b.check_in_date', '>=', $checkInDate)
                                            ->where('b.check_in_date', '<',  $checkOutDate);
                                    })
                                        ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                            $q2->where('b.check_out_date', '>',  $checkInDate)
                                                ->where('b.check_out_date', '<=', $checkOutDate);
                                        })
                                        ->orWhere(function ($q3) use ($checkInDate, $checkOutDate) {
                                            $q3->where('b.check_in_date', '<=', $checkInDate)
                                                ->where('b.check_out_date', '>=', $checkOutDate);
                                        });
                                });
                        });
                    }
                })
                ->count();

            $unavailableTypesCount = max($totalTypes - $availableTypesCount, 0);

            // Thống kê giá (dựa trên base_price của loại phòng)
            $avgPrice = (clone $baseQuery)->avg('rt.base_price');
            $minPrice = (clone $baseQuery)->min('rt.base_price');
            $maxPrice = (clone $baseQuery)->max('rt.base_price');

            $stats = [
                'total_rooms'       => $totalTypes,               // tổng LOẠI PHÒNG
                'available_rooms'   => $availableTypesCount,      // số LOẠI PHÒNG còn trống
                'unavailable_rooms' => $unavailableTypesCount,    // số LOẠI PHÒNG hết phòng
                'avg_price' => $avgPrice ? number_format($avgPrice, 0, ',', '.') . ' VND' : '0 VND',
                'min_price' => $minPrice ? number_format($minPrice, 0, ',', '.') . ' VND' : '0 VND',
                'max_price' => $maxPrice ? number_format($maxPrice, 0, ',', '.') . ' VND' : '0 VND',
            ];

            // Nếu là AJAX => trả JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rooms'   => $roomTypes->items(), // alias types -> rooms
                    'stats'   => $stats,
                    'count'   => $roomTypes->total(),
                    'pagination' => [
                        'current_page' => $roomTypes->currentPage(),
                        'last_page'    => $roomTypes->lastPage(),
                        'per_page'     => $roomTypes->perPage(),
                        'total'        => $roomTypes->total(),
                        'has_more'     => $roomTypes->hasMorePages(),
                    ]
                ]);
            }

            // Lấy thêm dữ liệu nếu cần cho sidebar...
            $serviceCategories = \App\Models\ServiceCategory::with('services')->get();

            // ĐỔ RA VIEW HIỆN TẠI (alias $roomTypes -> $rooms để khớp view cũ)
            $rooms = $roomTypes;

            return view('user.all_rooms', compact('rooms', 'stats', 'serviceCategories'));
        } catch (\Exception $e) {
            \Log::error('Error loading all room types: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi khi tải danh sách loại phòng'
                ], 500);
            }

            // Fallback view
            return view('user.all_rooms')
                ->with('error', 'Có lỗi khi tải danh sách loại phòng')
                ->with('rooms', collect([])->paginate(1))
                ->with('stats', [
                    'total_rooms' => 0,
                    'available_rooms' => 0,
                    'unavailable_rooms' => 0,
                    'avg_price' => '0 VND',
                    'min_price' => '0 VND',
                    'max_price' => '0 VND',
                ]);
        }
    }


    // Method bổ sung để filter AJAX (nếu cần)
    public function filter_rooms(Request $request)
    {
        // Gọi lại method all_rooms với request hiện tại
        return $this->all_rooms($request);
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

            // Lấy tất cả ảnh của phòng từ bảng images
            $roomImages = DB::table('images')
                ->where('r_id', $id)
                ->orderBy('created_at', 'asc')
                ->get();

            // Xử lý ảnh phòng
            $validImages = [];
            if ($roomImages->isNotEmpty()) {
                foreach ($roomImages as $image) {
                    if ($image->image_path && file_exists(public_path($image->image_path))) {
                        $validImages[] = $image->image_path;
                    }
                }
            }

            // Nếu không có ảnh hợp lệ từ bảng images, sử dụng ảnh từ bảng rooms
            if (empty($validImages)) {
                if ($room->images && file_exists(public_path($room->images))) {
                    $validImages[] = $room->images;
                } else {
                    $validImages[] = 'assets/images/default-room.jpg';
                }
            }

            // Gán ảnh vào room object
            $room->all_images = $validImages;
            $room->main_image = $validImages[0]; // Ảnh chính là ảnh đầu tiên

            // Format dữ liệu chi tiết
            $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';
            $room->rating = round(rand(40, 50) / 10, 1);
            $room->review_count = rand(50, 500);

            if ($room->created_at) {
                $room->formatted_created_at = date('d/m/Y H:i', strtotime($room->created_at));
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

                // Lấy ảnh chính cho phòng liên quan
                $relatedRoomImage = DB::table('images')
                    ->where('r_id', $relatedRoom->r_id)
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ($relatedRoomImage && file_exists(public_path($relatedRoomImage->image_path))) {
                    $relatedRoom->main_image = $relatedRoomImage->image_path;
                } elseif ($relatedRoom->images && file_exists(public_path($relatedRoom->images))) {
                    $relatedRoom->main_image = $relatedRoom->images;
                } else {
                    $relatedRoom->main_image = 'assets/images/default-room.jpg';
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

        $roomTypeId = $request->input('rt_id') ?: $request->input('room_type_id');
        $roomType = $roomTypeId
            ? DB::table('room_types')->where('rt_id', $roomTypeId)->first()
            : null;

        $checkin  = $request->input('checkin') ?: $request->input('check_in');
        $checkout = $request->input('checkout') ?: $request->input('check_out');
        $guests   = $request->input('guests');
        $discount_code = $request->input('discount_code');
        $voucher  = null;
        $discount_percent = 0;
        $discount_amount  = 0;

        // --- Tiền phòng ---
        $nights = 0;
        $roomTotal = 0;
        if ($roomType && $checkin && $checkout) {
            $nights = (strtotime($checkout) - strtotime($checkin)) / 86400;
            if ($nights < 1) $nights = 1;
            $roomTotal = $nights * (int)$roomType->base_price;

            if ($discount_code) {
                $voucher = DB::table('vouchers')
                    ->where('v_code', $discount_code)
                    ->where('status', 1)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();
                if ($voucher) {
                    $discount_percent = (int)$voucher->discount_percent;
                    $discount_amount  = (int) round($roomTotal * $discount_percent / 100);
                }
            }
        }

        // --- Tiền dịch vụ ---
        $selectedServices = $request->input('services', []);
        $services_total = 0;
        if (!empty($selectedServices)) {
            $serviceIds = array_map(fn($s) => (int)($s['s_id'] ?? 0), $selectedServices);
            $serviceRows = DB::table('services')
                ->whereIn('s_id', $serviceIds)
                ->where('is_available', 1)
                ->pluck('price', 's_id');

            foreach ($selectedServices as $svc) {
                $sid = (int)($svc['s_id'] ?? 0);
                $qty = (int)($svc['quantity'] ?? 0);
                if ($sid <= 0 || $qty <= 0) continue;

                $unitPrice = (int)($serviceRows[$sid] ?? 0);
                $services_total += $qty * $unitPrice;
            }
        }

        // --- Tổng cuối cùng ---
        $final_total = max(0, $roomTotal - $discount_amount + $services_total);

        return view('user.payment', [
            'user'             => $user,
            'roomType'         => $roomType,
            'checkin'          => $checkin,
            'checkout'         => $checkout,
            'guests'           => $guests,
            'discount_code'    => $discount_code,
            'discount_percent' => $discount_percent,
            'discount_amount'  => $discount_amount,
            'voucher'          => $voucher,

            'nights'       => $nights,
            'roomTotal'    => $roomTotal,
            'services_total' => $services_total,
            'final_total'  => $final_total,
        ]);
    }

    public function processPayment(Request $request)
    {
        try {
            Log::info('ProcessPayment request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'rt_id'          => 'required|exists:room_types,rt_id',
                'checkin'        => 'required|date',
                'checkout'       => 'required|date|after:checkin',
                'guests'         => 'required|integer|min:1',
                'payment_method' => 'required|string|in:vnpay,cash',
                // services_total và services[...] để tự tính lại, không cần validate chặt tại đây
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Dữ liệu không hợp lệ: ' . $validator->errors()->first());
            }

            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục!');
            }

            $roomType = DB::table('room_types')->where('rt_id', $request->rt_id)->first();
            if (!$roomType || (int)$roomType->status !== 1) {
                return back()->with('error', 'Loại phòng này hiện không khả dụng!');
            }

            // --- Tính tiền ---
            $nights = (strtotime($request->checkout) - strtotime($request->checkin)) / 86400;
            if ($nights < 1) $nights = 1;

            $roomTotal      = $nights * (int)$roomType->base_price;
            $discountAmount = (int)($request->discount_amount ?? 0);

            // TÍNH LẠI tiền dịch vụ từ mảng services[...] (ưu tiên an toàn)
            [$servicesTotal, $normalizedServices] = $this->computeServicesTotal(
                $request->input('services', [])
            );

            // Nếu không có mảng, fallback dùng trường services_total client gửi (nếu có)
            if ($servicesTotal === 0) {
                $servicesTotal = (int)($request->input('services_total', 0));
            }

            $finalAmount = max(0, $roomTotal - $discountAmount + $servicesTotal);

            Log::info('Payment calculation:', [
                'nights'          => $nights,
                'base_price'      => (int)$roomType->base_price,
                'room_total'      => $roomTotal,
                'discount_amount' => $discountAmount,
                'services_total'  => $servicesTotal,
                'final_amount'    => $finalAmount
            ]);

            // --- Điều hướng theo phương thức ---
            if ($request->payment_method === 'vnpay') {
                return $this->processVNPayPayment(
                    $request,
                    $user,
                    $roomType,
                    $finalAmount,
                    $nights,
                    $servicesTotal,
                    $normalizedServices
                );
            }

            // cash
            return $this->processCashPayment(
                $request,
                $user,
                $roomType,
                $finalAmount,
                $nights,
                $servicesTotal,
                $normalizedServices
            );
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Tính tổng dịch vụ từ mảng services[SID][quantity|unit_price|pricing_model].
     * Trả về [$total, $normalizedArray].
     * Hiện tại pricing_model=0 (once); nếu bạn mở rộng per_night/per_guest_per_night, thêm logic tại đây.
     */
    private function computeServicesTotal(array $services): array
    {
        if (empty($services)) {
            return [0, []];
        }

        // Lấy giá thực tế từ DB để chống sửa giá phía client
        $ids = array_map(fn($s) => (int)($s['s_id'] ?? 0), $services);
        $ids = array_values(array_filter($ids, fn($id) => $id > 0));
        if (empty($ids)) {
            return [0, []];
        }

        $prices = DB::table('services')
            ->whereIn('s_id', $ids)
            ->where('is_available', 1)
            ->pluck('price', 's_id');

        $total = 0;
        $normalized = [];
        foreach ($services as $key => $svc) {
            $sid          = (int)($svc['s_id'] ?? $key);
            $qty          = max(0, (int)($svc['quantity'] ?? 0));
            $pricingModel = (int)($svc['pricing_model'] ?? 0);

            if ($sid <= 0 || $qty <= 0) continue;

            $unit = (int)($prices[$sid] ?? 0); // dùng giá DB
            if ($unit <= 0) continue;

            // hiện tại: once
            $line = $qty * $unit;
            $total += $line;

            $normalized[$sid] = [
                's_id'          => $sid,
                'quantity'      => $qty,
                'unit_price'    => $unit,
                'pricing_model' => $pricingModel,
                'line_total'    => $line,
            ];
        }

        return [$total, $normalized];
    }

    private function processVNPayPayment($request, $user, $roomType, $amount, $nights, $servicesTotal = 0, array $services = [])
    {
        try {
            $vnp_TmnCode   = "1TFT191D";
            $vnp_HashSecret = "1OVMPGLZGJNAJW4PYC2SRK4MGSI1PWJQ";
            $vnp_Url       = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_ReturnUrl = route('vnpay.return');

            $vnp_TxnRef = 'BOOKING_RT_' . time() . '_' . $user->id;

            // LƯU ĐẦY ĐỦ dữ liệu (kể cả dịch vụ) để tạo record sau khi VNPay trả về
            $bookingData = [
                'u_id'            => $user->id,
                'rt_id'           => (int)$request->rt_id,
                'check_in_date'   => $request->checkin,
                'check_out_date'  => $request->checkout,
                'guests'          => (int)$request->guests,
                'nights'          => $nights,
                'room_total'      => $nights * (int)$roomType->base_price,
                'discount_code'   => $request->discount_code,
                'discount_amount' => (int)($request->discount_amount ?? 0),
                'services_total'  => (int)$servicesTotal,
                'services'        => $services, // để tạo bảng booking_services_details
                'total_price'     => (int)$amount, // FINAL (đã gồm dịch vụ & giảm giá)
                'payment_method'  => 'vnpay',
                'vnp_TxnRef'      => $vnp_TxnRef,
                'created_at'      => now()->toDateTimeString(),
            ];
            session(['booking_data' => $bookingData]);
            Log::info('Saved booking data to session (for VNPay):', $bookingData);

            // Tham số VNPay — dùng FINAL AMOUNT
            $inputData = [
                "vnp_Version"  => "2.1.0",
                "vnp_TmnCode"  => $vnp_TmnCode,
                "vnp_Amount"   => $amount * 100, // VNP yêu cầu *100
                "vnp_Command"  => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr"   => $request->ip(),
                "vnp_Locale"   => "vn",
                "vnp_OrderInfo" => "Thanh toan dat loai phong " . $roomType->type_name,
                "vnp_OrderType" => "other",
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef"   => $vnp_TxnRef,
            ];

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

            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            Log::info('VNPay redirect URL created:', ['url' => $vnp_Url]);

            return redirect($vnp_Url);
        } catch (\Exception $e) {
            Log::error('VNPay processing error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processCashPayment($request, $user, $roomType, $amount, $nights)
    {
        try {
            DB::beginTransaction();

            // -------------------------
            // 1) Tính lại dịch vụ kèm theo từ request (an toàn – dùng giá DB)
            // -------------------------
            $servicesInput = $request->input('services', []); // dạng services[SID][quantity,unit_price,pricing_model]
            $normalizedServices = [];
            if (!empty($servicesInput)) {
                // lấy các s_id hợp lệ
                $ids = array_map(fn($s) => (int)($s['s_id'] ?? 0), $servicesInput);
                $ids = array_values(array_filter($ids, fn($id) => $id > 0));

                if (!empty($ids)) {
                    // Giá lấy từ DB để chống client sửa
                    $priceMap = DB::table('services')
                        ->whereIn('s_id', $ids)
                        ->where('is_available', 1)
                        ->pluck('price', 's_id');

                    foreach ($servicesInput as $k => $svc) {
                        $sid = (int)($svc['s_id'] ?? $k);
                        $qty = max(0, (int)($svc['quantity'] ?? 0));
                        $pricingModel = (int)($svc['pricing_model'] ?? 0); // hiện tại 0=once

                        if ($sid <= 0 || $qty <= 0) continue;

                        $unit = (int)($priceMap[$sid] ?? 0);
                        if ($unit <= 0) continue;

                        $normalizedServices[$sid] = [
                            's_id'          => $sid,
                            'quantity'      => $qty,
                            'unit_price'    => $unit,       // chốt giá theo DB
                            'pricing_model' => $pricingModel,
                        ];
                    }
                }
            }

            // -------------------------
            // 2) Tạo booking
            // -------------------------
            $bookingId = strtoupper(Str::random(8));

            Log::info('Creating cash booking:', [
                'booking_id' => $bookingId,
                'user_id'    => $user->id,
                'rt_id'      => $request->rt_id,
                'amount'     => $amount
            ]);

            $bookingInserted = DB::table('booking')->insert([
                'b_id'           => $bookingId,
                'u_id'           => $user->id,
                'check_in_date'  => $request->checkin,
                'check_out_date' => $request->checkout,
                'status'         => 1, // Pending - chờ admin xác nhận
                'payment_status' => 0, // Chưa thanh toán
                'total_price'    => $amount, // ĐÃ gồm dịch vụ + giảm giá (đã tính từ trước)
                'created_at'     => now(),
                'updated_at'     => now()
            ]);
            if (!$bookingInserted) {
                throw new \Exception('Failed to insert booking record');
            }

            // -------------------------
            // 3) booking_details (rt_id, discount…)
            // -------------------------
            $detailsInserted = DB::table('booking_details')->insert([
                'b_id'            => $bookingId,
                'rt_id'           => $request->rt_id,
                'description'     => $roomType->type_name . ' - ' . ($roomType->description ?? ''),
                'guests'          => $request->guests,
                'discount_code'   => $request->discount_code,
                'discount_amount' => $request->discount_amount ?? 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
            if (!$detailsInserted) {
                throw new \Exception('Failed to insert booking details record');
            }

            // -------------------------
            // 4) Ghi dịch vụ kèm theo vào booking_services_details (nếu có)
            // -------------------------
            if (!empty($normalizedServices)) {
                $rows = [];
                $ts = now();
                foreach ($normalizedServices as $s) {
                    $rows[] = [
                        'b_id'          => $bookingId,
                        's_id'          => $s['s_id'],
                        'quantity'      => $s['quantity'],
                        'unit_price'    => $s['unit_price'],
                        'pricing_model' => $s['pricing_model'], // 0=once
                        'scheduled_at'  => null,
                        'note'          => null,
                        'created_at'    => $ts,
                        'updated_at'    => $ts,
                    ];
                }
                DB::table('booking_services_details')->insert($rows);
            }

            // -------------------------
            // 5) payment
            // -------------------------
            $paymentInserted = DB::table('payments')->insert([
                'b_id'            => $bookingId,
                'payment_method'  => 'cash',
                'amount'          => $amount,
                'status'          => 0, // Pending
                'transaction_id'  => 'CASH_' . $bookingId,
                'created_at'      => now(),
                'updated_at'      => now()
            ]);
            if (!$paymentInserted) {
                throw new \Exception('Failed to insert payment record');
            }

            DB::commit();

            Log::info('Cash booking created successfully:', ['booking_id' => $bookingId]);

            return redirect()->route('booking.success', ['id' => $bookingId])
                ->with('success', 'Đặt phòng thành công! Vui lòng thanh toán tiền mặt khi nhận phòng.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cash payment error: ' . $e->getMessage(), [
                'trace'        => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "1OVMPGLZGJNAJW4PYC2SRK4MGSI1PWJQ";

        Log::info('VNPay return data:', $request->all());

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
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
            if ($secureHash != $vnp_SecureHash) {
                Log::error('Invalid secure hash', [
                    'calculated' => $secureHash,
                    'received'   => $vnp_SecureHash
                ]);
                return redirect()->route('index')->with('error', 'Chữ ký không hợp lệ!');
            }

            $bookingData = session('booking_data');
            Log::info('Retrieved booking data from session:', $bookingData ?? []);

            if (!$bookingData) {
                return redirect()->route('index')->with('error', 'Không tìm thấy thông tin đặt phòng!');
            }

            if (($request->vnp_ResponseCode ?? '') === '00') {
                // Thanh toán thành công
                DB::beginTransaction();

                $bookingId = strtoupper(Str::random(8));

                Log::info('Creating VNPay booking:', [
                    'booking_id'        => $bookingId,
                    'vnp_response_code' => $request->vnp_ResponseCode,
                    'booking_data'      => $bookingData
                ]);

                // 1) booking
                $bookingInserted = DB::table('booking')->insert([
                    'b_id'           => $bookingId,
                    'u_id'           => $bookingData['u_id'],
                    'check_in_date'  => $bookingData['check_in_date'],
                    'check_out_date' => $bookingData['check_out_date'],
                    'status'         => 1,  // pending/đã đặt (tuỳ workflow của bạn)
                    'payment_status' => 1,  // đã thanh toán
                    'total_price'    => (int)$bookingData['total_price'], // FINAL gồm dịch vụ + giảm giá
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);
                if (!$bookingInserted) {
                    throw new \Exception('Failed to insert booking record');
                }

                // 2) room type
                $roomType = DB::table('room_types')->where('rt_id', $bookingData['rt_id'])->first();
                if (!$roomType) {
                    throw new \Exception('Room type not found: ' . $bookingData['rt_id']);
                }

                // 3) booking_details
                $detailsInserted = DB::table('booking_details')->insert([
                    'b_id'            => $bookingId,
                    'rt_id'           => $bookingData['rt_id'],
                    'description'     => $roomType->type_name . ' - ' . ($roomType->description ?? ''),
                    'guests'          => $bookingData['guests'],
                    'discount_code'   => $bookingData['discount_code'] ?? null,
                    'discount_amount' => (int)($bookingData['discount_amount'] ?? 0),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
                if (!$detailsInserted) {
                    throw new \Exception('Failed to insert booking details record');
                }

                // 4) booking_services_details (nếu có)
                $services = $bookingData['services'] ?? []; // đã chuẩn hoá ở bước tạo URL VNPay
                if (!empty($services)) {
                    $rows = [];
                    $ts = now();
                    foreach ($services as $s) {
                        $rows[] = [
                            'b_id'          => $bookingId,
                            's_id'          => (int)$s['s_id'],
                            'quantity'      => (int)$s['quantity'],
                            'unit_price'    => (int)$s['unit_price'],
                            'pricing_model' => (int)($s['pricing_model'] ?? 0),
                            'scheduled_at'  => null,
                            'note'          => null,
                            'created_at'    => $ts,
                            'updated_at'    => $ts,
                        ];
                    }
                    DB::table('booking_services_details')->insert($rows);
                }

                // 5) payment
                $paymentInserted = DB::table('payments')->insert([
                    'b_id'              => $bookingId,
                    'payment_method'    => 'vnpay',
                    'amount'            => (int)$bookingData['total_price'],
                    'status'            => 1, // Thành công
                    'transaction_id'    => $request->vnp_TransactionNo ?? null,
                    'vnp_response_code' => $request->vnp_ResponseCode,
                    'created_at'        => now(),
                    'updated_at'        => now()
                ]);
                if (!$paymentInserted) {
                    throw new \Exception('Failed to insert payment record');
                }

                DB::commit();

                Log::info('VNPay booking created successfully:', ['booking_id' => $bookingId]);

                // Lưu gọn info cho trang success
                session([
                    'last_booking' => [
                        'b_id'               => $bookingId,
                        'rt_id'              => $bookingData['rt_id'],
                        'guests'             => $bookingData['guests'],
                        'discount_code'      => $bookingData['discount_code'] ?? null,
                        'discount_amount'    => (int)($bookingData['discount_amount'] ?? 0),
                        'services_total'     => (int)($bookingData['services_total'] ?? 0),
                        'payment_method'     => 'vnpay',
                        'vnp_transaction_id' => $request->vnp_TransactionNo ?? null
                    ]
                ]);

                // Dọn session
                session()->forget('booking_data');

                return redirect()->route('booking.success', ['id' => $bookingId])
                    ->with('success', 'Thanh toán thành công! Đặt loại phòng của bạn đã được xác nhận.');
            }

            // Thanh toán thất bại
            Log::warning('VNPay payment failed:', [
                'response_code' => $request->vnp_ResponseCode,
                'booking_data'  => $bookingData
            ]);
            session()->forget('booking_data');

            // Quay về trang payment (nên kèm params tối thiểu)
            return redirect()
                ->route('payment', [
                    'rt_id'    => $bookingData['rt_id'] ?? null,
                    'checkin'  => $bookingData['check_in_date'] ?? null,
                    'checkout' => $bookingData['check_out_date'] ?? null,
                    'guests'   => $bookingData['guests'] ?? null,
                ])
                ->with('error', 'Thanh toán không thành công. Mã lỗi: ' . ($request->vnp_ResponseCode ?? 'UNKNOWN'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay return error: ' . $e->getMessage(), [
                'trace'        => $e->getTraceAsString(),
                'request'      => $request->all(),
                'booking_data' => $bookingData ?? null
            ]);
            return redirect()->route('index')->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán: ' . $e->getMessage());
        }
    }


    public function bookingSuccess($id)
    {
        try {
            // Lấy booking
            $booking = DB::table('booking')->where('b_id', $id)->first();
            if (!$booking) {
                return redirect()->route('index')->with('error', 'Không tìm thấy thông tin đặt phòng!');
            }

            // Quyền truy cập
            if ($booking->u_id != Auth::id()) {
                return redirect()->route('index')->with('error', 'Bạn không có quyền xem thông tin này!');
            }

            // User
            $user = DB::table('users')->where('id', $booking->u_id)->first();

            // Lấy booking_details + room type + voucher (nếu có)
            $roomType = null;
            $details = null;
            $voucher = null;
            $bookingDetails = DB::table('booking_details')->where('b_id', $id)->first();

            if ($bookingDetails && $bookingDetails->rt_id) {
                $roomType = DB::table('room_types')->where('rt_id', $bookingDetails->rt_id)->first();
                $details  = $bookingDetails;

                if (!empty($bookingDetails->discount_code)) {
                    $voucher = DB::table('vouchers')->where('v_code', $bookingDetails->discount_code)->first();
                }
            } else {
                // Fallback session (hiếm khi cần)
                $bookingData = session('last_booking');
                if ($bookingData && !empty($bookingData['rt_id'])) {
                    $roomType = DB::table('room_types')->where('rt_id', $bookingData['rt_id'])->first();
                    $details  = (object) $bookingData;
                    if (!empty($bookingData['discount_code'])) {
                        $voucher = DB::table('vouchers')->where('v_code', $bookingData['discount_code'])->first();
                    }
                }
            }

            // ====== LẤY VÀ TÍNH DỊCH VỤ KÈM THEO ======
            // Join để lấy tên, đơn giá, ...; sắp xếp mới nhất trước
            $serviceRows = DB::table('booking_services_details as bsd')
                ->join('services as s', 's.s_id', '=', 'bsd.s_id')
                ->where('bsd.b_id', $id)
                ->orderBy('bsd.created_at', 'desc')
                ->get([
                    'bsd.s_id',
                    's.name as service_name',
                    'bsd.quantity',
                    'bsd.unit_price',
                    'bsd.pricing_model',
                    'bsd.scheduled_at',
                    'bsd.note',
                    'bsd.created_at',
                    'bsd.updated_at',
                ]);

            // Chuẩn hoá + tính subtotal từng dòng
            $services = $serviceRows->map(function ($r) {
                $r->unit_price_int   = (int) $r->unit_price;
                $r->quantity_int     = (int) $r->quantity;
                $r->subtotal_int     = $r->unit_price_int * $r->quantity_int;
                $r->unit_price_fmt   = number_format($r->unit_price_int, 0, ',', '.') . ' VND';
                $r->subtotal_fmt     = number_format($r->subtotal_int, 0, ',', '.') . ' VND';
                $r->pricing_label    = match ((int)$r->pricing_model) {
                    1 => 'Theo đêm',
                    2 => 'Theo khách',
                    default => 'Mỗi lần',
                };
                $r->scheduled_fmt = $r->scheduled_at ? date('d/m/Y H:i', strtotime($r->scheduled_at)) : null;
                return $r;
            });

            $services_total = (int) $services->sum('subtotal_int');
            $services_total_fmt = number_format($services_total, 0, ',', '.') . ' VND';

            // ====== PAYMENTS ======
            $payments = DB::table('payments')
                ->where('b_id', $id)
                ->orderByDesc('created_at')
                ->get();

            $methodMap = [
                'vnpay' => 'VNPay',
                'cash'  => 'Tiền mặt',
                'bank'  => 'Chuyển khoản',
                'momo'  => 'MoMo',
            ];

            $payments = $payments->map(function ($p) use ($methodMap) {
                $p->method_label   = $methodMap[$p->payment_method] ?? strtoupper($p->payment_method);
                $p->amount_formatted = number_format((float)$p->amount, 0, ',', '.') . ' VND';
                $p->status_text    = $p->status == 1 ? 'Thành công' : ($p->status == 0 ? 'Chờ xử lý' : 'Thất bại');
                $p->status_badge   = $p->status == 1 ? 'success' : ($p->status == 0 ? 'warning' : 'danger');
                $p->payment_date_formatted = $p->payment_date ? date('d/m/Y H:i', strtotime($p->payment_date)) : null;
                $p->created_at_formatted   = $p->created_at ? date('d/m/Y H:i', strtotime($p->created_at)) : null;
                $p->updated_at_formatted   = $p->updated_at ? date('d/m/Y H:i', strtotime($p->updated_at)) : null;
                return $p;
            });
            $lastPayment = $payments->first();

            $isPaid = ($booking->payment_status ?? 0) == 1 || $payments->contains(fn($p) => (int)$p->status === 1);

            // ====== KHÁC ======
            if ($roomType) {
                $roomType->formatted_price = number_format((int)$roomType->base_price, 0, ',', '.') . ' VND';
            }

            $nights = 1;
            if ($booking->check_in_date && $booking->check_out_date) {
                $nights = (strtotime($booking->check_out_date) - strtotime($booking->check_in_date)) / 86400;
                if ($nights < 1) $nights = 1;
            }

            $booking->formatted_check_in  = $booking->check_in_date  ? date('d/m/Y', strtotime($booking->check_in_date)) : null;
            $booking->formatted_check_out = $booking->check_out_date ? date('d/m/Y', strtotime($booking->check_out_date)) : null;
            $booking->formatted_total_price = number_format((int)$booking->total_price, 0, ',', '.') . ' VND';
            $booking->nights = $nights;

            // Giá phòng (chỉ để show breakdown)
            $room_only_total = ($roomType ? (int)$roomType->base_price : 0) * $nights;
            $room_only_total_fmt = number_format($room_only_total, 0, ',', '.') . ' VND';

            // Giảm giá (nếu có)
            $discount_amount = (int)($details->discount_amount ?? 0);
            $discount_amount_fmt = number_format($discount_amount, 0, ',', '.') . ' VND';

            return view('user.booking-success', compact(
                'booking',
                'user',
                'roomType',
                'details',
                'voucher',
                'payments',
                'lastPayment',
                'isPaid',
                'services',
                'services_total',
                'services_total_fmt',
                'room_only_total',
                'room_only_total_fmt',
                'discount_amount',
                'discount_amount_fmt'
            ));
        } catch (\Exception $e) {
            Log::error('Booking success page error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'b_id'  => $id,
            ]);
            return redirect()->route('index')->with('error', 'Có lỗi xảy ra khi tải thông tin đặt phòng!');
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

    public function create()
    {
        $roomTypes = DB::table('room_types')
            ->where('status', 1)
            ->where('is_delete', 0)
            ->select('rt_id', 'type_name', 'base_price')
            ->orderBy('type_name')
            ->get();

        $services = DB::table('services')
            ->where('is_available', 1)
            ->select('s_id', 'name', 'price', 'unit', 'max_quantity', 'description')
            ->orderBy('name')
            ->get();

        return view('admin.bookings.create', compact('roomTypes', 'services'));
    }
    public function store(Request $request)
    {
        $hasExistingUser = $request->filled('u_id');

        // ===== Validate =====
        $rules = [
            'rt_id'             => ['required', 'integer', 'exists:room_types,rt_id'],
            'checkin'           => ['required', 'date'],
            'checkout'          => ['required', 'date', 'after:checkin'],
            'guests'            => ['required', 'integer', 'min:1'],
            'discount_code'     => ['nullable', 'string', 'max:50'],
            'discount_amount'   => ['nullable', 'numeric', 'min:0'],

            // dịch vụ kèm theo
            'services'                      => ['nullable', 'array'],
            'services.*.s_id'               => ['nullable', 'integer'],
            'services.*.quantity'           => ['nullable', 'integer', 'min:0'],
            'services.*.pricing_model'      => ['nullable', 'integer', 'in:0,1,2'], // 0=once, 1=per-night, 2=per-guest
            // 'payment_method' => ['nullable','in:vnpay,cash,bank,momo'] // nếu cần
        ];

        if ($hasExistingUser) {
            $rules['u_id'] = ['required', 'integer', 'exists:users,id'];
        } else {
            $rules = array_merge($rules, [
                'user.name'       => ['required', 'string', 'max:255'],
                'user.email'      => ['nullable', 'email', 'max:255'],
                'user.phone'      => ['nullable', 'string', 'max:20'],
                'user.address'    => ['nullable', 'string'],
                'user.gender'     => ['nullable', 'in:Nam,Nữ,Khác'],
                'user.dob'        => ['nullable', 'date'],
                'user.citizen_id' => ['required', 'string', 'max:20', 'unique:users,citizen_id'],
            ]);
        }

        $validated = $request->validate($rules);

        // ===== User: lấy hoặc tạo =====
        if ($hasExistingUser) {
            $userId = (int) $validated['u_id'];
        } else {
            $userId = DB::table('users')->insertGetId([
                'name'       => $validated['user']['name'],
                'email'      => $validated['user']['email'] ?? null,
                'phone'      => $validated['user']['phone'] ?? null,
                'address'    => $validated['user']['address'] ?? null,
                'gender'     => $validated['user']['gender'] ?? null,
                'dob'        => $validated['user']['dob'] ?? null,
                'citizen_id' => $validated['user']['citizen_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ===== Room type & nights =====
        $roomType = DB::table('room_types')
            ->select('rt_id', 'type_name', 'description', 'base_price')
            ->where('rt_id', $validated['rt_id'])
            ->firstOrFail();

        $ci = Carbon::parse($validated['checkin'])->startOfDay();
        $co = Carbon::parse($validated['checkout'])->startOfDay();
        $nights = max(1, $ci->diffInDays($co));

        // ===== Tiền phòng =====
        $basePrice = (int) $roomType->base_price;
        $roomCost  = $basePrice * $nights;

        // ===== Chuẩn hoá dịch vụ + tính tiền dịch vụ (giá lấy từ DB) =====
        $servicesInput = $request->input('services', []);
        $normalizedServices = [];  // để insert vào booking_services_details
        $servicesCost = 0;

        if (is_array($servicesInput) && count($servicesInput)) {
            // gom s_id hợp lệ
            $ids = [];
            foreach ($servicesInput as $key => $svc) {
                $sid = (int) ($svc['s_id'] ?? $key);
                if ($sid > 0) $ids[] = $sid;
            }
            $ids = array_values(array_unique($ids));

            if (!empty($ids)) {
                $serviceRows = DB::table('services')
                    ->whereIn('s_id', $ids)
                    ->where('is_available', 1)
                    ->select('s_id', 'price')
                    ->get()
                    ->keyBy('s_id');

                foreach ($servicesInput as $key => $svc) {
                    $sid = (int) ($svc['s_id'] ?? $key);
                    $qty = max(0, (int) ($svc['quantity'] ?? 0));
                    $pm  = (int) ($svc['pricing_model'] ?? 0); // 0=once, 1=per-night, 2=per-guest

                    if ($sid <= 0 || $qty <= 0) continue;
                    if (!isset($serviceRows[$sid])) continue;

                    $unit = (int) $serviceRows[$sid]->price;
                    if ($unit <= 0) continue;

                    // hệ số theo pricing model
                    $factor = 1;
                    if ($pm === 1) $factor = $nights;                       // theo đêm
                    elseif ($pm === 2) $factor = (int) $validated['guests']; // theo khách

                    $servicesCost += ($unit * $qty * $factor);

                    $normalizedServices[$sid] = [
                        's_id'          => $sid,
                        'quantity'      => $qty,
                        'unit_price'    => $unit,
                        'pricing_model' => $pm,
                    ];
                }
            }
        }

        // ===== Tổng tiền =====
        $discount = max(0, (int) ($validated['discount_amount'] ?? 0));
        $amount   = max(0, $roomCost + $servicesCost - $discount);

        if ($amount <= 0) {
            return back()
                ->withErrors(['discount_amount' => 'Số tiền giảm không hợp lệ so với tổng chi phí.'])
                ->withInput();
        }

        // ===== Ghi DB trong transaction =====
        $bookingId = $this->generateBookingId();
        $paymentMethod = in_array($request->input('payment_method'), ['vnpay', 'cash', 'bank', 'momo'])
            ? $request->input('payment_method')
            : 'cash';

        try {
            DB::beginTransaction();

            // 1) booking
            DB::table('booking')->insert([
                'b_id'           => $bookingId,
                'u_id'           => $userId,
                'check_in_date'  => $validated['checkin'],
                'check_out_date' => $validated['checkout'],
                'status'         => 1, // Pending
                'payment_status' => 0, // Chưa thanh toán
                'total_price'    => $amount,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 2) booking_details
            DB::table('booking_details')->insert([
                'b_id'            => $bookingId,
                'r_id'            => null,
                'rt_id'           => (int) $validated['rt_id'],
                'r_id_assigned'   => null,
                'description'     => $roomType->type_name . ' - ' . ($roomType->description ?? ''),
                'guests'          => (int) $validated['guests'],
                'discount_code'   => $validated['discount_code'] ?? null,
                'discount_amount' => $discount,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 3) booking_services_details (nếu có)
            if (!empty($normalizedServices)) {
                $rows = [];
                $ts = now();
                foreach ($normalizedServices as $s) {
                    $rows[] = [
                        'b_id'          => $bookingId,
                        's_id'          => $s['s_id'],
                        'quantity'      => $s['quantity'],
                        'unit_price'    => $s['unit_price'],
                        'pricing_model' => $s['pricing_model'],
                        'scheduled_at'  => null,
                        'note'          => null,
                        'created_at'    => $ts,
                        'updated_at'    => $ts,
                    ];
                }
                DB::table('booking_services_details')->insert($rows);
            }

            // 4) payments (pending)
            DB::table('payments')->insert([
                'b_id'            => $bookingId,
                'payment_method'  => $paymentMethod, // 'cash' mặc định
                'amount'          => $amount,
                'status'          => 0, // Pending
                'transaction_id'  => strtoupper($paymentMethod) . '_' . $bookingId,
                'vnp_response_code' => null,
                'payment_date'    => null,
                'notes'           => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::commit();

            // Redirect về trang quản lý đặt phòng (ADMIN)
            return redirect()
                ->route('admin.bookings.management')
                ->with('success', 'Đặt phòng thành công. Mã booking: ' . $bookingId);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin store booking failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['system' => 'Có lỗi khi tạo đặt phòng. Vui lòng thử lại.'])->withInput();
        }
    }

    /** Tạo b_id 8 ký tự duy nhất */
    private function generateBookingId(): string
    {
        do {
            $id = strtoupper(Str::random(8));
            $exists = DB::table('booking')->where('b_id', $id)->exists();
        } while ($exists);
        return $id;
    }

    // Lấy thống kê booking cho user


    public function bookingDetail($id)
    {
        $booking = DB::table('booking')
            ->join('booking_details', 'booking.b_id', '=', 'booking_details.b_id')
            ->join('rooms', 'booking_details.r_id', '=', 'rooms.r_id')
            ->select(
                'booking.*',
                'booking_details.description as booking_description',
                'rooms.name as room_name',
                'rooms.price_per_night',
                'rooms.max_guests',
                'rooms.number_beds',
                'rooms.images',
                'rooms.description as room_description'
            )
            ->where('booking.b_id', $id)
            ->first();

        if (!$booking) {
            return redirect()->route('booking.history', ['userId' => auth()->id()])
                ->with('error', 'Không tìm thấy đơn đặt phòng');
        }

        // Kiểm tra quyền xem (chỉ user tạo đơn mới được xem)
        if ($booking->u_id != auth()->id()) {
            return redirect()->route('booking.history', ['userId' => auth()->id()])
                ->with('error', 'Bạn không có quyền xem đơn đặt phòng này');
        }

        return view('user.booking_detail', compact('booking'));
    }

    /**
     * User xác nhận đã nhận phòng copysave
     */
    public function confirmCheckin($bookingId)
    {
        try {
            $booking = DB::table('booking as b')
                ->join('booking_details as bd', 'b.b_id', '=', 'bd.b_id')
                ->join('rooms as r', 'bd.r_id_assigned', '=', 'r.r_id')
                ->select('b.*', 'bd.r_id_assigned', 'r.name as room_name')
                ->where('b.b_id', $bookingId)
                ->first();

            if (!$booking) {
                return back()->with('error', 'Không tìm thấy đơn đặt phòng');
            }

            if ($booking->status != 2) {
                return back()->with('error', 'Đơn đặt phòng không trong trạng thái chờ nhận phòng');
            }

            if (!$booking->r_id_assigned) {
                return back()->with('error', 'Chưa có phòng được chọn cho đơn đặt phòng này');
            }

            DB::beginTransaction();

            try {
                // Update booking status to "checked in - waiting for checkout"
                DB::table('booking')
                    ->where('b_id', $bookingId)
                    ->update([
                        'status' => 3, // Checked in, waiting for checkout
                        'updated_at' => now()
                    ]);

                // Mark room as occupied/unavailable
                DB::table('rooms')
                    ->where('r_id', $booking->r_id_assigned)
                    ->update(['available' => 0]);

                DB::commit();

                return back()->with('success', "Đã xác nhận khách hàng nhận phòng {$booking->room_name} thành công. Đơn đặt phòng #{$bookingId} chuyển sang trạng thái chờ trả phòng.");
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi khi xác nhận nhận phòng: ' . $e->getMessage());
        }
    }

    /**
     * Admin quản lý đơn đặt phòng
     */

    /**
     * Updated Booking Management Controller
     * Handles booking management with status filter and room assignment
     */
    public function bookingManagement(Request $request)
    {
        try {
            // Subquery: lấy payment_id mới nhất cho mỗi booking
            $latestPaymentIdsSub = DB::table('payments')
                ->select('b_id', DB::raw('MAX(payment_id) as latest_payment_id'))
                ->groupBy('b_id');

            // Lấy toàn bộ bookings + thông tin phòng, loại phòng, user, phòng đã gán
            // + thông tin payment mới nhất (nếu có)
            $allBookings = DB::table('booking as b')
                ->join('users as u', 'b.u_id', '=', 'u.id')
                ->join('booking_details as bd', 'b.b_id', '=', 'bd.b_id')
                ->join('room_types as rt', 'bd.rt_id', '=', 'rt.rt_id')
                ->leftJoin('rooms as r_assigned', 'bd.r_id_assigned', '=', 'r_assigned.r_id')
                // join subquery payment mới nhất
                ->leftJoinSub($latestPaymentIdsSub, 'lp', function ($join) {
                    $join->on('lp.b_id', '=', 'b.b_id');
                })
                ->leftJoin('payments as p', 'p.payment_id', '=', 'lp.latest_payment_id')
                ->select(
                    // booking
                    'b.b_id',
                    'b.u_id',
                    'b.check_in_date',
                    'b.check_out_date',
                    'b.status',
                    'b.total_price',
                    'b.created_at',
                    'b.updated_at',

                    // user
                    'u.name as user_name',
                    'u.email as user_email',

                    // room type
                    'rt.rt_id',
                    'rt.type_name as room_type_name',
                    'rt.images as room_type_images',
                    'rt.base_price',
                    'rt.max_guests',
                    'rt.number_beds',
                    'rt.room_size',
                    'rt.description as room_type_description',

                    // booking details
                    'bd.description as booking_description',
                    'bd.r_id_assigned',
                    'r_assigned.name as assigned_room_name',

                    // payment (mới nhất)
                    DB::raw('COALESCE(p.status, 0) as payment_status'), // mặc định 0 nếu chưa có payment
                    'p.payment_method',
                    'p.amount as payment_amount',
                    'p.payment_date'
                )
                ->orderBy('b.created_at', 'desc')
                ->get();

            // Đếm theo trạng thái để hiển thị trên thanh filter
            $statusCounts = [];
            foreach ($allBookings as $booking) {
                $statusKey = ($booking->status == -1) ? 'cancelled' : $booking->status;
                if (!isset($statusCounts[$statusKey])) {
                    $statusCounts[$statusKey] = 0;
                }
                $statusCounts[$statusKey]++;
            }

            // Lọc theo trạng thái nếu có yêu cầu
            $statusFilter = $request->get('status');
            if ($statusFilter !== null && $statusFilter !== '') {
                if ($statusFilter === 'cancelled') {
                    $filteredBookings = $allBookings->where('status', -1);
                } else {
                    $filteredBookings = $allBookings->where('status', (int) $statusFilter);
                }
            } else {
                $filteredBookings = $allBookings;
            }

            // Chuẩn hoá dữ liệu hiển thị
            $transformedBookings = collect();
            foreach ($filteredBookings as $booking) {
                // Ảnh loại phòng
                if (!empty($booking->room_type_images)) {
                    $images = explode(',', $booking->room_type_images);
                    $booking->room_type_image_url = asset('storage/' . trim($images[0]));
                } else {
                    $booking->room_type_image_url = asset('images/no-image.jpg');
                }

                // Giá tiền
                $booking->formatted_total_price = number_format($booking->total_price, 0, ',', '.') . ' VND';
                $booking->formatted_base_price  = number_format($booking->base_price, 0, ',', '.') . ' VND';

                // Ngày tháng
                $booking->formatted_check_in   = date('d/m/Y', strtotime($booking->check_in_date));
                $booking->formatted_check_out  = date('d/m/Y', strtotime($booking->check_out_date));
                $booking->formatted_created_at = date('d/m/Y H:i', strtotime($booking->created_at));

                // Số đêm
                $checkin  = new DateTime($booking->check_in_date);
                $checkout = new DateTime($booking->check_out_date);
                $booking->nights = $checkin->diff($checkout)->days;

                // Bảo đảm payment_status không null (0 = chưa thanh toán)
                if ($booking->payment_status === null) {
                    $booking->payment_status = 0;
                }

                $transformedBookings->push($booking);
            }

            // Nhãn trạng thái hiển thị
            $statusLabels = [
                '0' => 'Chờ thanh toán',
                '1' => 'Chờ xử lý',
                '2' => 'Chờ nhận phòng',
                '3' => 'Chờ trả phòng',
                '4' => 'Hoàn tất',
                'cancelled' => 'Đã hủy',
            ];

            return view('admin.bookings.management', [
                'bookings'         => $allBookings,         // dùng cho "Tất cả (count)"
                'filteredBookings' => $transformedBookings, // dữ liệu sau khi lọc + format
                'statusCounts'     => $statusCounts,        // hiển thị badge đếm
                'statusLabels'     => $statusLabels,        // nhãn trạng thái
            ]);
        } catch (Exception $e) {
            return back()->with('error', 'Không thể tải danh sách đơn đặt phòng: ' . $e->getMessage());
        }
    }


    public function updatePaymentStatus(Request $request)
    {
        $data = $request->validate([
            'b_id'           => 'required|string|max:8',
            'payment_status' => 'required|in:0,1', // 0: chưa thanh toán, 1: đã thanh toán
        ]);

        try {
            DB::beginTransaction();

            // Lấy payment_id mới nhất cho booking này
            $latestPaymentId = DB::table('payments')
                ->where('b_id', $data['b_id'])
                ->max('payment_id');

            if (!$latestPaymentId) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy payment của booking này.',
                ], 404);
            }

            // Cập nhật đúng bản ghi mới nhất
            $affected = DB::table('payments')
                ->where('payment_id', $latestPaymentId)
                ->update([
                    'status'     => (int) $data['payment_status'],
                    'updated_at' => now(),
                ]);

            DB::commit();

            if ($affected > 0) {
                return response()->json([
                    'success'     => true,
                    'message'     => 'Cập nhật trạng thái thanh toán thành công!',
                    'payment_id'  => $latestPaymentId,
                    'new_status'  => (int) $data['payment_status'],
                ]);
            }

            // Không có dòng nào thay đổi (thường do set cùng giá trị cũ)
            return response()->json([
                'success' => false,
                'message' => 'Trạng thái không thay đổi (giá trị đã đúng).',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * View booking details with available rooms for assignment
     */
    public function viewBooking($id)
    {
        try {
            // Subquery: lấy payment_id mới nhất theo b_id
            $latestPaymentIdsSub = DB::table('payments')
                ->select('b_id', DB::raw('MAX(payment_id) as latest_payment_id'))
                ->groupBy('b_id');

            // Query chi tiết booking + join sang payments (mới nhất)
            $booking = DB::table('booking as b')
                ->join('users as u', 'b.u_id', '=', 'u.id')
                ->join('booking_details as bd', 'b.b_id', '=', 'bd.b_id')
                ->join('room_types as rt', 'bd.rt_id', '=', 'rt.rt_id')
                ->leftJoin('rooms as r_assigned', 'bd.r_id_assigned', '=', 'r_assigned.r_id')
                ->leftJoinSub($latestPaymentIdsSub, 'lp', function ($join) {
                    $join->on('lp.b_id', '=', 'b.b_id');
                })
                ->leftJoin('payments as p', 'p.payment_id', '=', 'lp.latest_payment_id')
                ->select(
                    // booking
                    'b.b_id',
                    'b.u_id',
                    'b.check_in_date',
                    'b.check_out_date',
                    'b.status',
                    'b.total_price',
                    'b.created_at',
                    'b.updated_at',

                    // user
                    'u.name as user_name',
                    'u.email as user_email',

                    // room type
                    'rt.rt_id',
                    'rt.type_name as room_type_name',
                    'rt.images as room_type_images',
                    'rt.base_price',
                    'rt.max_guests',
                    'rt.number_beds',
                    'rt.room_size',
                    'rt.description as room_type_description',
                    'rt.amenities',

                    // booking details
                    'bd.description as booking_description',
                    'bd.r_id_assigned',
                    'r_assigned.name as assigned_room_name',

                    // payment (mới nhất)
                    DB::raw('COALESCE(p.status, 0) as payment_status'),
                    'p.payment_method',
                    'p.amount as payment_amount',
                    'p.payment_date',
                    'p.payment_id'
                )
                ->where('b.b_id', $id)
                ->first();

            if (!$booking) {
                return redirect()->route('admin.bookings.management')
                    ->with('error', 'Không tìm thấy đơn đặt phòng');
            }

            // ===== DỊCH VỤ KÈM THEO =====
            $rawServices = DB::table('booking_services_details as bs')
                ->join('services as s', 'bs.s_id', '=', 's.s_id')
                ->where('bs.b_id', $id)
                ->select(
                    'bs.bs_id',
                    'bs.b_id',
                    'bs.s_id',
                    'bs.quantity',
                    'bs.unit_price',
                    'bs.pricing_model',
                    'bs.scheduled_at',
                    'bs.note',
                    'bs.created_at',
                    's.name as service_name',
                    's.unit',
                    's.service_time',
                    's.location',
                    's.image'
                )
                ->orderBy('bs.created_at')
                ->get();

            $pricingMap = [
                0 => 'Tính theo lần',
                1 => 'Tính theo đêm',
                2 => 'Tính theo khách',
            ];

            $services = $rawServices->map(function ($row) use ($pricingMap) {
                $row->quantity_int     = (int)($row->quantity ?? 0);
                $row->unit_price_int   = (int)($row->unit_price ?? 0);
                $row->subtotal_int     = $row->quantity_int * $row->unit_price_int;

                $row->unit_price_fmt   = number_format($row->unit_price_int, 0, ',', '.') . ' VND';
                $row->subtotal_fmt     = number_format($row->subtotal_int, 0, ',', '.') . ' VND';
                $row->pricing_label    = $pricingMap[(int)($row->pricing_model ?? 0)] ?? 'Theo dịch vụ';

                $row->scheduled_fmt    = $row->scheduled_at
                    ? \Carbon\Carbon::parse($row->scheduled_at)->format('d/m/Y H:i')
                    : null;

                // Ảnh dịch vụ (nếu có)
                $row->image_url        = !empty($row->image) ? asset($row->image) : null;

                return $row;
            });

            $services_total = (int)$services->sum('subtotal_int');
            $services_total_fmt = number_format($services_total, 0, ',', '.') . ' VND';

            // Room images
            if (!empty($booking->room_type_images)) {
                $images = array_filter(array_map('trim', explode(',', $booking->room_type_images)));
                $booking->room_type_image_urls = array_map(fn($img) => asset('storage/' . $img), $images);
            } else {
                $booking->room_type_image_urls = [];
            }

            // Room-only subtotal (ước lượng): base_price * nights
            $nights = \Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date);
            if ($nights < 1) $nights = 1;
            $room_only_total = (int)($booking->base_price) * $nights;
            $room_only_total_fmt = number_format($room_only_total, 0, ',', '.') . ' VND';

            return view('admin.bookings.view', [
                'booking'             => $booking,
                'availableRooms'      => (function () use ($booking) {
                    $list = [];
                    if ((int)$booking->status === 1) {
                        $list = DB::table('rooms as r')
                            ->where('r.rt_id', $booking->rt_id)
                            ->where('r.available', 1)
                            ->where('r.status', 1)
                            ->whereNotExists(function ($query) use ($booking) {
                                $query->select(DB::raw(1))
                                    ->from('booking_details as bd2')
                                    ->join('booking as b2', 'bd2.b_id', '=', 'b2.b_id')
                                    ->whereRaw('bd2.r_id_assigned = r.r_id')
                                    ->where(function ($q) use ($booking) {
                                        $q->where('b2.check_in_date', '<', $booking->check_out_date)
                                            ->where('b2.check_out_date', '>', $booking->check_in_date);
                                    })
                                    ->whereIn('b2.status', [1, 2, 3]);
                            })
                            ->select('r.r_id', 'r.name', 'r.description', 'r.price_per_night')
                            ->orderBy('r.name')
                            ->get();
                    }
                    return $list;
                })(),
                // DỊCH VỤ:
                'services'            => $services,
                'services_total'      => $services_total,
                'services_total_fmt'  => $services_total_fmt,
                'room_only_total_fmt' => $room_only_total_fmt,
            ]);
        } catch (Exception $e) {
            return back()->with('error', 'Không thể tải chi tiết đơn đặt phòng: ' . $e->getMessage());
        }
    }


    /**
     * Assign room to booking
     */
    public function assignRoom(Request $request, $bookingId)
    {
        try {
            $request->validate([
                'room_id' => 'required|integer|exists:rooms,r_id'
            ]);

            // Check if booking exists and is in correct status
            $booking = DB::table('booking')->where('b_id', $bookingId)->first();
            if (!$booking) {
                return back()->with('error', 'Không tìm thấy đơn đặt phòng');
            }

            if ($booking->status != 1) {
                return back()->with('error', 'Chỉ có thể chọn phòng cho đơn đặt phòng đang chờ xử lý');
            }

            // Check if room is available for the booking dates
            $roomId = $request->room_id;
            $conflictBooking = DB::table('booking_details as bd')
                ->join('booking as b', 'bd.b_id', '=', 'b.b_id')
                ->where('bd.r_id_assigned', $roomId)
                ->where('b.check_in_date', '<', $booking->check_out_date)
                ->where('b.check_out_date', '>', $booking->check_in_date)
                ->whereIn('b.status', [1, 2, 3])
                ->where('b.b_id', '!=', $bookingId)
                ->exists();

            if ($conflictBooking) {
                return back()->with('error', 'Phòng này đã được đặt trong khoảng thời gian này');
            }

            // Update booking_details with assigned room
            DB::table('booking_details')
                ->where('b_id', $bookingId)
                ->update([
                    'r_id_assigned' => $roomId,
                    'updated_at' => now()
                ]);

            $roomName = DB::table('rooms')->where('r_id', $roomId)->value('name');

            return back()->with('success', "Đã chọn phòng {$roomName} cho đơn đặt phòng #{$bookingId}. Bấm 'Xác nhận phòng' để thông báo cho khách hàng.");
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi khi chọn phòng: ' . $e->getMessage());
        }
    }

    /**
     * Confirm room assignment and notify customer
     */
    public function confirmRoomAssignment($bookingId)
    {
        try {
            // Check if booking exists and has assigned room
            $booking = DB::table('booking as b')
                ->join('booking_details as bd', 'b.b_id', '=', 'bd.b_id')
                ->join('rooms as r', 'bd.r_id_assigned', '=', 'r.r_id')
                ->select('b.*', 'bd.r_id_assigned', 'r.name as room_name')
                ->where('b.b_id', $bookingId)
                ->first();

            if (!$booking) {
                return back()->with('error', 'Không tìm thấy đơn đặt phòng');
            }

            if ($booking->status != 1) {
                return back()->with('error', 'Đơn đặt phòng không trong trạng thái chờ xử lý');
            }

            if (!$booking->r_id_assigned) {
                return back()->with('error', 'Chưa chọn phòng cho đơn đặt phòng này');
            }

            // Update booking status to "confirmed - waiting for check-in"
            DB::table('booking')
                ->where('b_id', $bookingId)
                ->update([
                    'status' => 2, // Confirmed, waiting for check-in
                    'updated_at' => now()
                ]);

            // Here you can add email notification logic
            // $this->sendRoomConfirmationEmail($booking);

            return back()->with('success', "Đã xác nhận phòng {$booking->room_name} cho đơn đặt phòng #{$bookingId}. Khách hàng có thể nhận phòng từ ngày " . date('d/m/Y', strtotime($booking->check_in_date)));
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi khi xác nhận phòng: ' . $e->getMessage());
        }
    }

    /**
     * Confirm checkout - customer returned room
     */
    public function confirmCheckout($bookingId)
    {
        try {
            $booking = DB::table('booking as b')
                ->join('booking_details as bd', 'b.b_id', '=', 'bd.b_id')
                ->select('b.*', 'bd.r_id_assigned')
                ->where('b.b_id', $bookingId)
                ->first();

            if (!$booking) {
                return back()->with('error', 'Không tìm thấy đơn đặt phòng');
            }

            if ($booking->status != 3) {
                return back()->with('error', 'Đơn đặt phòng không trong trạng thái chờ trả phòng');
            }

            DB::beginTransaction();

            try {
                // Update booking status to completed
                DB::table('booking')
                    ->where('b_id', $bookingId)
                    ->update([
                        'status' => 4, // Completed
                        'updated_at' => now()
                    ]);

                // Mark room as available again
                if ($booking->r_id_assigned) {
                    DB::table('rooms')
                        ->where('r_id', $booking->r_id_assigned)
                        ->update(['available' => 1]);
                }

                DB::commit();

                return back()->with('success', "Đã xác nhận khách hàng trả phòng thành công. Đơn đặt phòng #{$bookingId} đã hoàn tất.");
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi khi xác nhận trả phòng: ' . $e->getMessage());
        }
    }

    // chức năng hủy đơn đặt phòng
    public function refund(Request $request, $id)
    {
        // Có thể truyền 'refund_amount' (VND) và 'note' từ form; nếu không truyền thì hoàn full phần còn lại
        $validator = \Validator::make($request->all(), [
            'refund_amount' => ['nullable', 'numeric', 'min:1'],
            'note'          => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Dữ liệu không hợp lệ.');
        }

        try {
            DB::beginTransaction();

            // 1) Lấy booking
            $booking = DB::table('booking')->where('b_id', $id)->first();
            if (!$booking) {
                DB::rollBack();
                return back()->with('error', 'Không tìm thấy đơn đặt phòng.');
            }

            // 2) Tính số tiền đã thanh toán & đã hoàn trước đó
            //    - status = 1: thanh toán thành công (tiền dương)
            //    - status = 2: đã hoàn (tiền âm)
            $paid = (int) DB::table('payments')
                ->where('b_id', $id)
                ->where('status', 1)
                ->sum('amount');

            $refundedAbs = (int) abs(DB::table('payments')
                ->where('b_id', $id)
                ->where('status', 2)
                ->sum('amount')); // amount âm, nên lấy abs tổng

            $refundable = max(0, $paid - $refundedAbs);

            if ($refundable <= 0) {
                DB::rollBack();
                return back()->with('error', 'Đơn này chưa thanh toán hoặc đã được hoàn đủ.');
            }

            // 3) Xác định số tiền cần hoàn (mặc định hoàn toàn phần còn lại)
            $amountToRefund = (int) $request->input('refund_amount', $refundable);
            if ($amountToRefund > $refundable) {
                DB::rollBack();
                return back()->with('error', 'Số tiền hoàn vượt quá số tiền có thể hoàn (' . number_format($refundable, 0, ',', '.') . ' VND).');
            }

            // 4) Lấy giao dịch đã thanh toán gần nhất (nếu cần tham chiếu method/txn)
            $latestPaid = DB::table('payments')
                ->where('b_id', $id)
                ->where('status', 1)
                ->orderByDesc('created_at')
                ->first();

            if (!$latestPaid) {
                DB::rollBack();
                return back()->with('error', 'Không tìm thấy giao dịch đã thanh toán hợp lệ để hoàn tiền.');
            }

            $note = trim((string) $request->input('note', ''));
            $refundNotes = 'Refund: ' . ($note !== '' ? $note : 'admin thực hiện hoàn tiền');

            // 5) Ghi bản ghi hoàn tiền (âm tiền) vào payments
            DB::table('payments')->insert([
                'b_id'              => $id,
                'payment_method'    => $latestPaid->payment_method, // vnpay | cash | bank | momo
                'amount'            => -abs($amountToRefund),       // số âm
                'status'            => 2,                           // 2 = refunded
                'transaction_id'    => ($latestPaid->transaction_id ? ($latestPaid->transaction_id . '-REFUND-' . now()->format('YmdHis')) : 'REFUND-' . now()->format('YmdHis')),
                'vnp_response_code' => null,
                'payment_date'      => now(),
                'notes'             => $refundNotes,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // 6) Cập nhật trạng thái payment_status của booking:
            //    nếu sau khi hoàn mà tổng (paid - refunded) <= 0 => 0 (chưa thanh toán), ngược lại giữ 1
            $remainAfterRefund = $refundable - $amountToRefund; // phần còn lại sau khi hoàn lần này
            $newPaymentStatus  = $remainAfterRefund <= 0 ? 0 : 1;

            DB::table('booking')->where('b_id', $id)->update([
                'payment_status' => $newPaymentStatus,
                'updated_at'     => now(),
            ]);

            DB::commit();

            return back()->with(
                'success',
                'Hoàn tiền thành công: ' . number_format($amountToRefund, 0, ',', '.') . ' VND.'
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Refund error: ' . $e->getMessage(), ['b_id' => $id, 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Không thể hoàn tiền: ' . $e->getMessage());
        }
    }



    public function cancelForm($id)
    {
        $booking = DB::table('booking')->where('b_id', $id)->first();
        if (!$booking) {
            return redirect()->route('admin.bookings.management')
                ->with('error', 'Không tìm thấy đơn đặt phòng');
        }

        // Không cho hủy nếu đã thanh toán
        if ((int)$booking->payment_status === 1) {
            return redirect()->route('admin.bookings.view', $id)
                ->with('error', 'Đơn đã thanh toán. Vui lòng hoàn tiền trước khi hủy.');
        }

        // Chỉ cho hủy khi đơn chưa hoàn tất/hủy/đã nhận phòng
        if (in_array((int)$booking->status, [3, 4, 5])) {
            return redirect()->route('admin.bookings.view', $id)
                ->with('error', 'Đơn này không thể hủy (đã nhận phòng/hoàn tất/đã hủy).');
        }

        return view('admin.bookings.cancel', compact('booking'));
    }

    public function cancel(Request $request, $id)
    {
        $data = $request->validate([
            'reason' => 'required|string|max:1000',
        ], [
            'reason.required' => 'Vui lòng nhập lý do hủy.',
        ]);

        try {
            DB::beginTransaction();

            $booking = DB::table('booking')->where('b_id', $id)->lockForUpdate()->first();
            if (!$booking) {
                return back()->with('error', 'Không tìm thấy đơn đặt phòng.');
            }

            // Chặn hủy nếu đã thanh toán
            if ((int)$booking->payment_status === 1) {
                DB::rollBack();
                return redirect()->route('admin.bookings.view', $id)
                    ->with('error', 'Đơn đã thanh toán. Vui lòng hoàn tiền trước khi hủy.');
            }

            // Không cho hủy nếu đã nhận phòng/hoàn tất/đã hủy
            if (in_array((int)$booking->status, [3, 4, 5])) {
                DB::rollBack();
                return redirect()->route('admin.bookings.view', $id)
                    ->with('error', 'Đơn này không thể hủy (đã nhận phòng/hoàn tất/đã hủy).');
            }

            // Cập nhật trạng thái đơn sang "đã hủy" (giả sử 5 = cancelled)
            DB::table('booking')->where('b_id', $id)->update([
                'status'     => 5, // cancelled
                'updated_at' => now(),
            ]);

            // Ghi chú vào booking_details (nếu muốn lưu lý do hủy)
            DB::table('booking_details')->where('b_id', $id)->update([
                'description' => DB::raw("CONCAT(COALESCE(description,''), '\n[ADMIN CANCEL] " . addslashes($data['reason']) . "')")
            ]);

            // Option: bỏ gán phòng (nếu có)
            DB::table('booking_details')->where('b_id', $id)->update([
                'r_id_assigned' => null,
            ]);

            DB::commit();
            return redirect()->route('admin.bookings.view', $id)
                ->with('success', 'Hủy đặt phòng thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Cancel booking error: ' . $e->getMessage(), ['b_id' => $id]);
            return back()->with('error', 'Không thể hủy đặt phòng: ' . $e->getMessage());
        }
    }

    /**
     * Get booking statistics for dashboard
     */
    public function getBookingStats()
    {
        try {
            $stats = [
                'total' => DB::table('booking')->count(),
                'pending_payment' => DB::table('booking')->where('status', 0)->count(),
                'waiting_admin' => DB::table('booking')->where('status', 1)->count(),
                'confirmed' => DB::table('booking')->where('status', 2)->count(),
                'checked_in' => DB::table('booking')->where('status', 3)->count(),
                'completed' => DB::table('booking')->where('status', 4)->count(),
                'cancelled' => DB::table('booking')->where('status', -1)->count(),
            ];

            $stats['active'] = $stats['confirmed'] + $stats['checked_in'];
            $stats['revenue_today'] = DB::table('booking')
                ->where('status', 4)
                ->whereDate('updated_at', today())
                ->sum('total_price');

            return $stats;
        } catch (Exception $e) {
            return [
                'total' => 0,
                'pending_payment' => 0,
                'waiting_admin' => 0,
                'confirmed' => 0,
                'checked_in' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'active' => 0,
                'revenue_today' => 0
            ];
        }
    }


    public function checkUser(Request $request)
    {
        $data = $request->validate([
            'citizen_id' => ['required', 'string', 'max:20'],
        ]);

        $user = User::query()
            ->select(['id', 'name', 'email', 'phone', 'address', 'gender', 'dob', 'citizen_id', 'issue_date', 'issue_place'])
            ->where('citizen_id', $data['citizen_id'])
            ->first();

        if (!$user) {
            return back()->with('check_result', [
                'status' => 'not_found',
                'citizen_id' => $data['citizen_id'],
            ]);
        }

        // Trả về 1 mảng gọn để tránh serialize model nặng
        return back()->with('check_result', [
            'status' => 'found',
            'user' => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'phone'       => $user->phone,
                'address'     => $user->address,
                'gender'      => $user->gender,
                'dob'         => optional($user->dob)->format('Y-m-d'),
                'citizen_id'  => $user->citizen_id,
                'issue_date'  => optional($user->issue_date)->format('Y-m-d'),
                'issue_place' => $user->issue_place,
            ],
        ]);
    }
}
