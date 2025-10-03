<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;


class homeController extends Controller
{
    public function home(Request $request)
    {
        try {
            // ========== PHẦN XỬ LÝ ROOMS (giữ nguyên logic cũ) ==========
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
                        $room->images = null;
                    }
                }

                // Tạo URL cho hình ảnh mặc định nếu không có ảnh
                if (!$room->images) {
                    $room->images = 'assets/images/default-room.jpg';
                }

                // Thêm thông tin bổ sung
                $room->is_new = (strtotime($room->created_at) > strtotime('-7 days'));
                $room->facilities = ['WiFi', 'Điều hòa', 'TV', 'Tủ lạnh'];
            }

            // Lấy thống kê rooms
            $totalRooms = DB::table('rooms')->where('status', 1)->where('available', true)->count();
            $avgPrice = DB::table('rooms')->where('status', 1)->where('available', true)->avg('price_per_night');

            $stats = [
                'total_rooms' => $totalRooms,
                'avg_price' => number_format($avgPrice, 0, ',', '.') . ' VND',
                'min_price' => number_format(DB::table('rooms')->where('status', 1)->where('available', true)->min('price_per_night'), 0, ',', '.') . ' VND',
                'max_price' => number_format(DB::table('rooms')->where('status', 1)->where('available', true)->max('price_per_night'), 0, ',', '.') . ' VND'
            ];

            // ========== PHẦN XỬ LÝ ROOM TYPES (thêm mới) ==========

            // Lấy danh sách loại phòng với xử lý tương tự rooms
            $roomTypesQuery = DB::table('room_types')
                ->select('*')
                ->where('status', 1);

            // Có thể thêm filter cho room types nếu cần
            if ($request->filled('room_type_search')) {
                $searchTerm = '%' . $request->room_type_search . '%';
                $roomTypesQuery->where(function ($q) use ($searchTerm) {
                    $q->where('type_name', 'LIKE', $searchTerm)
                        ->orWhere('description', 'LIKE', $searchTerm);
                });
            }

            $roomTypes = $roomTypesQuery->orderBy('created_at', 'desc')->limit(6)->get();

            // Xử lý format dữ liệu cho từng room type
            foreach ($roomTypes as $roomType) {
                // Format giá tiền
                $roomType->formatted_price = number_format($roomType->base_price, 0, ',', '.') . ' VND';

                // Xử lý hình ảnh - room_types có thể có multiple images trong text field
                if ($roomType->images) {
                    // Giả sử images lưu dạng JSON hoặc comma-separated
                    $images = json_decode($roomType->images, true);
                    if (!$images) {
                        // Nếu không phải JSON, có thể là comma-separated
                        $images = explode(',', $roomType->images);
                    }
                    $roomType->image_list = $images;
                    $roomType->main_image = is_array($images) && count($images) > 0 ? trim($images[0]) : 'assets/images/default-room-type.jpg';
                } else {
                    $roomType->main_image = 'assets/images/default-room-type.jpg';
                    $roomType->image_list = [];
                }

                // Xử lý amenities
                if ($roomType->amenities) {
                    $amenities = json_decode($roomType->amenities, true);
                    if (!$amenities) {
                        $amenities = explode(',', $roomType->amenities);
                    }
                    $roomType->amenity_list = is_array($amenities) ? array_map('trim', $amenities) : [];
                } else {
                    $roomType->amenity_list = ['WiFi', 'Điều hòa', 'TV'];
                }

                // Format ngày tạo
                if ($roomType->created_at) {
                    $roomType->formatted_created_at = date('d/m/Y', strtotime($roomType->created_at));
                }

                // Thêm thông tin bổ sung
                $roomType->is_new = (strtotime($roomType->created_at) > strtotime('-7 days'));
            }

            // Thêm thống kê room types vào stats
            $stats['total_room_types'] = DB::table('room_types')->where('status', 1)->count();

            // ========== XỬ LÝ SERVICE CATEGORIES (giữ nguyên) ==========
            $serviceCategories = ServiceCategory::with('services')->get();
            $services = ServiceCategory::all();

            // ========== XỬ LÝ AJAX REQUEST ==========
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rooms' => $rooms,
                    'roomTypes' => $roomTypes,
                    'stats' => $stats,
                    'rooms_count' => $rooms->count(),
                    'room_types_count' => $roomTypes->count()
                ]);
            }

            $reviewData = DB::table('reviews')
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_reviews')
                ->first();

            // Lấy 1 số review có 4 - 5 sao (ví dụ: lấy 5 review gần nhất)
            $highReviews = DB::table('reviews')
                ->whereBetween('rating', [4, 5])
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Lấy album banner mới nhất (3 banner có cùng album_code)
            $latestAlbum = Banner::where('status', 1)
                ->where('status', 1)

                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('album_code')
                ->first(); // Lấy album mới nhất

            // Trả về view
            // return view('user.home', compact('reviewData', 'highReviews','rooms', 'stats', 'services', 'serviceCategories','latestAlbum'));

            // ========== TRẢ VỀ VIEW ==========
            return view('user.home', compact('rooms', 'reviewData', 'highReviews', 'latestAlbum', 'roomTypes', 'stats', 'services', 'serviceCategories'));
        } catch (\Exception $e) {
            // Log lỗi
            \Log::error('Error loading data for user home: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi khi tải dữ liệu'
                ]);
            }

            return view('user.home')
                ->with('error', 'Có lỗi khi tải dữ liệu')
                ->with('rooms', collect([]))
                ->with('roomTypes', collect([]))
                ->with('services', collect([]))
                ->with('serviceCategories', collect([]))
                ->with('stats', [
                    'total_rooms' => 0,
                    'total_room_types' => 0,
                    'avg_price' => '0 VND',
                    'min_price' => '0 VND',
                    'max_price' => '0 VND'
                ]);
        }
    }
}
