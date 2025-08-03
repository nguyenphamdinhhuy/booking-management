<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class atatistical extends Model
{
    protected $table = 'bookings';
    public $timestamps = true;

    protected $fillable = [
        'customer_id',
        'room_id',
        'booking_date',
        'check_in',
        'check_out',
        'total_price',
        'status', // ví dụ: 'cho_xac_nhan', 'da_huy', 'hoan_thanh'
        'note',
    ];

    // Nếu muốn định nghĩa quan hệ:
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
