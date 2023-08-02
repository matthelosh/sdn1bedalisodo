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
}
