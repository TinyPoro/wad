<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sinh_vien extends Model
{
    protected $table = 'sinh_vien';
    protected $fillable = ['id',
    'khoa_hoc',
    'id_khoa',
    'ctdt',
    'id_de_tai',
    'tt_dang_ky',
    'nop_ho_so',
    ];
}
