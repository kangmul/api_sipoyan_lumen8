<?php

namespace App\Models;

use DateTime;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Datawarga extends Model
{
    protected $table = 'data_warga';

    public static function queryAllAge()
    {
        $query = "WITH usia AS (SELECT date_part('YEAR', AGE(tanggal_lahir)) AS tahun, date_part('MONTH', age(tanggal_lahir)) AS bulan, nama_lengkap, age(tanggal_lahir) AS usia, tanggal_lahir FROM data_warga WHERE tanggal_lahir NOTNULL)";
        return $query;
    }

    public function alldatawarga($request)
    {
        $limit = !empty($request->limit) ?  (int) $request->limit : 10;
        $offset = !empty($request->offset) ? (int) $request->offset : 0;

        $alldatawarga = DB::table('data_warga')
            ->select('nama_lengkap', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_perkawinan_id', 'status_hub_kel_id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $querycountAll = DB::table('data_warga')->count();
        $data = [
            'data' => $alldatawarga,
            'countAll' => $querycountAll,
            'limit' => $limit,
            'offset' => $offset,
        ];
        return $data;
    }

    public function bayi($request)
    {
        $limit = !empty($request->limit) ?  (int) $request->limit : 10;
        $offset = !empty($request->offset) ? (int) $request->offset : 0;
        $now = date("Y-m-d H:i:s");
        $Thremontage = date("Y-m-d H:i:s", strtotime("-3 Month"));

        $bayi = DB::table('data_warga')
            ->select('nama_lengkap', 'tanggal_lahir')
            ->selectRaw("date_part('MONTH', age(tanggal_lahir)) AS usia_dalam_bulan")
            ->selectRaw('age(tanggal_lahir) AS usia')
            ->where('tanggal_lahir', '<=', $now)
            ->Where('tanggal_lahir', '>=', $Thremontage)
            ->limit($limit)
            ->offset($offset)
            ->get();

        $querycountAll = DB::table("data_warga")->whereBetween('tanggal_lahir', [$Thremontage, $now])->count();

        $data = [
            'data' => $bayi,
            'countAll' => $querycountAll,
            'limit' => $limit,
            'offset' => $offset,
        ];
        return $data;
    }

    public function batita($request)
    {
        $limit = !empty($request->limit) ?  (int) $request->limit : 10;
        $offset = !empty($request->offset) ? (int) $request->offset : 0;
        $now = date("Y-m-d H:i:s");
        $Thremontage = date("Y-m-d H:i:s", strtotime("-33 Month"));

        $batita = DB::table('data_warga')
            ->select('nama_lengkap', 'tanggal_lahir')
            ->selectRaw("(date_part('years', age(tanggal_lahir)) * 12) + (date_part('month', age(tanggal_lahir)))   AS usia_dalam_bulan")
            ->selectRaw('age(tanggal_lahir) AS usia')
            ->whereRaw('tanggal_lahir >= now() - \'3 years\'::interval')
            ->WhereRaw('tanggal_lahir <= now() - \'3 month\'::interval')
            ->limit($limit)
            ->offset($offset)
            ->get();


        $querycountAll = DB::table("data_warga")
            ->whereRaw('tanggal_lahir >= now() - \'3 years\'::interval')
            ->WhereRaw('tanggal_lahir <= now() - \'3 month\'::interval')
            ->count();

        $data = [
            'data' => $batita,
            'countAll' => $querycountAll,
            'limit' => $limit,
            'offset' => $offset,
        ];
        return $data;
    }

    public function balita($request)
    {
        $limit = !empty($request->limit) ?  (int) $request->limit : 10;
        $offset = !empty($request->offset) ? (int) $request->offset : 0;
        $now = date("Y-m-d H:i:s");
        $fiveyearsold = date("Y-m-d H:i:s", strtotime("-36 Month"));

        $balita = DB::table('data_warga')
            ->select('nama_lengkap', 'tanggal_lahir')
            ->selectRaw("(date_part('years', age(tanggal_lahir)) * 12) + (date_part('month', age(tanggal_lahir)))   AS usia_dalam_bulan")
            ->selectRaw('age(tanggal_lahir) AS usia')
            ->whereRaw('tanggal_lahir >= now() - \'5 years\'::interval')
            ->WhereRaw('tanggal_lahir <= now() - \'3 years\'::interval')
            ->limit($limit)
            ->offset($offset)
            ->get();

        $querycountAll = DB::table("data_warga")
            ->whereRaw('tanggal_lahir >= now() - \'5 years\'::interval')
            ->WhereRaw('tanggal_lahir <= now() - \'3 years\'::interval')
            ->count();

        $data = [
            'data' => $balita,
            'countAll' => $querycountAll,
            'limit' => $limit,
            'offset' => $offset,
        ];
        return $data;
    }

    public function lansia(Request $request)
    {
        $limit = !empty($request->limit) ?  (int) $request->limit : 10;
        $offset = !empty($request->offset) ? (int) $request->offset : 0;

        $query = DB::select(self::queryAllAge() . "SELECT nama_lengkap, tanggal_lahir, (tahun * 12) + bulan AS usia_dalam_bulan, usia FROM usia WHERE (tahun * 12) + bulan >= 780 LIMIT " . $limit . " OFFSET " . $offset);

        $querycountAll = DB::select(self::queryAllAge() . "SELECT count(*) AS jml_data FROM usia WHERE (tahun * 12) + bulan >= 780");

        $data = [
            'data' => $query,
            'countAll' => $querycountAll[0]->jml_data,
            'limit' => $limit,
            'offset' => $offset,
        ];
        return $data;
    }
}
