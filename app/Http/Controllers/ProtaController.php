<?php

namespace App\Http\Controllers;

use App\Models\Prota;
use Illuminate\Http\Request;

class ProtaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $protas = Prota::where('rombel_id', $request->query('rombel_id'))->where('mapel_id', $request->query('mapel_id'))->with('atp','materi')->get();

        return response()->json(['status' => "Ok", 'protas' => $protas], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $prota = new Prota($request->all());
            $prota->guru_id = auth()->user()->userable->nip;
            $prota->save();
    
            return response()->json(['status' => 'Ok', 'prota' => $prota], 200);    //code...
        } catch (\Exception $th) {
            dd($th);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Prota $prota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prota $prota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prota $prota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prota $prota)
    {
        //
    }
}
