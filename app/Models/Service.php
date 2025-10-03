<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $table = 'services';
    protected $primaryKey = 's_id';
    protected $dates = ['deleted_at'];
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
        'deleted_at',
    ];

    // Quan hệ: Mỗi dịch vụ thuộc một danh mục
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}
