<?php

namespace App\Http\Controllers;

use App\Models\Atp;
use Illuminate\Http\Request;

class AtpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $atps = Atp::where('tingkat', $request->query('tingkat'))->where('mapel_id', $request->query('mapel_id'))->get();

        return response()->json(['status' => 'Ok', 'atps' => $atps], 200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Atp $atp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Atp $atp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Atp $atp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atp $atp)
    {
        //
    }
}
