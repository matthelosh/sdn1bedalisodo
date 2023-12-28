<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{

    function page() {
        return Inertia::render('Auth/Guru', [
            'gurus' => Guru::whereNot("nama", "Administrator")->with('user')->get(),
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
        try {
            $data = json_decode($request->guru);
            $guru = Guru::updateOrCreate(
                [
                    'id' => $data->id ?? null,
                ],
                [
                    'nip' => $data->nip,
                    'nama' => $data->nama,
                    'gelar_depan' => $data->gelar_depan ?? null,
                    'gelar_belakang' => $data->gelar_belakang ?? null,
                    'jk' => $data->jk,
                    'tempat_lahir' => $data->tempat_lahir,
                    'tanggal_lahir' => $data->tanggal_lahir,
                    'agama' => $data->agama,
                    'hp' => $data->hp,
                    'alamat' => $data->alamat,
                    'role' => $data->role ?? 'gkel',
                    'foto' => $data->foto ?? null,
                    'facebook' => $data->facebook?? null,
                    'youtube' => $data->youtube?? null,
                    'instagram' => $data->instagram?? null,
                    'status' => $data->status?? 'active',
                    'nickname' => $data->nickname,
                    'bio' => $data->bio ?? null,
                    'pangkat' => $data->pangkat ?? null
                ]
            );

            return response()->json([
                'status' => 'ok',
                'guru' => $guru
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }


    public function addAccount(Request $request) {
        try {
            $guru = Guru::where('id', $request->query('id'))->first();
            // dd($guru);
            $username = strtolower($guru->nickname);
            $user = User::create([
                'name' => $username,
                'email' => $username.'@sdn1-bedalisodo.sch.id',
                'email_verified_at' => date('y-m-d H:i:a'),
                'password' => Hash::make('12345'), // password
                'level' => $guru->role == 'admin' ? 'admin' : 'guru',
                'userable_id' => $request->query('id'),
                'userable_type' => 'App\Models\Guru'
            ]);
            // dd($user);
            return response()->json([
                'status' => 'ok',
                'guru' => $guru
            ], 200);
        } catch (\Exception $e) {
            // return response()->json([
            //     'status' => 'fail',
            //     'msg' => $e->getMessage()
            // ], 500);
            dd($e);
        }
    }

    function removeAccount(Request $request) {
        try {
            $delete = User::where('userable_id',$request->query('id' ))->delete();
            return response()->json([
                'status' => 'ok',
                'msg' => $delete
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
