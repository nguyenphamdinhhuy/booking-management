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
        // Validate dữ liệu - Sửa validation cho 1 ảnh
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'nullable|integer|min:1|max:10',
            'number_beds' => 'nullable|integer|min:1|max:10',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // Đổi thành image (không phải images)
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

            // Xử lý upload 1 hình ảnh vào public/uploads/rooms
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

            // Lưu vào DB - sửa theo cấu trúc bảng với 1 ảnh
            DB::table('rooms')->insert([
                'name' => $validatedData['name'],
                'price_per_night' => $validatedData['price_per_night'],
                'max_guests' => $validatedData['max_guests'] ?? null,
                'number_beds' => $validatedData['number_beds'] ?? null,
                'images' => $imagePath, // Lưu đường dẫn ảnh đơn (không phải JSON)
                'status' => $validatedData['status'],
                'description' => $validatedData['description'] ?? null,
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

            // Tìm kiếm theo từ khóa (tên phòng hoặc mô tả)
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
                $room->formatted_created_at = date('d/m/Y', strtotime($room->created_at));
            }

            // Nếu là AJAX request (cho tìm kiếm real-time)
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

            // Kiểm tra xem phòng có đang được đặt không (nếu có bảng bookings)
            // Uncomment phần này nếu bạn có bảng bookings

            // $hasActiveBookings = DB::table('booking')
            //     ->where('room_id', $id)
            //     ->where('status', 'confirmed') // hoặc trạng thái tương ứng
            //     ->where('check_out', '>=', now())
            //     ->exists();

            // if ($hasActiveBookings) {
            //     return redirect()->route('admin.rooms.management')
            //         ->with('error', 'Không thể xóa phòng đang có đặt phòng!');
            // }


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

    // Thêm vào rooms_controller.php

    public function edit_room_form($id)
    {
        try {
            // Lấy thông tin phòng theo ID
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
                // Xóa ảnh cũ nếu có ảnh mới và ảnh cũ tồn tại
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

            // Xóa ảnh mới upload nếu có lỗi và ảnh mới khác ảnh cũ
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
}
