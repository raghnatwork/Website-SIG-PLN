<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
// use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class AllAset extends Model
{
    use HasSpatial;

    // Tentukan nama tabel jika tidak menggunakan konvensi penamaan Laravel
    protected $table = 'aset';

    // Tentukan atribut yang dapat diisi secara massal
    protected $fillable = ['nama_aset', 'nama_tipe', 'geometry', 'provinsi_id'];

    // Tentukan atribut yang harus di-cast
    protected $casts = [
        'geometry' => 'string',
    ];

    // Tentukan apakah model menggunakan timestamp otomatis
    // public $timestamps = false;
}
