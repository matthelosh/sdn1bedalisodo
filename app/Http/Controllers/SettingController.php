<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //

    public function tapel() {
        return response([
            'status' => 'ok',
            'tapels' => Tapel::all()
        ], 200);
    }

    public function toggleTapel(Request $request) {
        Tapel::where('status','1')->update(['status' => '0']);
        $toggle = Tapel::where('id', $request->tapel['id'])->update(['status' => '1']);

        return response([
            'status' => 'ok',
            'tapels' => $toggle
        ], 200);
    }

    public function storeTapel(Request $request) {
        try {
            $data = json_decode($request->tapel);
            $tapel = Tapel::updateOrCreate(
                [
                    'id' => $data->id ?? null,
                ],
                [
                    'kode' => $data->kode,
                    'label' => $data->label,
                    'status' => '0'
                ]
            );
            return response()->json([
                'status' => 'ok',
                'msg' => $tapel
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 500);
        }
    }
}
