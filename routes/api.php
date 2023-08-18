<?php

use App\Models\Sekolah;
use App\Models\Tapel;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/sekolah", function(Request $request) {
    $sekolah = Sekolah::with('ks')->first();
    return response()->json(['status' => 'ok', 'sekolah' => $sekolah], 200);
})->middleware('isLegal');

Route::get('/siswa', function(Request $request) {
    try {
        $tapel = Tapel::where('status', '1')->first();
        $kode_tapel = $tapel->kode;
        $res = 'Berikut adalah info siswa yang dapat saya berikan:'."\n";

        if($request->query('kelas') && !$request->query('nama')) {
            if ($request->query('kelas') !== 'all') {
                $rombels = Rombel::where('kode', 'LIKE', '%'.$request->query('kelas'))->where('tapel', $tapel->kode)->with('siswas','guru')->get();
            } else {
                $rombels = Rombel::where('tapel', $tapel->kode)->with('siswas', 'guru')->get();
            }
            
            foreach($rombels as $rombel)
            {
                $label = $rombel->label;
                $jml = count($rombel->siswas);
                $lk = count($rombel->siswas->filter(function ($siswa) {
                    return $siswa->jk == 'Laki-laki';
                })->values());
                $pr = count($rombel->siswas->filter(function ($siswa) {
                    return $siswa->jk == 'Perempuan';
                })->values());
            $res .= "
$label: 
Lk:  $lk, Pr: $pr, Jml: $jml\n";
        }
        
            
        }

        if ($request->query('nama') && !$request->query('kelas')) {
            $siswas = Siswa::where('nama','LIKE', '%'.$request->query('nama').'%')
            // ->whereHas('rombel', function($q) use($kode_tapel) {
            //     $q->where('tapel', $kode_tapel);
            // })
            ->with('rombel')
            ->get();
            if(count($siswas) < 1) {
                $res = "Maaf, saya tidak menemukan siswa dengan nama:". $request->query('nama')."\n\nSilahkan menanyakan kepada operator Sekolah apakah sudah dimasukkan." ;
            }
            // $res .= count($siswas)."\n";
            foreach($siswas as $siswa) {
                $res .= "
Nama: $siswa->nama,
NISN: $siswa->nisn,
NIS: $siswa->nis,
JK: $siswa->jk,
TTL: $siswa->tempat_lahir , $siswa->tanggal_lahir,
Alamat: $siswa->alamat,
No HP: $siswa->hp,
Kelas:". $siswa->rombel[0]->label ."
Status: $siswa->status
                ";
            }

        }

        if($request->query('kelas') && $request->query('name')) {
            $rombel = $request->query('kelas');
            $siswas = Siswa::where('nama','LIKE','%'.$request->query('nama').'%')
                            ->whereHas('rombel', function($q) use($rombel) {
                                $q->where('rombels.kode', 'LIKE', '%'.$rombel);
                            })->get();

            foreach($siswas as $siswa) {
                $res .= "
Nama: $siswa->nama,
NISN: $siswa->nisn,
NIS: $siswa->nis,
JK: $siswa->jk,
TTL: $siswa->tempat_lahir , $siswa->tanggal_lahir,
Alamat: $siswa->alamat,
No HP: $siswa->hp,
Kelas: $siswa->rombel->label
Status: $siswa->status
                ";
            }

        }
        return response()->json(['status' => 'ok', 'siswas' => $res], 200);
    } catch(\Exception $e) {
        dd($e->getMessage());
    }
})->middleware('isLegal');