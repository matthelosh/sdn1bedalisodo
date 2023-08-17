<?php

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/sekolah", function(Request $request) {
    $token = $request->header('token');
    // return response()->json(['status' => $token]);
    $key = Hash::make('mandita');
    if(Hash::check($token, $key)) {
        $sekolah = Sekolah::first();
        return response()->json(['status' => "OK", "sekolah" => $sekolah], 200);
    } else {
        return response()->json(['status' => 'Unauthorized'], 403);
    }
    
});