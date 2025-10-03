<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomType extends Model
{
    protected $table = 'room_types';
    protected $primaryKey = 'rt_id';
    
    protected $fillable = [
        'type_name',
        'description',
        'base_price',
        'max_guests',
        'number_beds',
        'room_size',
        'amenities',
        'images',
        'status'
    ];

    protected $casts = [
        'base_price' => 'decimal:0',
        'max_guests' => 'integer',
        'number_beds' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor để lấy danh sách amenities
    public function getAmenitiesListAttribute()
    {
        return $this->amenities ? json_decode($this->amenities, true) : [];
    }

    // Accessor để lấy danh sách hình ảnh
    public function getImagesListAttribute()
    {
        return $this->images ? json_decode($this->images, true) : [];
    }

    // Accessor để lấy URL hình ảnh đầu tiên
    public function getFirstImageUrlAttribute()
    {
        $images = $this->images_list;
        if (!empty($images)) {
            return Storage::url($images[0]);
        }
        return asset('images/no-image.jpg'); // Ảnh mặc định
    }

    // Accessor để lấy tất cả URL hình ảnh
    public function getImageUrlsAttribute()
    {
        $images = $this->images_list;
        $urls = [];
        foreach ($images as $image) {
            $urls[] = Storage::url($image);
        }
        return $urls;
    }

    // Accessor để format giá
    public function getFormattedPriceAttribute()
    {
        return number_format($this->base_price, 0, ',', '.') . ' VNĐ';
    }

    // Accessor để lấy trạng thái text
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Hoạt động' : 'Không hoạt động';
    }

    // Scope để lấy các loại phòng active
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope để lấy các loại phòng inactive
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
}