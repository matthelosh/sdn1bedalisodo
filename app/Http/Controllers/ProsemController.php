<?php

namespace App\Http\Controllers;

use App\Models\Prosem;
use Illuminate\Http\Request;

class ProsemController extends Controller
{
    public function index(Request $request) {
        $prosems = Prosem::where('rombel_id', $request->query('rombel_id'))->where('mapel_id', $request->query('mapel_id'))->get();

        return response()->json(['status' => 'Ok', 'prosems' => $prosems], 200);
    }
}
