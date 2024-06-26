<?php

namespace App\Http\Controllers;

use App\Models\GrupWa;
use App\Models\Guru;
use Inertia\Inertia;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RombelController extends Controller
{
    function page(Request $request) {
        try {
        $tapel = Tapel::where("status","1")->first();
        $guru = Guru::where('id', $request->user()->userable_id)->first();
        $rombels = $request->user()->level == 'admin' 
                    ? Rombel::where("tapel", $tapel->kode)->with('guru', 'tapel', 'siswas', 'mapels', 'grupwa')->get() 
                    :  Rombel::where("tapel", $tapel->kode)->where('guru_id', $guru->nip)->with('guru', 'tapel', 'siswas')->get();

        
        return Inertia::render('Auth/Rombel', [
            'rombels' => $rombels,
        ]);
        } catch(\Exception $e) {
            dd($e);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->tapel);
        try {
            switch(auth()->user()->level)
            {
                case "admin":
                    if ($request->tingkat && $request->tapel) {
                        $rombels = Rombel::where('tapel', $request->tapel)->where('tingkat', $request->tingkat)->with('siswas')->get();
                    } else {
                        $rombels = Rombel::all();
                    }
                    break;
                case "guru":
                    if (auth()->user()->userable->role == 'gkel') {
                        $rombels = Rombel::where('guru_id', auth()->user()->userable->nip)->where('tapel', $this->tapel()->kode)->get();
                    } else {
                        $rombels = Rombel::where('tapel', $this->tapel()->kode)->get();
                    }
            }

            return response()->json(['status' => 'ok', 'rombels' => $rombels], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function nonMember(Request $request) {
        $nonmembers = Siswa::where('is_active','0')->whereDoesntHave('rombel')->get();
        // dd($nonmembers);
        return response()->json([
            'status' => 'ok',
            'nonmembers' => $nonmembers
        ], 200);
    } 

    public function imporMember(Request $request, $id) {
        try {
            $rombel = Rombel::find($id);
            $datas = json_decode($request->siswas);

            foreach($datas as $data) {
                $siswa = Siswa::where('nisn', $data->nisn)->first();
                $rombel->siswas()->attach([$siswa->id]);
            }

            return response()->json([
                'status' => 'ok',
                'msg' => 'Siswa dimasukkan ke dalam rombel'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    function masukkan(Request $request, $id) {
        $rombel = Rombel::find($id);
        $attach = $rombel->siswas()->attach([$request->siswa['id']]);
        return response()->json([
            'status' => 'ok',
            'msg' => $attach
        ], 200);
    }

    function keluarkan(Request $request, $id, $siswa_id) {
      
      try {
      $rombel = Rombel::find($id);
        $detach = DB::table('rombel_siswa')->where('siswa_id', $siswa_id)->delete();
        //$detach = $rombel->siswas()->detach([$siswa_id]);
        return response()->json([
            'status' => 'ok',
            'msg' => $detach
        ], 200);
      } catch(\Exception $e) {
        dd($e);
      }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = json_decode($request->rombel);
            
            $rombel = Rombel::updateOrCreate(
                [
                    'id' => $data->id ?? null
                ],
                [
                    'tapel' => $data->tapel,
                    "kode"  => $data->kode,
                    "label"  => $data->label,
                    "kurikulum"  => $data->kurikulum,
                    "tingkat" => $data->tingkat,
                    "guru_id"  => $data->guru_id
                ]
            );

            if(isset($data->grupWa)) {
                $grup = GrupWa::where('chat_id', $data->grupWa)->update(['rombel_id' => $rombel->id]);
            }

            return response()->json([
                'status' => 'ok',
                'msg' => $rombel
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function detachMapel(Request $request, $id) {
        try {
            $rombel = Rombel::find($id);
            $detach = $rombel->mapels()->detach([$request->mapel_id]);
            // $detach = DB::table('mapel_rombel')->where('mapel_id', $request->mapel_id)->where('rombel_id', $id)->delete();
            return response()->json([
                'status' => 'ok',
                'msg' => $detach
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }    
    }
    public function attachMapel(Request $request, $id) {
        try {
            $rombel = Rombel::find($id);
            $rombel->mapels()->attach($request->mapel_id);
            return response()->json([
                'status' => 'ok',
                'msg' => $rombel
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }    
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rombel $rombel, $id)
    {
        try {
            $grupwa = GrupWa::where('rombel_id', $id)->first();
            $grupwa->update(['rombel_id' => null]);
            $rombel->find($id)->delete();
            return response()->json([
                'status' => 'ok',
                'msg' => 'Rombel dihapus'
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e
            ], 500);
        }
    }

    function tapel() {
        return Tapel::where('status', '1')->first();
    }
}
