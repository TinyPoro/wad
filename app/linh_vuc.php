<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class linh_vuc extends Model
{
    protected $table = 'linh_vuc';
    protected $fillable = ['id',
    'ten',
    ];

    public $timestamps = true;
}
