<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $primaryKey = 'p_id';

    protected $fillable = [
        'title',
        'content',
        'category',
        'images',
        'status',
        'image',
        'author',
        'published_at',
    ];

    public $timestamps = true; // vì có created_at, updated_at
}