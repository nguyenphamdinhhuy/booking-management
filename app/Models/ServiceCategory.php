<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = 'service_categories';
    public $timestamps = true;

    protected $fillable = ['name', 'description', 'image'];

    // ✅ Ép kiểu created_at thành datetime để dùng ->format()
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Quan hệ: Một danh mục có nhiều dịch vụ
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
