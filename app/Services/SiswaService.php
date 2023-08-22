<?php
namespace App\Services;

use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;

class SiswaService
{
    public function store($siswa, $foto=null)
    {
        try {
            // dd($foto);
            if ($foto !== null) {
                $foto_url = $this->storeFoto($foto, $siswa->nisn);
            }

            $store = Siswa::updateOrCreate(
                [
                    'id' => $siswa->id ?? null,
                    
                ],
                [
                    'nisn' => $siswa->nisn ?? null,
                    'nis' => $siswa->nis ?? null,
                    'nama' => $siswa->nama,
                    'jk' => $siswa->jk,
                    'tempat_lahir' => $siswa->tempat_lahir,
                    'tanggal_lahir' => $siswa->tanggal_lahir,
                    'agama' => $siswa->agama,
                    'alamat' => $siswa->alamat,
                    'rt' => $siswa->rt ?? null,
                    'rw' => $siswa->rw ?? null,
                    'desa' => $siswa->desa,
                    'kecamatan' => $siswa->kecamatan,
                    'kode_pos' => $siswa->kode_pos,
                    'kabupaten' => $siswa->kabupaten,
                    'angkatan' => $siswa->angkatan,
                    'hp' => $siswa->hp ?? null,
                    'email' => $siswa->email ?? $siswa->nisn.'@sdn1-bedalisodo.sch.id',
                    'nik' => $siswa->nik ?? null,
                    'is_active' => $siswa->is_active ?? '1',
                    'status' => $siswa->status ?? 'aktif',
                    'foto_url' => $foto !== null ? $foto_url : ($siswa->foto_url ?? null),
                    'no_akta' => $siswa->no_akta ?? null,
                    'sekolah_asal' => $siswa->sekolah_asal ?? null,
                    'no_kk' => $siswa->no_kk ?? null
                ]
            );

            return $store;
        } catch(\Exception $e)
        {
            return $e;
        }
    }

    public function storeFoto($foto, $nisn) {
        try {
            
            $store = $foto->storePubliclyAs('images/siswas', $nisn.'.'.$foto->extension(), 's3');
            return Storage::disk('s3')->url($store);
        } catch(\Exception $e)
        {

        }
    }

    public function impor($siswas)
    {
        try {
            foreach ($siswas as $siswa)
            {
                Siswa::updateOrCreate(
                    [
                        'id' => $siswa->id ?? null,
                        
                    ],
                    [
                        'nisn' => $siswa->nisn ?? null,
                        'nis'  => $siswa->nis?? 'null',
                        'nama' => $siswa->nama,
                        'jk' => $siswa->jk,
                        'tempat_lahir'=> $siswa->tempat_lahir,
                        'tanggal_lahir' => $siswa->tanggal_lahir,
                        'agama' => $siswa->agama?? 'Islam',
                        'alamat' => $siswa->alamat,
                        'rt' => $siswa->rt,
                        'rw' => $siswa->rw,
                        'desa' => $siswa->desa?? 'Dalisodo',
                        'kecamatan' => $siswa->kecamatan?? 'Wagir',
                        'kode_pos' => $siswa->kode_pos?? '65158',
                        'kabupaten' => $siswa->kabupaten?? 'Malang',
                        'hp' => $siswa->hp?? '0893847475',
                        'email' => $siswa->email?? $siswa->nisn.'@sdn1-bedalisodo.sch.id',
                        'nik' => $siswa->nik?? null,
                        'is_active'=> $siswa->is_active?? '0',
                        'status' => $siswa->status?? 'aktif',
                        'foto_url' => null,
                        'no_akta' => $siswa->no_akta ?? null,
                        'sekolah_asal' => $siswa->sekolah_asal ?? null,
                        'no_kk' => $siswa->no_kk ?? null
                    ]
                    );
            }

            return ['status' => 'ok', 'msg' => 'Impor Siswa Selesai', 'response_code' => 200];
        } catch(\Exception $e)
        {
            return ['status' => 'fail', 'msg' => $e->getMessage(), 'response_code' => 500];
        }
    }
}