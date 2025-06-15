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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'name.required' => 'Tên phòng là bắt buộc',
            'name.max' => 'Tên phòng không được quá 50 ký tự',
            'price_per_night.required' => 'Giá phòng là bắt buộc',
            'price_per_night.numeric' => 'Giá phòng phải là số',
            'price_per_night.min' => 'Giá phòng phải lớn hơn 0',
            'max_guests.integer' => 'Số khách tối đa phải là số nguyên',
            'number_beds.integer' => 'Số giường phải là số nguyên',
            'status.required' => 'Trạng thái là bắt buộc',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'images.*.max' => 'Kích thước hình ảnh không được quá 5MB'
        ]);

        try {
            DB::beginTransaction();

            // Xử lý upload hình ảnh vào public/uploads/rooms
            $imagePaths = [];
            if ($request->hasFile('images')) {
                $destinationPath = public_path('uploads/rooms');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $fileName);
                    $imagePaths[] = 'uploads/rooms/' . $fileName;
                }
            }

            $imagesJson = !empty($imagePaths) ? json_encode($imagePaths) : null;

            // Lưu vào DB - sửa theo cấu trúc bảng thật
            DB::table('rooms')->insert([
                'name' => $validatedData['name'],
                'price_per_night' => $validatedData['price_per_night'],
                'max_guests' => $validatedData['max_guests'] ?? null,
                'number_beds' => $validatedData['number_beds'] ?? null,
                'images' => $imagesJson,
                'status' => $validatedData['status'],
                'description' => $validatedData['description'] ?? null,
                // Bỏ created_at và updated_at vì DB tự động set
            ]);

            DB::commit();

            // Sửa redirect về đúng route
            return redirect()->route('admin.rooms.create')->with('success', 'Thêm phòng thành công!');
        } catch (\Exception $e) {
            DB::rollback();

            // Xóa ảnh đã upload nếu có lỗi
            if (!empty($imagePaths)) {
                foreach ($imagePaths as $path) {
                    $absolutePath = public_path($path);
                    if (file_exists($absolutePath)) {
                        unlink($absolutePath);
                    }
                }
            }

            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function rooms_management()
    {
        try {
            // Lấy tất cả phòng từ database
            $rooms = DB::table('rooms')
                ->select('*')
                ->orderBy('created_at', 'desc')
                ->get();

            // Xử lý dữ liệu images
            foreach ($rooms as $room) {
                $room->images_array = [];
                if ($room->images) {
                    $images = json_decode($room->images, true);
                    if (is_array($images)) {
                        $room->images_array = $images;
                    }
                }

                // Format giá tiền
                $room->formatted_price = number_format($room->price_per_night, 0, ',', '.') . ' VND';

                // Format ngày tạo
                $room->formatted_created_at = date('d/m/Y', strtotime($room->created_at));
            }

            return view('admin.rooms.rooms_management', compact('rooms'));
        } catch (\Exception $e) {
            return view('admin.rooms.rooms_management')
                ->with('error', 'Có lỗi khi load danh sách phòng: ' . $e->getMessage())
                ->with('rooms', collect([]));
        }
    }
}
