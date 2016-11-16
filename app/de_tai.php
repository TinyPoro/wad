<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class de_tai extends Model
{
    protected $table = 'de_tai';
    protected $fillable = ['id',
    'ten',
    'id_linh_vuc',
    'id_giang_vien',
    ];

    public $timestamps = true;
}
