<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Pembangkit extends Model
{
    use HasSpatial;

    protected $table = 'aset';

    protected $fillable = [
        'nama_aset', 'nama_tipe', 'geometry',
    ];

    protected $casts = [
        'geometry' => Point::class,
    ];
}
