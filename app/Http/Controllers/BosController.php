<?php

namespace App\Http\Controllers;

use App\Models\Bku;
use App\Models\Rkas;
use App\Models\Bukti;
use App\Models\Anggaran;
use Mockery\Matcher\Any;
use App\Models\Transaksi;
use App\Models\KegiatanBos;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class BosController extends Controller
{
    // Kuitansi Methods

    function anggaran($kode) {
        return Anggaran::where('kode', $kode)->first();
    }

    function getBku(Request $request) {
        try {
            $year = substr($request->query('anggaran_id'), 2, 4);
            if($request->query('bulan') && $request->query('bulan') !== 'all') {
                $bkus = Transaksi::where('anggaran_id', $request->query('anggaran_id'))->whereMonth('tanggal', $request->query('bulan'))->whereYear('tanggal', $year)->with('buktis')->get();
            } else {
                $bkus = Transaksi::where('anggaran_id', $request->query('anggaran_id'))->whereYear('tanggal', $year)->with('buktis')->get();
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
        // dd($request->query('anggaran_id'));
        $anggaran_id = $request->query('anggaran_id');
        $anggaran_like = substr($anggaran_id,0,-1);
        try {
            $prevAnggaranId = Anggaran::where('sumber_dana', $this->anggaran($request->query('anggaran_id'))->sumber_dana)->where('id', '<', $this->anggaran($request->query('anggaran_id'))->id)->max('id');
            return response()->json([
                'status' => 'ok',
                'antris' => Rkas::where('anggaran_id', 'LIKE', $anggaran_like.'%')->where('status', 'antri')->get(),
                'transaksis' => Transaksi::where('anggaran_id', $request->query('anggaran_id'))->with('buktis')->get(),
                'selesais' => Rkas::where('anggaran_id', $anggaran_id)->where('status', 'selesai')->get(),
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
                    'anggaran_id' => $this->anggaran($request->query('anggaran_id'))->kode,
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
                try {
                foreach($request->file('files') as $file) {
                    $tipe = $file->extension() == 'pdf' ? 'dokumen' : 'foto';
                    // $store = Storage::putFileAs('public/files/bos', $file, $file->getClientOriginalName());
                    if ($tipe == 'foto') {
                        $file = ImageManager::imagick()->read($file);
                        $file->resize(300, null);
                        $file = $file->stream();
                    }
                    // $store = $file->storePubliclyAs('bos/file/bukti', $file->getClientOriginalName(), 's3');
                    $store = Storage::disk('s3')->putFileAs('bos/file/bukti', $file->getCLientOriginalName());
                    Bukti::create([
                        'transaksi_id' => $transaksi->kode,
                        'label' => $file->getClientOriginalName(),
                        'url' => Storage::disk('s3')->url($store),
                        'tipe' => $tipe,
                    ]);
                }
                } catch(\Throwable $th) {
                    dd($th);
                }
            }

            if($transaksi) {
                Rkas::where('id', $data->rkas_id)->update(['status' => 'selesai']);
                $anggaran = Anggaran::where('id', $this->anggaran($request->query('anggaran_id'))->id)->update(['silpa' => ($this->anggaran($request->query('anggaran_id'))->silpa - $transaksi->nilai)]);
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
            $res = '';
            foreach($datas as $data) {
                Transaksi::create([
                    
                    'kode' => $data->kode ?? Str::random(16),
                    'rkas_id' => $this->getRkasId($data),
                    'anggaran_id' => $request->query('anggaran_id'),
                    'tipe' => $data->tipe??'kredit',
                    'jenis' => $data->jenis ?? 'tunai',
                    'tanggal' => $data->tanggal,
                    'uraian' => $data->uraian,
                    'kode_kegiatan' => $data->kode_kegiatan,
                    'kode_rekening' => $data->kode_rekening,
                    'no_bukti' => $data->no_bukti ?? null,
                    'merchant' => $data->merchant?? $this->anggaran($request->query('anggaran_id'))->sumber_dana,
                    'nilai' => $data->nilai??$data->kredit
                ]);
                Rkas::where('id',$this->getRkasId($data))->update(['status' => 'selesai']);
                $anggaran = Anggaran::where('id', $this->anggaran($request->query('anggaran_id'))->id)->update(['silpa' => ($this->anggaran($request->query('anggaran_id'))->silpa - $data->nilai)]);
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
            $anggaran = Anggaran::where('id', $id)->first();
            Anggaran::where('status', 'aktif')->where('kode','LIKE', substr($anggaran->kode, 0, 2).'%')->update(['status' => 'nonaktif']);
            $anggaran->update(['status' =>  $request->status]);
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
                        'anggaran_id' => $this->anggaran($request->query('anggaran_id'))->kode,
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

    public function deleteBukti(Request $request, $id) {
        $bukti = Bukti::findOrFail($id);
        Storage::disk('s3')->delete($bukti->url);
        $bukti->delete();

        return response()->json(['status' => 'ok', 'message' => 'Bukti Dihapus'], 200);
    }

}
