<?php

namespace App\Http\Controllers;

use App\Models\Amal;
use App\Models\MutasiAmal;
use Illuminate\Http\Request;

class AmalController extends Controller
{
    public function index(Request $request) 
    {
        try {
            $amal = Amal::first();
            if (!$amal) {
                Amal::create(['saldo' => 0]);
            }
            $mutasis = MutasiAmal::all();

            return response()->json([
                'mutasis' => $mutasis,
                'saldo' => $amal->saldo,
            ], 200);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeMutasi(Request $request) {
        try {
            $amal = Amal::first();
            if (!$amal) {
                Amal::create(['saldo' => 0]);
            }
            $saldo = $amal->saldo;
            $store = MutasiAmal::updateOrCreate(
                [
                    'id' => $request['id'] ?? null,
                ],
                [
                    'tanggal' => $request['tanggal'],
                    'jenis' => $request['jenis'],
                    'nilai' => $request['nilai'],
                    'keterangan' => $request['keterangan']
                ]
            );
            if ( $store ) {
                if ($request['jenis'] == 'masuk') {
                    $amal->update(['saldo' => $saldo + $request['nilai']]);
                } else {
                    $amal->update(['saldo' => $saldo - $request['nilai']]);
                }
            }

            return response()->json([
                'status' => 'ok',
                'msg' => 'Mutasi disimpan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMEssage()
            ], 500);
        }
    }
}
