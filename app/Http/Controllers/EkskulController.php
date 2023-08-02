<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Siswa;
use App\Models\Tapel;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        return response()->json([
            'status' => 'ok',
            'ekskuls' => Ekskul::with('guru', 'pesertas')->get()
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
        $data = json_decode($request->ekskul);

        $store = Ekskul::updateOrCreate(
            [
                'id' => $data->id ?? null,
            ],
            [
                'kode' => $data->kode . '-'. $this->tapel()->kode,
                'label' => $data->label,
                'guru_id' => $data->guru_id,
                'keterangan' => $data->keterangan,
                'tapel_id' => $this->tapel()->kode
            ]
        );

        return response()->json([
            'status' => 'ok',
            'ekskuls' => $store
        ]);
    }


    function peserta(Request $request, $id) {
        try {
            $ekskul = Ekskul::find($id);
            $siswas = Siswa::whereDoesntHave('ekskuls', function($q) use($id) {
                $q->where('ekskuls.id', $id);
            })->with('rombel')->get();
            $pesertas = $ekskul->pesertas();

            return response()->json([
                'status' => 'ok',
                'siswas' => $siswas,
                'pesertas' => $pesertas
            ], 200);
        } catch(\Exception $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ekskul $ekskul)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ekskul $ekskul)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ekskul $ekskul)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ekskul $ekskul, $id)
    {
        $ekskul->destroy($id);
        return response()->json([
            'status' => 'ok',
            'msg' => 'Ekskul dihapus'
        ], 200);
    }


    function tapel() {
        return Tapel::where('status','1')->first();
    }
}
