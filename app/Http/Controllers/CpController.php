<?php

namespace App\Http\Controllers;

use App\Models\Cp;
use App\Services\CpService;
use Illuminate\Http\Request;

class CpController extends Controller
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
    public function impor(Request $request, CpService $cp)
    {
        try {
            $datas = json_decode($request->cps);
            $count = 0;
            foreach($datas as $data)
            {
                $store = $cp->store($data);
                if ($store) { $count++; }
            }

            return response()->json([
                'status' => 'ok',
                'msg' => $count.' CP disimpan'
            ], 200);
        } catch(\Exception $e)
        {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
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
     * Display the specified resource.
     */
    public function show(Cp $cp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cp $cp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cp $cp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cp $cp)
    {
        //
    }
}
