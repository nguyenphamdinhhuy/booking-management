<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Tên bảng trong DB
    protected $table = 'rooms';

    // Khóa chính
    protected $primaryKey = 'r_id';

    // Cho phép Laravel tự quản lý created_at & updated_at
    public $timestamps = true;

    // Các cột được phép gán giá trị hàng loạt
    protected $fillable = [
        'name',
        'price_per_night',
        'max_guests',
        'number_beds',
        'images',
        'status',
        'description',
        'available',
        'rt_id',
    ];

    // Các cột sẽ được cast sang kiểu dữ liệu tương ứng
    protected $casts = [
        'price_per_night' => 'decimal:0',
        'max_guests'      => 'integer',
        'number_beds'     => 'integer',
        'status'          => 'boolean',
        'available'       => 'boolean',
    ];
}
