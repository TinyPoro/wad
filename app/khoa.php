<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class khoa extends Model
{
    protected $table = 'khoa';
    protected $fillable = ['id',
    'name',
    'so_luong_sinh_sv',
    'mo_dang_ky',
    'admin_id',
    ];

    protected $hidden = ['admin_id',
    ];

    public $timestamps = true;
}
