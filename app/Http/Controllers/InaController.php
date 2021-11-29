<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\KotaKab;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

class InaController extends Controller
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
     * Cari semua provinsi di indonesia
     */
    public function allprovinsi(Request $request)
    {
        $provinsi = Provinsi::All()->toArray();
        if (empty($provinsi)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => '',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data provinsi berhasil ditemukan',
            'data' => $provinsi,
        ], 200);
    }

    /**
     * Cari semua Kota dan Kabupaten di Indonesia
     */
    public function allkotakab(Request $request)
    {
        // cari semua provinsi
        $kab_kota = KotaKab::All()->toArray();
        if (empty($kab_kota)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => '',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Kabupaten Kota berhasil ditemukan',
            'data' => $kab_kota,
        ], 200);
    }

    /**
     * Cari Kota atau Kabupaten berdasarkan Id
     */
    public function kotakabbyid(Request $request)
    {
        $kotakabbyid = KotaKab::find($request->id);
        if (empty($kotakabbyid)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => '',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Kota atau Kabupaten dengan id ' . $request->id . ' berhasil ditemukan',
            'data' => $kotakabbyid,
        ], 200);
    }


    /**
     * Cari Kota atau Kabupaten berdasarkan Id Provinsi
     */
    public function kotakabbyidProvinsi(Request $request)
    {
        $kotakabbyprovid = KotaKab::where('provinsi_id', '=', $request->id)->get()->toArray();
        if (empty($kotakabbyprovid)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => '',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Kota atau Kabupaten dengan id Provinsi ' . $request->id . ' berhasil ditemukan',
            'data' => $kotakabbyprovid,
        ], 200);
    }

    /**
     * Cari Nama Provinsi berdasarkan kode Provinsi
     */
    public function provinsi(Request $request)
    {
        // cari semua provinsi
        $provinsibyid = Provinsi::find($request->id);
        if (empty($provinsibyid)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => '',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data provinsi dengan id ' . $request->id . ' berhasil ditemukan',
            'data' => $provinsibyid,
        ], 200);
    }

    /**
     * Cari data semua kecamatan
     */
    public function kecamatan()
    {
        $kecamatan = Kecamatan::all()->toArray();
        if (empty($kecamatan)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => '',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Kecamatan berhasil ditemukan',
            'data' => $kecamatan,
        ], 200);
    }

    /**
     * Cari Kecamatan By ID
     */
    public function kecbyid(Request $request)
    {
        $kecamatanbyId = Kecamatan::find($request->id);
        if (empty($kecamatanbyId)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => '',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Kecamatan dengan id ' . $request->id . ' berhasil ditemukan',
            'data' => $kecamatanbyId,
        ], 200);
    }


    /**
     * Cari Kecamatan By Kota Kabupaten ID
     */
    public function kecbykotakabid(Request $request)
    {
        $kecamatanbykotakabId = Kecamatan::where('kota_kab_id', '=', $request->id)->get()->toArray();
        if (empty($kecamatanbykotakabId)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => ''
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data Kecamatan dengan ID Kota Kabupaten ' . $request->id . ' berhasil ditemukan',
            'data' => $kecamatanbykotakabId,
        ], 200);
    }

    /**
     * Cari data Kelurahan
     */
    public function kelurahan()
    {
        $kelurahan = Kelurahan::all()->toArray();
        if (empty($kelurahan)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => ''
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Data berhasil ditemukan',
            'data' => $kelurahan,
        ], 200);
    }

    /**
     * Cari Kelurahan by ID
     */
    public function kelurahanbyid(Request $request)
    {
        $kelurahanbyid = Kelurahan::find($request->id);
        if (empty($kelurahanbyid)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
                'data' => ''
            ], 404);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Kelurahan dengan id ' . $request->id . ' Berhasil ditemukan',
            'data' => $kelurahanbyid,
        ], 200);
    }

    /**
     * Kelurahan by Kecamatan ID
     */
    public function kelurahanbykecid(Request $request)
    {
        try {
            $kelbykecid = Kelurahan::where('kecamatan_id', '=', $request->id)->get()->toArray();
            if (empty($kelbykecid)) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Data tidak ditemukan',
                    'data' => '',
                ], 404);
            }

            return response()->json([
                'status' => 'Success',
                'message' => 'Data Kelurahan dengan id Kecamatan ' . $request->id . ' berhasil ditemukan',
                'data' => $kelbykecid,
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'code' => $e->getMessage(),
            ], 404);
        }
    }
}
