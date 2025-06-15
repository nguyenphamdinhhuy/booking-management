<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
protected $table = 'services';
protected $primaryKey = 's_id';
public $timestamps = false;
protected $fillable = [
        'name',
        'price',
        'unit',
        'category_id',
        'is_available',
        'description',
        'image'
    ];

    // Quan hệ: Mỗi dịch vụ thuộc 1 danh mục
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}
