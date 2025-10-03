<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'rv_id';

    protected $fillable = [
        'bs_id',
        'c_id',
        'rating',
        'comments',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime'
    ];

    /**
     * Mối quan hệ với bảng users (khách hàng)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'c_id', 'id');
    }

    /**
     * Lấy thông tin booking từ bảng booking
     */
    public function getBookingInfo()
    {
        return \DB::table('booking')
            ->where('b_id', $this->bs_id)
            ->first();
    }

    /**
     * Scope để lấy đánh giá active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope để lấy đánh giá theo phòng
     */
    public function scopeByRoom($query, $roomId)
    {
        return $query->whereExists(function ($subquery) use ($roomId) {
            $subquery->select(\DB::raw(1))
                ->from('booking')
                ->join('booking_details', 'booking.b_id', '=', 'booking_details.b_id')
                ->whereColumn('reviews.bs_id', 'booking.b_id')
                ->where('booking_details.r_id', $roomId);
        });
    }

    /**
     * Kiểm tra xem user có thể đánh giá booking này không
     */
    public static function canUserReview($userId, $bookingId): bool
    {
        // Kiểm tra xem user có đặt phòng này và đã trả phòng chưa
        $booking = \DB::table('booking')
            ->where('b_id', $bookingId)
            ->where('u_id', $userId)
            ->where('status', 2) // 2 = completed (đã trả phòng)
            ->first();

        if (!$booking) {
            return false;
        }

        // Kiểm tra xem user đã đánh giá booking này chưa
        $existingReview = self::where('bs_id', $bookingId)
            ->where('c_id', $userId)
            ->first();

        return !$existingReview;
    }
}