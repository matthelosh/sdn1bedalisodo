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
    public function index(Request $request)
    {
        try {
            $cps = Cp::where('tingkat', $request->query('tingkat'))->where('mapel_id', $request->query('mapel_id'))->get();
            return response()->json(['status' => 'Ok', 'cps' => $cps], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
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
        try {
            $store = Cp::updateOrCreate(
                [
                    'id' => $request['id'] ?? null,
                    'kode' => $request['kode']
                ],
                [
                    'fase' => $request['fase'],
                    'tingkat' => $request['tingkat'],
                    'elemen' => $request['elemen'],
                    'teks' => $request['teks'],
                    'mapel_id' => $request['mapel_id']
                ]
            );
            return response()->json(['status' => 'Ok', 'cp' => $store], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
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
    public function destroy(Cp $cp, $id)
    {
        $item = $cp::findOrFail($id);
        $destroy = $item->delete();

        return response()->json(['status' => 'Ok', 'msg' => $destroy], 200);
    }
}
