<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;
use App\Models\Menu;

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

    public function menuIndex(Request $request) {
        try {
            $menus = Menu::all();
            return response()->json([
                'status' => 'ok',
                'menus' => $menus
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function menuStore(Request $request) {
        try {
            $data = json_decode($request->menu);
            $menu = Menu::updateOrCreate(
                [
                    'id' => $data->id ?? null
                ],
                [
                    'label' => $data->label,
                    'url' => $data->url,
                    'icon' => $data->icon,
                    'roles' => $data->roles,
                    'status' => $data->status ?? "1",
                    'parent_id' => $data->parent_id
                ]
            );
            return response()->json([
                'status' => 'ok',
                'menus' => $menu
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function menuDestroy(Request $request, $id) {
        $destroy = Menu::findOrFail($id)->delete();
        return response()->json([
            'status' => 'ok',
            'msg' => 'Menu dihapus'
        ], 200);
    }
}
