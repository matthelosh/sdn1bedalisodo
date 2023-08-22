<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Services\SiswaService;

class SiswaController extends Controller
{
    function page(Request $request) {
        // return $request->user();
        return Inertia::render('Auth/Siswa', [
            'siswas' => Siswa::with('rombel')->get(),
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, SiswaService $siswa)
    {
        try {
            $data = json_decode($request->siswa);
            $foto = $request->file('foto') ?? null;
            // dd($foto);
            $store = $siswa->store($data, $foto);

            return response()->json([
                'status' => 'ok',
                'msg' => $store
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function impor(Request $request, SiswaService $siswaService) {
        try {
            $siswas = json_decode($request->siswas);

            $response = $siswaService->impor($siswas);
            return response()->json($response, $response['response_code']);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function updateFoto(Request $request, SiswaService $siswaService)
    {
        try {
            $siswa = Siswa::where('nisn', $request['nisn'])->first();
            // dd($siswa);
            $update = $siswa->update(['foto_url' => $siswaService->storeFoto($request->file('foto'), $request['nisn'])]);
            return response()->json([
                'status' => 'ok',
                'msg' => $update
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
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->rombel()->detach();
        $siswa->delete();

        return response()->json([
            'status' => 'ok',
            'msg' => 'Siswa dihapus'
        ], 200);
    }
}
