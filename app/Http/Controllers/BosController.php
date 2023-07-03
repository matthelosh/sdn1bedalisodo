<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bku;
use App\Models\Transaksi;

class BosController extends Controller
{
    // Kuitansi Methods

    function getBku(Request $request) {
        try {
            if($request->query('bulan') && $request->query('bulan') !== 'all') {
                $bkus = Bku::whereMonth('tanggal', $request->query('bulan'))->get();
            } else {
                $bkus = Bku::all();
            }
            return response()->json([
                'status' => 'ok',
                'bkus' => $bkus
            ], 200);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function transaksi(Request $request) {
        try {
            return response()->json([
                'status' => 'ok',
                'transaksis' => Transaksi::all()
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function storeTransaksi(Request $request) {
        try {
            $data = json_decode($request->transaksi);
            $transaksi = Transaksi::updateOrCreate(
                [
                    'id' => $data->id?? null
                ],
                [
                    'tipe' => $data->tipe??'kredit',
                    'jenis' => $data->jenis,
                    'tanggal' => $data->tanggal,
                    'uraian' => $data->uraian,
                    'kode_kegiatan' => $data->kode_kegiatan,
                    'kode_rekening' => $data->kode_rekening,
                    'no_bukti' => $data->no_bukti,
                    'merchant' => $data->merchant,
                    'nilai' => $data->nilai
                ]
            );
            return response()->json([
                'status' => 'ok',
                'transaksi' => $transaksi
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

}
