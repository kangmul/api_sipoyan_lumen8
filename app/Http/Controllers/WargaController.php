<?php

namespace App\Http\Controllers;

use App\Models\Datawarga;
use Error;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

class WargaController extends Controller
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

    /**
     * Menampilkan semua data warga
     */
    public function allwarga(Request $request)
    {
        try {
            $dataallwarga = Datawarga::alldatawarga($request);
            if (!empty($dataallwarga)) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data warga ditemukan',
                    'data' => $dataallwarga['data'],
                    'Allrecord' => $dataallwarga['countAll'],
                    'perPage' => $dataallwarga['limit'],
                    'page' => $dataallwarga['offset'],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Data tidak ditemukan',
                    'data' => '',
                ], 404);
            }
        } catch (Exception $e) {
            return "ERROR";
        }
    }

    /**
     * Tampilkan semua data bayi
     */
    public function bayi(Request $request)
    {
        try {
            $bayi = Datawarga::bayi($request);
            if (!empty($bayi)) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data warga ditemukan',
                    'data' => $bayi['data'],
                    'Allrecord' => $bayi['countAll'],
                    'perPage' => $bayi['limit'],
                    'page' => $bayi['offset'],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Data tidak ditemukan',
                    'data' => '',
                ], 404);
            }
        } catch (Exception $e) {
            return "ERROR";
        }
    }


    public function batita(Request $request)
    {
        try {
            $batita = Datawarga::batita($request);
            if (!empty($batita)) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data BATITA ditemukan',
                    'data' => $batita['data'],
                    'Allrecord' => $batita['countAll'],
                    'perPage' => $batita['limit'],
                    'page' => $batita['offset'],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Data tidak ditemukan',
                    'data' => '',
                ], 404);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Tampilkan data BALITA
     */
    public function balita(Request $request)
    {
        try {
            $balita = Datawarga::balita($request);
            if (!empty($balita)) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data BALITA ditemukan',
                    'data' => $balita['data'],
                    'Allrecord' => $balita['countAll'],
                    'perPage' => $balita['limit'],
                    'page' => $balita['offset'],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Data tidak ditemukan',
                    'data' => '',
                ], 404);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Tampilkan semua data warga LANSIA
     */
    public function lansia(Request $request)
    {
        try {
            $lansia = Datawarga::lansia($request);
            if (!empty($lansia)) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data warga lansia ditemukan',
                    'data' => $lansia['data'],
                    'Allrecord' => $lansia['countAll'],
                    'perPage' => $lansia['limit'],
                    'page' => $lansia['offset'],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Data tidak ditemukan',
                    'data' => '',
                ], 404);
            }
        } catch (Exception $e) {
            return "ERROR";
        }
    }
}
