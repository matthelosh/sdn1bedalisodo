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

    public function impor(Request $request) {
        try {
            $siswas = json_decode($request->siswas);
            foreach($siswas as $siswa) {
                Siswa::updateOrCreate(
                    [
                        'id' => $siswa->id ?? null,
                        'nisn' => $siswa->nisn ?? null
                    ],
                    [
                        'nis'  => $siswa->nis?? 'null',
                        'nama' => $siswa->nama,
                        'jk' => $siswa->jk,
                        'tempat_lahir'=> $siswa->tempat_lahir,
                        'tanggal_lahir' => $siswa->tanggal_lahir,
                        'agama' => $siswa->agama?? 'Islam',
                        'alamat' => $siswa->alamat,
                        'rt' => $siswa->rt,
                        'rw' => $siswa->rw,
                        'desa' => $siswa->desa?? 'Dalisodo',
                        'kecamatan' => $siswa->kecamatan?? 'Wagir',
                        'kode_pos' => $siswa->kode_pos?? '65158',
                        'kabupaten' => $siswa->kabupaten?? 'Malang',
                        'hp' => $siswa->hp?? null,
                        'email' => $siswa->email?? null,
                        'nik' => $siswa->nik?? null,
                        'is_active'=> $siswa->is_active?? '0',
                        'status' => $siswa->status?? 'aktif'
                    ]
                    );
            }

            return response()->json([
                'status' => 'ok',
                'msg' => 'Impor Selesai'
            ], 200);
        } catch(\Exception $e) {
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
