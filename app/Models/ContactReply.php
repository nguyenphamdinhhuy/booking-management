<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    protected $fillable = [
        'contact_id',
        'admin_id',
        'reply',
    ];
    public $timestamps = false;

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}