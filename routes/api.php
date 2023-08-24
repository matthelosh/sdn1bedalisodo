<?php

use App\Http\Controllers\BosController;
use App\Http\Controllers\WaController;
use App\Models\Sekolah;
use App\Models\Tapel;
use App\Models\Rombel;
use App\Models\Siswa;
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

Route::get("/sekolah", [WaController::class, 'replyDataSekolah'])->middleware('isLegal');

Route::get('/siswa', [WaController::class, 'replyDataSiswa'])->middleware('isLegal');
Route::get('/guru', [WaController::class, 'replyDataGuru'])->middleware('isLegal');
Route::get('/agenda', [WaController::class, 'replyDataAgenda'])->middleware('isLegal');

Route::prefix("bos")->group(function() {
    Route::get('/rkas', [BosController::class, 'replyRkas']);
})->middleware('isLegal');