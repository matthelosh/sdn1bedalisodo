<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;

class PeraturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'status' => 'ok',
                'peraturans' => Peraturan::all()
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peraturan $peraturan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peraturan $peraturan)
    {
        //
    }
}
