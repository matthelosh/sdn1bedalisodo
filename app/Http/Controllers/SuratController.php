<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Surat;
use Illuminate\Http\Request;
use App\Models\KlasifikasiSurat;
use Illuminate\Support\Facades\Storage;

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
            $tahun = $request->query('tahun');
            $latest = Surat::whereYear('tanggal', $tahun)->orderBy('no_surat', 'DESC')->first();
            return response()->json([
                'status' => 'ok',
                'latest' => $latest,
            ], 200);
        } catch(\Throwable $th) {
            dd($th);
        }
    }

    public function store(Request $request)
    {
        try {
            // dd($request->data);
            $data  = json_decode($request->data);
            // dd($data);
            $surat = Surat::updateOrCreate(
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
                    'tembusan' => implode(",", $data->tembusan) ?? null,
                    'status' => $data->status ?? 'arsip',
                ]
            );

            if($request->file('file_arsip')) {
                $file  =$request->file('file_arsip');
                $jenis = $file->extension() == 'pdf' ? 'dokumen' : 'gambar';
                $store = $file->storePubliclyAs('surat/arsip', str_replace("/", "-", $request['surat_id']).".".$file->extension(), 's3');
                $url = Storage::disk('s3')->url($store);
                Arsip::create([
                    'surat_id' => $surat->kode,
                    'jenis' =>$jenis,
                    'url' => $url
                ]);
            }

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
