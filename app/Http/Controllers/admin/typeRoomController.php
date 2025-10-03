<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\roomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class typeRoomController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::where('is_delete', 0); // Chỉ lấy những item chưa bị xóa mềm

        // Debug: Log các tham số đầu vào
        \Log::info('Filter parameters:', $request->all());

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            \Log::info('Status filter applied: ' . $request->status);
        }

        // Lọc theo số khách tối đa
        if ($request->filled('max_guests')) {
            if ($request->max_guests == '4') {
                $query->where('max_guests', '>=', 4);
            } else {
                $query->where('max_guests', $request->max_guests);
            }
            \Log::info('Max guests filter applied: ' . $request->max_guests);
        }

        // Lọc theo khoảng giá
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case '0-500000':
                    $query->where('base_price', '<', 500000);
                    break;
                case '500000-1000000':
                    $query->whereBetween('base_price', [500000, 1000000]);
                    break;
                case '1000000-2000000':
                    $query->whereBetween('base_price', [1000000, 2000000]);
                    break;
                case '2000000+':
                    $query->where('base_price', '>', 2000000);
                    break;
            }
            \Log::info('Price range filter applied: ' . $request->price_range);
        }

        // Lọc theo số giường
        if ($request->filled('number_beds')) {
            if ($request->number_beds == '3+') {
                $query->where('number_beds', '>=', 3);
            } else {
                $query->where('number_beds', $request->number_beds);
            }
            \Log::info('Number beds filter applied: ' . $request->number_beds);
        }

        // Tìm kiếm theo tên loại phòng và mô tả
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('type_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
            \Log::info('Search filter applied: ' . $searchTerm);
        }

        // Debug: Log SQL query
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        \Log::info('Final SQL: ' . $sql, $bindings);

        // Sắp xếp theo thời gian tạo (mới nhất trước)
        $roomTypes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Debug: Log số lượng kết quả
        \Log::info('Results count: ' . $roomTypes->total());

        // Giữ lại các tham số filter khi phân trang
        $roomTypes->appends($request->query());

        return view('admin.room_types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:100|unique:room_types,type_name',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1|max:20',
            'number_beds' => 'required|integer|min:1|max:10',
            'room_size' => 'nullable|string|max:50',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'status' => 'required|boolean',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ], [
            'type_name.required' => 'Tên loại phòng là bắt buộc',
            'type_name.unique' => 'Tên loại phòng đã tồn tại',
            'base_price.required' => 'Giá cơ bản là bắt buộc',
            'base_price.numeric' => 'Giá cơ bản phải là số',
            'max_guests.required' => 'Số khách tối đa là bắt buộc',
            'number_beds.required' => 'Số giường là bắt buộc',
            'images.max' => 'Chỉ được tải lên tối đa 10 ảnh',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.max' => 'Kích thước ảnh không được vượt quá 5MB',
        ]);

        try {
            $roomType = new RoomType();
            $roomType->type_name = $request->type_name;
            $roomType->description = $request->description;
            $roomType->base_price = $request->base_price;
            $roomType->max_guests = $request->max_guests;
            $roomType->number_beds = $request->number_beds;
            $roomType->room_size = $request->room_size;
            $roomType->status = $request->status;
            $roomType->is_delete = 0; // Mặc định không bị xóa

            // Xử lý amenities
            if ($request->has('amenities') && is_array($request->amenities)) {
                $roomType->amenities = json_encode(array_filter($request->amenities));
            }

            // Xử lý upload ảnh - lưu trong thư mục public
            if ($request->hasFile('images')) {
                $imagePaths = [];
                $uploadPath = public_path('uploads/room-types');

                // Tạo thư mục nếu chưa tồn tại
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    $imagePaths[] = 'uploads/room-types/' . $filename;
                }
                $roomType->images = json_encode($imagePaths);
            }

            $roomType->save();

            return redirect()->route('admin.roomType.index')
                ->with('success', 'Loại phòng đã được thêm thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm loại phòng: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $roomType = RoomType::where('is_delete', 0)->findOrFail($id);
        return view('admin.room_types.show', compact('roomType'));
    }

    public function edit($id)
    {
        $roomType = RoomType::where('is_delete', 0)->findOrFail($id);
        return view('admin.room_types.edit', compact('roomType'));
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::where('is_delete', 0)->findOrFail($id);

        $request->validate([
            'type_name' => 'required|string|max:100|unique:room_types,type_name,' . $id . ',rt_id',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1|max:20',
            'number_beds' => 'required|integer|min:1|max:10',
            'room_size' => 'nullable|string|max:50',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'status' => 'required|boolean',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'type_name.required' => 'Tên loại phòng là bắt buộc',
            'type_name.unique' => 'Tên loại phòng đã tồn tại',
            'base_price.required' => 'Giá cơ bản là bắt buộc',
            'base_price.numeric' => 'Giá cơ bản phải là số',
            'max_guests.required' => 'Số khách tối đa là bắt buộc',
            'number_beds.required' => 'Số giường là bắt buộc',
            'images.max' => 'Chỉ được tải lên tối đa 10 ảnh',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.max' => 'Kích thước ảnh không được vượt quá 5MB',
        ]);

        try {
            $roomType->type_name = $request->type_name;
            $roomType->description = $request->description;
            $roomType->base_price = $request->base_price;
            $roomType->max_guests = $request->max_guests;
            $roomType->number_beds = $request->number_beds;
            $roomType->room_size = $request->room_size;
            $roomType->status = $request->status;

            // Xử lý amenities
            if ($request->has('amenities') && is_array($request->amenities)) {
                $roomType->amenities = json_encode(array_filter($request->amenities));
            } else {
                $roomType->amenities = null;
            }

            // Xử lý upload ảnh mới
            if ($request->hasFile('images')) {
                // Xóa ảnh cũ
                if ($roomType->images) {
                    $oldImages = json_decode($roomType->images, true);
                    foreach ($oldImages as $oldImage) {
                        $oldImagePath = public_path($oldImage);
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }
                }

                // Upload ảnh mới
                $imagePaths = [];
                $uploadPath = public_path('uploads/room-types');

                // Tạo thư mục nếu chưa tồn tại
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    $imagePaths[] = 'uploads/room-types/' . $filename;
                }
                $roomType->images = json_encode($imagePaths);
            }

            $roomType->save();

            return redirect()->route('admin.roomType.index')
                ->with('success', 'Loại phòng đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật loại phòng: ' . $e->getMessage());
        }
    }

    /**
     * Xóa mềm - chỉ đặt is_delete = 1
     */
    public function destroy($id)
    {
        try {
            $roomType = RoomType::where('is_delete', 0)->findOrFail($id);

            // Xóa mềm - chỉ cập nhật cột is_delete
            $roomType->is_delete = 1;
            $roomType->save();

            return redirect()->route('admin.roomType.index')
                ->with('success', 'Loại phòng đã được chuyển vào thùng rác!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa loại phòng: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị danh sách đã xóa (thùng rác)
     */
    public function trash(Request $request)
    {
        $query = RoomType::where('is_delete', 1); // Chỉ lấy những item đã bị xóa mềm

        // Các bộ lọc giống như index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('max_guests')) {
            if ($request->max_guests == '4') {
                $query->where('max_guests', '>=', 4);
            } else {
                $query->where('max_guests', $request->max_guests);
            }
        }

        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case '0-500000':
                    $query->where('base_price', '<', 500000);
                    break;
                case '500000-1000000':
                    $query->whereBetween('base_price', [500000, 1000000]);
                    break;
                case '1000000-2000000':
                    $query->whereBetween('base_price', [1000000, 2000000]);
                    break;
                case '2000000+':
                    $query->where('base_price', '>', 2000000);
                    break;
            }
        }

        if ($request->filled('number_beds')) {
            if ($request->number_beds == '3+') {
                $query->where('number_beds', '>=', 3);
            } else {
                $query->where('number_beds', $request->number_beds);
            }
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('type_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $roomTypes = $query->orderBy('updated_at', 'desc')->paginate(10);
        $roomTypes->appends($request->query());

        return view('admin.room_types.trash', compact('roomTypes'));
    }

    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id)
    {
        try {
            $roomType = RoomType::where('is_delete', 1)->findOrFail($id);

            $roomType->is_delete = 0;
            $roomType->save();

            return redirect()->route('admin.roomType.trash')
                ->with('success', 'Loại phòng đã được khôi phục thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi khôi phục loại phòng: ' . $e->getMessage());
        }
    }

    /**
     * Xóa vĩnh viễn (chỉ admin)
     */
    public function forceDelete($id)
    {
        try {
            // Kiểm tra quyền admin (giả sử bạn có trường role hoặc is_admin)
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return back()->with('error', 'Chỉ admin mới có quyền xóa vĩnh viễn!');
            }

            $roomType = RoomType::where('is_delete', 1)->findOrFail($id);

            // Xóa ảnh khỏi thư mục public
            if ($roomType->images) {
                $images = json_decode($roomType->images, true);
                foreach ($images as $image) {
                    $imagePath = public_path($image);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }

            // Xóa vĩnh viễn khỏi database
            $roomType->forceDelete();

            return redirect()->route('admin.roomType.trash')
                ->with('success', 'Loại phòng đã được xóa vĩnh viễn!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn loại phòng: ' . $e->getMessage());
        }
    }

    /**
     * Khôi phục tất cả
     */
    public function restoreAll()
    {
        try {
            RoomType::where('is_delete', 1)->update(['is_delete' => 0]);

            return redirect()->route('admin.roomType.trash')
                ->with('success', 'Tất cả loại phòng đã được khôi phục!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi khôi phục: ' . $e->getMessage());
        }
    }

    /**
     * Xóa vĩnh viễn tất cả (chỉ admin)
     */
    public function forceDeleteAll()
    {
        try {
            // Kiểm tra quyền admin
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return back()->with('error', 'Chỉ admin mới có quyền xóa vĩnh viễn!');
            }

            $roomTypes = RoomType::where('is_delete', 1)->get();

            foreach ($roomTypes as $roomType) {
                // Xóa ảnh
                if ($roomType->images) {
                    $images = json_decode($roomType->images, true);
                    foreach ($images as $image) {
                        $imagePath = public_path($image);
                        if (File::exists($imagePath)) {
                            File::delete($imagePath);
                        }
                    }
                }
                $roomType->forceDelete();
            }

            return redirect()->route('admin.roomType.trash')
                ->with('success', 'Tất cả loại phòng đã được xóa vĩnh viễn!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn: ' . $e->getMessage());
        }
    }

    /**
     * Method để debug và kiểm tra dữ liệu
     */
    public function debug(Request $request)
    {
        // Kiểm tra tổng số bản ghi
        $totalRecords = RoomType::where('is_delete', 0)->count();
        $deletedRecords = RoomType::where('is_delete', 1)->count();

        // Kiểm tra các giá trị cột
        $statusDistribution = RoomType::where('is_delete', 0)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $guestsDistribution = RoomType::where('is_delete', 0)
            ->selectRaw('max_guests, COUNT(*) as count')
            ->groupBy('max_guests')
            ->get();

        $bedsDistribution = RoomType::where('is_delete', 0)
            ->selectRaw('number_beds, COUNT(*) as count')
            ->groupBy('number_beds')
            ->get();

        $priceRange = RoomType::where('is_delete', 0)
            ->selectRaw('MIN(base_price) as min_price, MAX(base_price) as max_price')
            ->first();

        return response()->json([
            'total_records' => $totalRecords,
            'deleted_records' => $deletedRecords,
            'status_distribution' => $statusDistribution,
            'guests_distribution' => $guestsDistribution,
            'beds_distribution' => $bedsDistribution,
            'price_range' => $priceRange,
            'sample_data' => RoomType::where('is_delete', 0)->limit(3)->get()
        ]);
    }

    /**
     * Phương thức AJAX để lọc dữ liệu theo thời gian thực (tùy chọn)
     */
    public function ajaxFilter(Request $request)
    {
        $isTrash = $request->get('is_trash', false);
        $query = RoomType::where('is_delete', $isTrash ? 1 : 0);

        // Áp dụng các bộ lọc giống như trong method index
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('max_guests') && $request->max_guests !== '') {
            if ($request->max_guests == '4') {
                $query->where('max_guests', '>=', 4);
            } else {
                $query->where('max_guests', $request->max_guests);
            }
        }

        if ($request->has('price_range') && $request->price_range !== '') {
            switch ($request->price_range) {
                case '0-500000':
                    $query->where('base_price', '<', 500000);
                    break;
                case '500000-1000000':
                    $query->whereBetween('base_price', [500000, 1000000]);
                    break;
                case '1000000-2000000':
                    $query->whereBetween('base_price', [1000000, 2000000]);
                    break;
                case '2000000+':
                    $query->where('base_price', '>', 2000000);
                    break;
            }
        }

        if ($request->has('number_beds') && $request->number_beds !== '') {
            if ($request->number_beds == '3+') {
                $query->where('number_beds', '>=', 3);
            } else {
                $query->where('number_beds', $request->number_beds);
            }
        }

        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('type_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $orderBy = $isTrash ? 'updated_at' : 'created_at';
        $roomTypes = $query->orderBy($orderBy, 'desc')->paginate(10);
        $roomTypes->appends($request->query());

        return response()->json([
            'success' => true,
            'data' => $roomTypes->items(),
            'pagination' => [
                'current_page' => $roomTypes->currentPage(),
                'last_page' => $roomTypes->lastPage(),
                'per_page' => $roomTypes->perPage(),
                'total' => $roomTypes->total(),
            ]
        ]);
    }
}
