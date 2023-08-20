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
                    'nisn' => $siswa->nisn,
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
                    'email' => $siswa->email ?? null,
                    'nik' => $siswa->nik ?? null,
                    'is_active' => $siswa->is_active ?? '1',
                    'status' => $siswa->status ?? 'aktif',
                    'foto_url' => $foto !== null ? $foto_url : ($siswa->foto_url ?? null)
                ]
            );

            return $store;
        } catch(\Exception $e)
        {
            dd($e);
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
}