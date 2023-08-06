<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use Illuminate\Http\Request;
use App\Models\Bku;
use App\Models\Bukti;
use App\Models\KegiatanBos;
use App\Models\Rkas;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Any;
use Illuminate\Support\Str;

class BosController extends Controller
{
    // Kuitansi Methods

    function anggaran() {
        return Anggaran::where('status', 'aktif')->first();
    }

    function getBku(Request $request) {
        try {
            $year = date('Y');
            if($request->query('bulan') && $request->query('bulan') !== 'all') {
                $bkus = Transaksi::whereMonth('tanggal', $request->query('bulan'))->whereYear('tanggal', $year)->where('anggaran_id', $this->anggaran()->kode)->with('buktis')->get();
            } else {
                $bkus = Transaksi::whereYear('tanggal', $year)->where('anggaran_id', $this->anggaran()->kode)->with('buktis')->get();
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
            $prevAnggaranId = Anggaran::where('id', '<', $this->anggaran()->id)->max('id');
            return response()->json([
                'status' => 'ok',
                'antris' => Rkas::where('status', 'antri')->get(),
                'transaksis' => Transaksi::where('anggaran_id', $this->anggaran()->kode)->with('buktis')->get(),

                'silpa' => Anggaran::where('id', $prevAnggaranId)->select('silpa')->first()
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
                    'id' => $data->id?? null,
                    'kode' => $data->kode ?? Str::random(16),
                ],
                [
                    'rkas_id' => $data->rkas_id,
                    'anggaran_id' => $this->anggaran()->kode,
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

            if($transaksi) {
                Rkas::where('id', $data->rkas_id)->update(['status' => 'selesai']);
            }

            return response()->json([
                'status' => 'ok',
                'transaksi' => $transaksi
            ], 200);
        } catch(\Exception $e) {
            // dd($e);
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

    function indexAnggaran(Request $request) {
        try {
            return response()->json([
                'status' => 'ok',
                'anggarans' => Anggaran::all()
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function storeAnggaran(Request $request) {
        try {
            $data = json_decode($request->anggaran);

            $anggaran = Anggaran::updateOrCreate(
                [
                    'id' => $data->id?? null,
                ],
                [
                    'kode' =>$data->kode,
                    'tahun_anggaran' =>$data->tahun_anggaran,
                    'sumber_dana' =>$data->sumber_dana,
                    'tahap' =>$data->tahap,
                    'uraian' =>$data->uraian,
                    'nilai' =>$data->nilai,
                    'silpa' => $data->silpa,
                    'keterangan' =>$data->keterangan,
                    'mulai' =>$data->mulai,
                    'selesai' =>$data->selesai,
                    'status' =>$data->status ?? 'nonaktif'
                ]
            );
            return response()->json([
                'status' => 'ok',
                'anggaran' => $anggaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function changeStatusAnggaran(Request $request, $id) {
        try {
            Anggaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
            $anggaran = Anggaran::where('id', $id)->update(['status' =>  $request->status]);
            return response()->json([
                'status' => 'ok',
                'anggaran' => $anggaran
            ], 200);
        } catch(\Exception $e) {
            dd($e);
        }
    }

    function deleteAnggaran(Request $request, $id) {
        try {
            $anggaran = Anggaran::where('id', $id)->delete();
            return response()->json([
                'status' => 'ok',
                'anggaran' => $anggaran
            ], 200);
        } catch(\Exception $e) {
            dd($e);
        }

    }

    function indexRkas(Request $request) {
        try {
            $rkas = Rkas::where('anggaran_id', $this->anggaran()->kode)->get();

            return response()->json([
                'status' => 'ok',
                'rkas' => $rkas,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function imporRkas(Request $request) {
        try {
            $datas = json_decode($request->datas);

            foreach($datas as $data) {
                Rkas::updateOrCreate(
                    [
                        'id' => $data->id?? null,
                    ],
                    [
                        'anggaran_id' => $this->anggaran()->kode,
                        'bulan' => $request->bulan,
                        'tahun' => $request->tahun,
                        'status' => $data->status ?? 'antri',
                        'kode_rekening'  => $data->kode_rekening,
                        'kode_kegiatan' => $data->kode_kegiatan,
                        'uraian' => $data->uraian,
                        'volume' => $data->volume,
                        'satuan' => $data->satuan,
                        'tarif' => $data->tarif,
                        'jumlah' => $data->jumlah
                    ]
                );
            }
            return response()->json([
                'status' => 'ok',
                'msg' => 'RKAS diimpor'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function changeStatusRkas(Request $request, $id) {
        try {
            $rkas = Rkas::where('id', $id)->update(['status' => $request->status]);
            return response()->json([
                'status' => 'ok',
                'msg' => 'Status RKAS: '.$rkas.' diperbarui',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

}
