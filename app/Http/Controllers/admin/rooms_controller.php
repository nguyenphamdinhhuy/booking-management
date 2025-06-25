<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    function payment() {
        return view('user.payment');
    
    }
}
