<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'banners';

    // Khóa chính
    protected $primaryKey = 'b_id';

    // Bật timestamps nếu bảng có cột created_at, updated_at
    public $timestamps = false; // Nếu chỉ có created_at

    // Các cột có thể fill
    protected $fillable = [
        'title',
        'description',
        'images_path',
        'status',
        'created_at',
        'album_code'
    ];
}
