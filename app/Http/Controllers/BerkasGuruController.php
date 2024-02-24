<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Inertia\Inertia;
use App\Models\BerkasGuru;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BerkasGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $nip = $request->query('nip');
            $guru = Guru::where('nip',$nip)->with('arsips')->first();
            // dd($guru);
            return Inertia::render('Auth/BerkasGuru', [
                'guru' => $guru
            ]);
        } catch (\Throwable $th) {
            dd($th);
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = json_decode($request->data);
            $file = $request->file('dokumen');
            // dd($file);
            $store = $file->storePubliclyAs('guru/arsip', auth()->user()->userable->nip.'-'.Str::random(16).'.'.$file->extension(), 's3');
            // dd($store);

            if ($store) {
                BerkasGuru::updateOrCreate(
                    [
                        'id' => $data->id ?? null,
                    ],
                    [
                        'guru_id' => $request->query('guru_id'),
                        'tipe' => $data->tipe,
                        // 'kode' => Str::random(5),
                        'label' => $data->label,
                        'deskripsi' => $data->deskripsi ?? null,
                        'keterangan' => $data->keterangan ?? null,
                        'url' => Storage::disk('s3')->url($store)
                    ]
                    );
            }

            return back()->with('message', 'Arsip Guru disimpan');
            // return redirect('arsip.index')->with('message', 'Arsip disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BerkasGuru $berkasGuru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $arsip = BerkasGuru::findOrFail($id);
            Storage::disk('s3')->delete($arsip->url);
            $arsip->delete();

            return back()->with('message', 'Arsip dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
