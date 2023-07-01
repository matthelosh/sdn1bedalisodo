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
    });
    Route::prefix('images')->group(function() {
        Route::post('/upload', [ImageController::class, 'store'])->name('image.upload');
        Route::post('/list', [ImageController::class, 'list'])->name('images.list');
    });
    Route::prefix('category')->group(function() {
        Route::post('/', [CategoryController::class, 'index'])->name('category.index');
    });

    Route::prefix("guru")->group(function() {
        Route::get("/", [GuruController::class, 'page'])->name('dashboard.guru.page');
    });
    Route::prefix("rombel")->group(function() {
        Route::get("/", [RombelController::class, 'page'])->name('dashboard.rombel.page');
        Route::post('/nonmember', [RombelController::class, 'nonMember'])->name('dashboard.rombel.nonmember');
        Route::delete('/{id}', [RombelController::class, 'destroy'])->name('dashboard.rombel.destroy');
        Route::post('/{id}/masukkan', [RombelController::class, 'masukkan'])->name('dashboard.rombel.masukkan');
        Route::post('/{id}/keluarkan', [RombelController::class, 'keluarkan'])->name('dashboard.rombel.keluarkan');
    });
    Route::prefix("siswa")->group(function() {
        Route::get("/", [SiswaController::class, 'page'])->name('dashboard.siswa.page');
    });

    Route::prefix('settings')->group(function() {
        Route::inertia('/', 'Auth/Setting')->name('dashboard.settings');
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

    Route::resource('video', 'VideoController');


    Route::get('/', [FrontPageController::class, 'index'])->name('Welcome');
    Route::get('/posts', [FrontPageController::class, 'index'])->name('Post.index');
    Route::get('/about', [FrontPageController::class, 'index'])->name('About');


    
    Route::prefix("post")->group(function() {
        Route::post('/upload-image', function(Request $request) {
            dd($request->all());
        })->name('post.upload.image');
    });

   
    Route::get('/search', [FrontPageController::class, 'search'])->name('Search');

    Route::prefix('install')->group(function() {
        Route::inertia('/', 'Install')->name('site.install');
    });

    Route::inertia('tes', 'Tes');

    Route::get('/{kategori}/{slug}', [FrontPageController::class, 'readPost'])->name('Post.read');
})->middleware(['guest','is_configured']);

