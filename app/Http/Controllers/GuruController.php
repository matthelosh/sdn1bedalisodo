<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuruController extends Controller
{

    function page() {
        return Inertia::render('Auth/Guru', [
            'gurus' => Guru::whereNot("nama", "Administrator")->get(),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(!$request->query('q')) {
            $gurus = Guru::whereNot('role', 'admin')->get();
        } else {
            $gurus = Guru::whereNot('role', 'admin')->where('nama','LIKE','%'.$request->query('q').'%')->get();
        }

        return response()->json([
            'status' => 'ok',
            'gurus' => $gurus
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        //
    }
}
