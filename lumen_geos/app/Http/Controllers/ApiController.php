<?php

namespace App\Http\Controllers;

use App\Models\AllAset;
use App\Models\GarduInduk;
use App\Models\Pembangkit;
use App\Models\Province;
use App\Models\Transmisi;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getAllProvinsi()
    {
        try {
            // dd("ini provinsi");
            $listProvinsi = Province::select('name', 'id', 'zoom', 'center_point', 'area', )->orderBy('name')->get();
            return $listProvinsi;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllAset($id)
    {
        try {

            $listAset = AllAset::select('nama_aset', 'nama_tipe', 'geometry', 'provinsi_id')
                ->where('provinsi_id', '=', $id)
                ->get();
            return $listAset;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getProvinceBoundary($id)
    {
        try {
            $result = Province::select('id', 'name', 'area')
                ->where('id', '=', $id)
                ->get();
            return $result;
        } catch (\Throwable $th) {}
    }

    public function getGarduInduk($id)
    {
        try {
            $garduIndukList = GarduInduk::select('provinsi_id', 'nama_aset', 'nama_tipe', 'geometry')
                ->where('provinsi_id', '=', $id)
                ->where('nama_tipe', '=', 'Gardu Induk')
                ->get();

            return $garduIndukList;
        } catch (\Throwable $th) {
            dd('eror');
        }
    }

    public function getPembangkit($id)
    {
        try {
            $pembangkitList = Pembangkit::select('provinsi_id', 'nama_aset', 'nama_tipe', 'geometry')
                ->where('provinsi_id', '=', $id)
                ->where('nama_tipe', '=', 'Pembangkit')
                ->get();

            return $pembangkitList;
        } catch (\Throwable $th) {
            dd('eror');
        }
    }

    public function getTransmisi($id)
    {

        try {
            $transmisiList = Transmisi::select('provinsi_id', 'nama_aset', 'nama_tipe', 'geometry')
                ->where('provinsi_id', '=', $id)
                ->where('nama_tipe', '=', 'Transmisi')
                ->get();

            return $transmisiList;
        } catch (\Throwable $th) {
            dd('eror');
        }
    }

    public function getBatasProvinsi($id)
    {
        try {
            // dd('halo');
            $polygonList = Province::select('id', 'name', 'area')->
                where('id', '=', $id)
                ->get();
            return $polygonList;
        } catch (\Throwable $th) {
            dd('eror');
        }
    }
}
