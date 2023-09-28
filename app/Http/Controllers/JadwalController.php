<?php

namespace App\Http\Controllers;

use App\Http\Requests\JadwalRequest;
use App\Models\Jadwal;
use App\Services\JadwalService;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(JadwalService $jadwal)
    {
        $jadwals = $jadwal->index();
        return response()->json(['status' => 'ok', 'jadwals' => $jadwals], 200);
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
    public function store(JadwalRequest $request, JadwalService $jadwal)
    {
        $store = $jadwal->store($request->all());
        return back()->with('status', $store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JadwalRequest $request, JadwalService $jadwal)
    {
        $update = $jadwal->update($request->all());
        return back()->with('status', $update);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jadwal $jadwal)
    {
        //
    }
}
