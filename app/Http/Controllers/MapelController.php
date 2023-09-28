<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Rombel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        try {
            switch(auth()->user()->level)
            {
                case "admin":
                    $mapels = Mapel::all();
                    break;
                case "guru":
                    if (auth()->user()->userable->role == 'gkel') {
                        $rombel = Rombel::where('guru_id', auth()->user()->userable->nip)->where('tapel', $this->tapel()->kode)->first();
                        $mapels = $rombel->mapel();
                    } else {
                        $mapels = Mapel::where('kode', 'LIKE', substr(auth()->user()->userable->role,1))->get();
                    }
            }

            return response()->json(['status' => 'ok', 'mapels' => $mapels], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function rombelExcluded(Request $request)
    {
        // $kurikulum = strtolower(str_replace("-","",$request->kurikulum));
        $rombel_id = $request->rombel_id;
        $excluded = Mapel::whereDoesntHave('rombel', function($q) use ($rombel_id) {
            $q->where('rombels.id', $rombel_id);
        })->get();
        // dd($nonmembers);
        return response()->json([
            'status' => 'ok',
            'mapels' => $excluded
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = json_decode($request->mapel);
            $mapel = Mapel::updateOrCreate(
                [
                    'id' => $data->id ?? null,
                ],
                [
                    'kode' => $data->kode,
                    'label' => $data->label,
                    'tingkat' => $data->tingkat,
                    'kurikulum' => $data->kurikulum,
                    'kelompok' => $data->kelompok,
                    'kategori' => $data->kategori
                ]
            );
            return response()->json([
                'status' => 'ok',
                'msg' => $mapel
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mapel $mapel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mapel $mapel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mapel $mapel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mapel $mapel, $id)
    {
        $mapel = Mapel::where('id', $id)->first();
        $mapel->rombel()->detach();
        $delete = $mapel->delete();

        return response()->json([
            'status' => 'ok',
            'msg' => $delete
        ], 200);
    }
}
