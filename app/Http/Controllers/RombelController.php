<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tapel;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    function page() {
        $tapel = Tapel::where("status","1")->first();
        return Inertia::render('Auth/Rombel', [
            'rombels' => Rombel::where("tapel", $tapel->kode)->with('guru', 'tapel', 'siswas')->get(),
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    function nonMember(Request $request) {
        $nonmembers = Siswa::where('is_active','1')->whereDoesntHave('rombel')->get();
        // dd($nonmembers);
        return response()->json([
            'status' => 'ok',
            'nonmembers' => $nonmembers
        ], 200);
    } 

    function masukkan(Request $request, $id) {
        $rombel = Rombel::find($id);
        $attach = $rombel->siswas()->attach([$request->siswa['id']]);
        return response()->json([
            'status' => 'ok',
            'msg' => $attach
        ], 200);
    }
    function keluarkan(Request $request, $id) {
        $rombel = Rombel::find($id);
        $detach = $rombel->siswas()->detach([$request->siswa['id']]);
        return response()->json([
            'status' => 'ok',
            'msg' => $detach
        ], 200);
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
    public function show(Rombel $rombel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rombel $rombel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rombel $rombel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rombel $rombel, $id)
    {
        try {
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
}
