<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    //

    public function imporKlasifikasiSurat(Request $request) {
        try {
            $datas = json_decode($request->datas);
            foreach($datas as $data) {
                KlasifikasiSurat::updateOrCreate(
                    [
                        'id' => $data->id ?? null,
                    ],
                    [
                        'kode' => $data->kode,
                        'uraian' => $data->uraian
                    ]
                    );
            }

            return response()->json([
                'status' => 'ok',
                'msg' => 'Klasifikasi Surat diimpor'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ], 500);
        }
    }
}