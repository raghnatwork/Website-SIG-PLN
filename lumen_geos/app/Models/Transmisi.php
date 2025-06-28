<?php

namespace App\Models;

// use App\Casts\LineOrMultiLineStringCast;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Transmisi extends Model
{
    use HasSpatial;

    protected $table = 'aset';

    protected $fillable = [
        'nama_aset', 'nama_tipe', 'geometry',
    ];

    protected $casts = [
        'geometry' => MultiLineString::class,
    ];
}
