<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'ok',
            'mapels' => Mapel::all()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                    'kurikulum' => $data->kurikulum
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
