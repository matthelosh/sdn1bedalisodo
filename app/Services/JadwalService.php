<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Tapel;

class JadwalService
{

    public function index() {
        try {
            if (auth()->user()->level == 'admin') {
                return Jadwal::where('tapel', $this->tapel()->kode)->with('rombel', 'mapel')->get();
            } else {
                return Jadwal::where('tapel', $this->tapel()->kode)->where('guru_id', auth()->user()->userable->nip)->with('rombel', 'mapel')->get();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store($request) {
        try {
            $jadwal = new Jadwal($request);
            $jadwal->save();

            return "Data Jadwal Berhasil disimpan";
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($request) {
        $jadwal = Jadwal::where('id', $request['id'])->first();
        $jadwal->guru_id = $request['guru_id'];
        $jadwal->tapel = $request['tapel'];
        $jadwal->mapel_id = $request['mapel_id'];
        $jadwal->rombel_id = $request['rombel_id'];
        $jadwal->jamke = $request['jamke'];
        $jadwal->jml_jam = $request['jml_jam'];
        $jadwal->hari = $request['hari'];

        $jadwal->save();

        return "Jadwal berhasil diupdate";
    }

    function tapel() {
        return Tapel::where('status','1')->first();
    }
}