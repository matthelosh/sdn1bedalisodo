<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Tapel;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 'ok',
            'ekskuls' => Ekskul::all()
        ], 200);
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
    public function destroy(Ekskul $ekskul)
    {
        //
    }


    function tapel() {
        return Tapel::where('status','1')->first();
    }
}
