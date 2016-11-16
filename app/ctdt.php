<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ctdt extends Model
{
    protected $table = 'ctdt';
    protected $fillable = ['chuong_trinh',
    ];

    public $timestamps = true;
}
