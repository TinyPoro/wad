<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class giang_vien extends Model
{
    protected $table = 'giang_vien';
    protected $fillable = ['id',
    'id_bo_mon',
    'huong_nghien_cuu',
    ];

    public $timestamps = true;
}
