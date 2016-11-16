<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tai_khoan extends Model
{
    protected $table = 'tai_khoan';
    protected $fillable = ['id',
    'ten_rieng',
    'username',
    'password',
    'email',
    'loai_tai_khoan',
    'activated',
    ];

    protected $guarded =['loai_tai_khoan'];

    public $timestamps = true;
}
