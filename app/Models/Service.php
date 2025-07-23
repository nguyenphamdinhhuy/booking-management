<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 's_id';
    public $timestamps = false; // Nếu bạn muốn Eloquent không quản lý tự động created_at, updated_at

    protected $fillable = [
        'name',
        'price',
        'unit',
        'is_available',
        'description',
        'category_id',
        'image',
        'max_quantity',
        'service_time',
        'location',
        'note',
        'created_at',
        'updated_at',
    ];

    // Quan hệ: Mỗi dịch vụ thuộc một danh mục
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}
