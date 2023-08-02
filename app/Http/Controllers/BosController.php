<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bku;
use App\Models\Bukti;
use App\Models\KegiatanBos;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Storage;

class BosController extends Controller
{
    // Kuitansi Methods

    function getBku(Request $request) {
        try {
            $year = date('Y');
            if($request->query('bulan') && $request->query('bulan') !== 'all') {
                $bkus = Transaksi::whereMonth('tanggal', $request->query('bulan'))->whereYear('tanggal', $year)->with('buktis')->get();
            } else {
                $bkus = Transaksi::whereYear('tanggal', $year)->with('buktis')->get();
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
                'transaksis' => Transaksi::with('buktis')->get()
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function storeTransaksi(Request $request) {
        // dd($request->file('files'));
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
            
            if($request->file('files')) {
                foreach($request->file('files') as $file) {
                    $tipe = $file->extension() == 'pdf' ? 'dokumen' : 'foto';
                    $store = Storage::putFileAs('public/files/bos', $file, $file->getClientOriginalName());
                    Bukti::create([
                        'transaksi_id' => $transaksi->kode,
                        'label' => $file->getClientOriginalName(),
                        'url' => Storage::url($store),
                        'tipe' => $tipe,
                    ]);
                }
            }
            return response()->json([
                'status' => 'ok',
                'transaksi' => $transaksi
            ], 200);
        } catch(\Exception $e) {
            dd($e);
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function importTransaksi(Request $request) {
        try {
            $datas = json_decode($request->data);
            foreach($datas as $data) {
                Transaksi::create([
                    'tipe' => $data->tipe??'kredit',
                    'jenis' => $data->jenis,
                    'tanggal' => $data->tanggal,
                    'uraian' => $data->uraian,
                    'kode_kegiatan' => $data->kode_kegiatan,
                    'kode_rekening' => $data->kode_rekening,
                    'no_bukti' => $data->no_bukti,
                    'merchant' => $data->merchant??null,
                    'nilai' => $data->nilai??$data->kredit
                ]);
            }
            return response()->json([
                'status' => 'ok',
                'msg' => 'Impor Transaksi Selesai'
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function listKegiatan(Request $request) {
        try {
            $kegiatans = KegiatanBos::all();
            return response()->json([
                'status' => 'ok',
                'kegiatans' => $kegiatans
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

}
