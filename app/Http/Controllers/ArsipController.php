<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
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
    public function store(Request $request)
    {
        if($request->query('return_type')) {
            dd($request->query('return_type'));
        }
        try {
            $file  =$request->file('file_arsip');
            $jenis = $file->extension() == 'pdf' ? 'dokumen' : 'gambar';
            $store = $file->storePubliclyAs('surat/arsip', str_replace("/", "-", $request['surat_id']).".".$file->extension(), 's3');
            $url = Storage::disk('s3')->url($store);
            Arsip::create([
                'surat_id' => $request['surat_id'],
                'jenis' =>$jenis,
                'url' => $url
            ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Arsip ditambahkan'
            ], 200);

            
        } catch(\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Arsip $arsip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arsip $arsip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Arsip $arsip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Arsip $arsip)
    {
        //
    }
}
