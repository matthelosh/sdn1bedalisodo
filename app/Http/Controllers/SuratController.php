<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use Illuminate\Http\Request;
use App\Models\Surat;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        try {
            // dd('halo');
            $surats = Surat::orderBy('no_surat', 'DESC')->with('arsip')->get();

            return response()->json([
                'status' => 'ok',
                'surats' => $surats
            ], 200);
        } catch(\Throwable $th) {
            dd($th);
        }
    }

    public function last(Request $request)  
    {
        // dd('halo');
        try {
            $klasifikasi = $request->query('klasifikasi');
            $lastCategory = Surat::whereKlasifikasiId($klasifikasi)->orderBy('no_surat', 'DESC')->first();
            $lastAll = Surat::latest()->first();
            return response()->json([
                'status' => 'ok',
                'latest' => $lastCategory,
                'latestAll' => $lastAll,
            ], 200);
        } catch(\Throwable $th) {
            dd($th);
        }
    }

    public function store(Request $request)
    {
        try {
            $data  = json_decode($request->data);
            // dd($data);
            Surat::updateOrCreate(
                [
                    'id' => $data->id ?? null
                ],
                [
                    'klasifikasi_id' => $data->klasifikasi_id,
                    'no_surat' => $data->no_surat,
                    'kode' => $data->kode,
                    'kategori' => $data->kategori,
                    'penerima' => $data->penerima,
                    'perihal' => $data->perihal,
                    'tanggal' => $data->tanggal,
                    'tembusan' => $data->tembusan,
                    'status' => $data->status,
                ]
            );

            return response()->json([
                'status' => 'ok',
                'message' => 'Surat disimpan'
            ], 200);
        } catch(\Throwable $th) {
            dd($th);
        }
    }

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


    function indexKlasifikasiSurat(Request $request) {
        $q = $request->query('q');
        try {
            if($q == null) {
                $klasifikasis = KlasifikasiSurat::all();
            } 
            else {
                $klasifikasis = KlasifikasiSurat::where('uraian', 'LIKE', '%'.$q.'%')->get();
            }

            // dd($klasifikasis);
            return response()->json([
                'status' => 'ok',
                'klasifikasis' => $klasifikasis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }
}
