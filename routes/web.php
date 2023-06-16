<?php
namespace App\Http\Controllers;

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });





Route::prefix('dashboard')->group(function() {
    Route::get('/', function() {
        return Inertia::render('Auth/Dashboard');
    })->name('dashboard')->middleware('is_auth');
    Route::prefix("post")->group(function() {
        Route::get('/', [PostController::class, 'index'])->name('dashboard.post');
        Route::post('/store', [PostController::class, 'store'])->name('post.store');
        // Route::get('/write', [PostController::class, 'write'])->name('post.write');
        // Route::post('/list-image', function(Request $request) {
        //     $directory = 'images';
        //     $files = Storage::allFiles('public/images');
        //     return response()->json([
        //         'status' => 'ok',
        //         'images' => $files
        //     ], 200);
        // })->name('images.list');
    });
    Route::prefix('images')->group(function() {
        Route::post('/upload', [ImageController::class, 'store'])->name('image.upload');
        Route::post('/list', [ImageController::class, 'list'])->name('images.list');
    });
    Route::prefix('category')->group(function() {
        Route::post('/', [CategoryController::class, 'index'])->name('category.index');
    });
    Route::prefix('profile')->group(function() {
        Route::get('/', function() {
            return 'Profil';
        })->name('profile');
    });
})->middleware('is_auth');


require __DIR__.'/auth.php';

Route::prefix("/")->group(function() {

    // Tes IDC Storage
    Route::get('/s3', function() {
        $files = Storage::disk('s3')->files('images');
        return $files;
    });

    Route::get('/', [FrontPageController::class, 'index'])->name('Welcome');
    Route::get('/posts', [FrontPageController::class, 'index'])->name('Post.index');
    Route::get('/about', [FrontPageController::class, 'index'])->name('About');

    Route::get('/{kategori}/{slug}', [FrontPageController::class, 'readPost'])->name('Post.read');
    Route::prefix("post")->group(function() {
        Route::post('/upload-image', function(Request $request) {
            dd($request->all());
        })->name('post.upload.image');
    });

   
    Route::get('/search', [FrontPageController::class, 'search'])->name('Search');

    Route::prefix('install')->group(function() {
        Route::inertia('/', 'Install')->name('site.install');
    });
})->middleware(['guest','is_configured']);

