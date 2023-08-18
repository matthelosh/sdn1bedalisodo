<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Tapel;
use App\Models\Rombel;
use GuzzleHttp\Client;
use App\Models\Sekolah;
use App\Services\Waapi;
use App\Traits\WablasTrait;
use Illuminate\Http\Request;

class WaController extends Controller
{

    public function sendToUsers(Request $request) {
        $gurus = Guru::whereNot('role', 'admin')->get();
        $message = json_decode($request->data);
        $datas = [];
        foreach($gurus as $guru)
        {
           
            
            $data['phone'] = $guru->hp;
            $data['message'] = $message->text;
            $data['secret'] = false;
            $data['retry'] = false;
            $data['isGroup'] = false;

            array_push($datas, $data);

           
        }
        $this->kirim($datas);

        return response()->json([
            'result' => 'PEsan berhasil dikirim'
        ], 200);
    }


    public function send(Request $request, Waapi $waapi)
    {
        try {
            $message = json_decode($request->data);
                $params = [
                    'isGroup' => $message->isGroup,
                    'chatId' => $message->chatId,
                    'pesan' => $message->text,
                    'media' => $message->media ?? null
                ];
            $result = $waapi->send($params);
            return response()->json(
                $result
            , 200);
        } catch(\Exception $e) {
            return response()->json([
                'result' => $e->getMessage()
            ], 500);
        }
    }  

    public function listGroup(Waapi $waapi) {
        try {
            $groups = $waapi->listGroups();
            
            return response()->json($groups, 200);
        } catch(\Exception $e) {
            dd($e);
        }
    }
    
    public function replyDataSekolah(Request $request) {
        $sekolah = Sekolah::with('ks')->first();
        return response()->json(['status' => 'ok', 'sekolah' => $sekolah], 200);
    }

    public function replyDataSiswa(Request $request) {
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
                ->with('rombel')
                ->get();
                if(count($siswas) < 1) {
                    $res = "Maaf, saya tidak menemukan siswa dengan nama:". $request->query('nama')."\n\nSilahkan menanyakan kepada operator Sekolah apakah sudah dimasukkan." ;
                }
                foreach($siswas as $siswa) {
                    $rombel = count($siswa->rombel) > 0 ? $siswa->rombel[0]->label : '-';
                    $res .= "
    Nama: $siswa->nama,
    NISN: $siswa->nisn,
    NIS: $siswa->nis,
    JK: $siswa->jk,
    TTL: $siswa->tempat_lahir , $siswa->tanggal_lahir,
    Alamat: $siswa->alamat,
    No HP: $siswa->hp,
    Kelas: $rombel,
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
    }

    public function replyDataGuru(Request $request) {
        $q = $request->query('nama') == 'all' ? '%' : $request->query('nama');

        try {
            $res = "Berikut Data Guru yang Anda Cari:\n";
            $gurus = Guru::whereNot('role', 'admin')->where('nama', 'LIKE', '%'.$q.'%')->get();

            foreach($gurus as $guru)
            {
                $res .= "
Nama: $guru->nama,
Gelar Belakang: $guru->gelar_belakang,
NIP: $guru->nip,
NUPTK: $guru->nuptk,
Pangkat: $guru->pangkat,
Alamat: $guru->alamat,
HP: $guru->hp,
==============================
                ";
            }
            return response()->json(['status' => 'ok', 'gurus' => $res], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    
}
