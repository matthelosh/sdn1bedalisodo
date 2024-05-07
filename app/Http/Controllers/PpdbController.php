<?php

namespace App\Http\Controllers;

use App\Http\Requests\PpdbRequest;
use App\Models\Ppdb;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PpdbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Ppdb', [
            'data' => 'Selamat Datang',
        ]);
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
        // dd($request->all());
        $casis = json_decode($request->casis);
        // dd($casis);
        try {
            $ppdb = Ppdb::updateOrCreate(
                [
                    'kode' => $casis->kode ?? Str::random(16),
                    'nik' => $casis->nik,
                ],
                [
                    'tapel' => '2425',
                    'nisn' => $casis->nisn ?? null,
                    'nama' => $casis->nama,
                    'jk' => $casis->jk,
                    'tempat_lahir' => $casis->tempat_lahir,
                    'tanggal_lahir' => $casis->tanggal_lahir,
                    'alamat' => $casis->alamat,
                    'rt' => $casis->rt ?? null,
                    'rw' => $casis->rw ?? null,
                    'desa' => $casis->desa,
                    'kecamatan' => $casis->kecamatan ?? 'Wagir',
                    'kabupaten' => $casis->kabupaten ?? 'Malang',
                    'kode_pos' => $casis->kode_pos ?? '65158',
                    'hp' => $casis->hp ?? null,
                    'email' => $casis->email ?? null,
                    'asal_tk' => $casis->asal_tk ?? null,
                    'nama_ayah' => $casis->nama_ayah,
                    'nik_ayah' => $casis->nik_ayah ?? null,
                    'nama_ibu' => $casis->nama_ibu,
                    'nik_ibu' => $casis->nik_ibu ?? null,
                    'nama_wali' => $casis->nama_wali ?? null,
                    'nik_wali' => $casis->nik_wali ?? null,
                    'hubungan_wali' => $casis->hubungan_wali ?? null
                ]
            );
            return back()->with('ppdb', $ppdb);
        } catch (\Throwable $th)
        {
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ppdb $ppdb)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ppdb $ppdb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ppdb $ppdb)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ppdb $ppdb)
    {
        //
    }
}
