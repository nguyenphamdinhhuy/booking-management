<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomTypeController extends Controller
{

    public function show($rt_id)
    {
        try {
            $roomType = DB::table('room_types')
                ->where('rt_id', $rt_id)
                ->where('status', 1)
                ->first();

            if (!$roomType) {
                abort(404, 'Loại phòng không tồn tại hoặc đã bị tạm dừng');
            }

            $roomType->amenities = json_decode($roomType->amenities, true) ?? [];
            $roomType->images = json_decode($roomType->images, true) ?? [];

            $availableRooms = DB::table('rooms')
                ->where('room_type_id', $rt_id)
                ->where('status', 1)
                ->count();

            $reviews = [];
            if (DB::getSchemaBuilder()->hasTable('reviews')) {
                $reviews = DB::table('reviews')
                    ->join('bookings', 'reviews.booking_id', '=', 'bookings.b_id')
                    ->join('users', 'bookings.user_id', '=', 'users.id')
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.room_id')
                    ->where('rooms.room_type_id', $rt_id)
                    ->select(
                        'reviews.*',
                        'users.name as customer_name',
                        'bookings.check_in_date',
                        'bookings.check_out_date'
                    )
                    ->orderBy('reviews.created_at', 'desc')
                    ->limit(10)
                    ->get();
            }

            $averageRating = 0;
            $totalReviews = count($reviews);
            if ($totalReviews > 0) {
                $totalRating = array_sum(array_column($reviews->toArray(), 'rating'));
                $averageRating = round($totalRating / $totalReviews, 1);
            }

            $suggestedRoomTypes = DB::table('room_types')
                ->where('rt_id', '!=', $rt_id)
                ->where('status', 1)
                ->limit(3)
                ->get();

            return view('room-types.detail', compact(
                'roomType',
                'availableRooms',
                'reviews',
                'averageRating',
                'totalReviews',
                'suggestedRoomTypes'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải thông tin loại phòng: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = DB::table('room_types')->where('status', 1);

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

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
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'rt_id' => 'required|integer',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $rtId = $request->rt_id;
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        // Đếm số phòng có sẵn
        $totalRooms = DB::table('rooms')
            ->where('room_type_id', $rtId)
            ->where('status', 1)
            ->count();

        // Đếm số phòng đã được đặt trong khoảng thời gian
        $bookedRooms = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.room_id')
            ->where('rooms.room_type_id', $rtId)
            ->where('bookings.status', '!=', 5) // Không tính các booking đã hủy
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->where(function($q) use ($checkIn, $checkOut) {
                    // Check-in falls within existing booking
                    $q->where('bookings.check_in_date', '<=', $checkIn)
                      ->where('bookings.check_out_date', '>', $checkIn);
                })->orWhere(function($q) use ($checkIn, $checkOut) {
                    // Check-out falls within existing booking
                    $q->where('bookings.check_in_date', '<', $checkOut)
                      ->where('bookings.check_out_date', '>=', $checkOut);
                })->orWhere(function($q) use ($checkIn, $checkOut) {
                    // Existing booking falls entirely within new booking
                    $q->where('bookings.check_in_date', '>=', $checkIn)
                      ->where('bookings.check_out_date', '<=', $checkOut);
                });
            })
            ->count();

        $availableRooms = $totalRooms - $bookedRooms;

        return response()->json([
            'available' => $availableRooms > 0,
            'available_rooms' => max(0, $availableRooms),
            'total_rooms' => $totalRooms
        ]);
    }
}