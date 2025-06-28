<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Province extends Model
{
    use HasSpatial;

    protected $table = 'province';

    protected $fillable = [
        'id', 'name', 'area', 'zoom', 'center_point',
    ];

    protected $casts = [
        'id' => 'string',
        'area' => MultiPolygon::class,
        'center_point' => Point::class,
    ];
}
