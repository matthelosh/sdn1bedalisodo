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
                $anggaran = Anggaran::where('id', $this->anggaran()->id)->update(['silpa' => ($this->anggaran()->silpa - $transaksi->nilai)]);
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
            $res = '';
            foreach($datas as $data) {
                Transaksi::create([
                    
                    'kode' => $data->kode ?? Str::random(16),
                    'rkas_id' => $this->getRkasId($data),
                    'anggaran_id' => $this->anggaran()->kode,
                    'tipe' => $data->tipe??'kredit',
                    'jenis' => $data->jenis ?? 'tunai',
                    'tanggal' => $data->tanggal,
                    'uraian' => $data->uraian,
                    'kode_kegiatan' => $data->kode_kegiatan,
                    'kode_rekening' => $data->kode_rekening,
                    'no_bukti' => $data->no_bukti ?? null,
                    'merchant' => $data->merchant??'BOSREG',
                    'nilai' => $data->nilai??$data->kredit
                ]);
                Rkas::where('id',$this->getRkasId($data))->update(['status' => 'selesai']);
                $anggaran = Anggaran::where('id', $this->anggaran()->id)->update(['silpa' => ($this->anggaran()->silpa - $data->nilai)]);
            }
            return response()->json([
                'status' => 'ok',
                'msg' => $res,
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
            // dd($e);
        }
    }

    function getRkasId($data) {
        $rkas = Rkas::where('kode_kegiatan', $data->kode_kegiatan)
                        ->where('kode_rekening', $data->kode_rekening)
                        ->where('uraian', $data->uraian)
                        ->where('jumlah', $data->nilai)
                        ->first();
        return $rkas ? $rkas->id : null;
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


    function replyRkas(Request $request) {
        try {
            $res = "Data Anggaran BOS:\n";
            $q = $request->query("q");
            $rkas = Rkas::all();

            $jml = $rkas->count();
            $selesai = $rkas->filter(function($rka) use($q) {
                return $rka->status == 'selesai';
            });
            $antri = $rkas->filter(function($rka) use($q) {
                return $rka->status == 'antri';
            });

            $bulan = $rkas->filter(function($rka) use ($q) {
                return $rka->bulan == substr($q, 5);
            });

            $tahap = $rkas->filter(function($rka) use ($q) {
                return substr($rka->anggaran_id, -1) == substr($q, -1);
            });

            $datas = $q == 'tersedia' ? $antri : ($q == 'selesai' ? $selesai : (preg_match("/^bulan/i", $q) ? $bulan : (preg_match("/^tahap/i", $q) ? $tahap : $rkas)));

            
            $res .= "
Jumlah Anggaran: $jml,
Selesai: ".count($selesai).",
Antri: ".count($antri).",
Prosentase:".ceil((count($selesai) / $jml * 100))."%
========================
            ";


            

            foreach($datas as $rka)
            {
                $res .= "
Bulan: $rka->bulan,
Kegiatan: $rka->uraian,
Status: $rka->status,
Nilai: Rp. $rka->jumlah
=======================
                ";
            }

            return response()->json(['status' => 'ok', 'rkas' => $res]);
        } catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    function indexRkas(Request $request) {
        try {
            $rkas = Rkas::all();

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
