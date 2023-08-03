<?php
namespace App\Http\Controllers;

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Tabuna\Breadcrumbs\Trail;

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
        // return User::with('userable')->first();
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
        Route::post("/", [GuruController::class, 'index'])->name('dashboard.guru.index');
    });
    Route::prefix("rombel")->group(function() {
        Route::get("/", [RombelController::class, 'page'])->name('dashboard.rombel.page');
        Route::post("/store", [RombelController::class, 'store'])->name('dashboard.rombel.store');
        Route::post('/nonmember', [RombelController::class, 'nonMember'])->name('dashboard.rombel.nonmember');
        Route::delete('/{id}', [RombelController::class, 'destroy'])->name('dashboard.rombel.destroy');
        Route::post('/{id}/masukkan', [RombelController::class, 'masukkan'])->name('dashboard.rombel.masukkan');
        Route::post('/{id}/keluarkan/{siswa_id}', [RombelController::class, 'keluarkan'])->name('dashboard.rombel.keluarkan');
    });
    Route::prefix("siswa")->group(function() {
        Route::get("/", [SiswaController::class, 'page'])->name('dashboard.siswa.page');
        Route::post("/impor", [SiswaController::class, 'impor'])->name('dashboard.siswa.impor');
    });

    Route::prefix("ekskul")->group(function() {
        Route::inertia("/", "Auth/Ekskul")->name('dashboard.ekskul.home');
        Route::post('/', [EkskulController::class, 'index'])->name('dashboard.ekskul.index');
        Route::post('/store', [EkskulController::class, 'store'])->name('dashboard.ekskul.store');
        Route::post('/peserta/{id}', [EkskulController::class, 'peserta'])->name('dashboard.ekskul.peserta');
        Route::post('/peserta/masukkan/{id_ekskul}/{id_siswa}',[EkskulController::class, 'masukkan'])->name('dashboard.ekskul.peserta.masukkan');
        Route::post('/peserta/keluarkan/{id_ekskul}/{id_siswa}',[EkskulController::class, 'keluarkan'])->name('dashboard.ekskul.peserta.keluarkan');
        Route::delete('/{id}', [EkskulController::class, 'destroy'])->name('dashboard.ekskul.destroy');
       
    });

    Route::prefix("bos")->group(function() {
        Route::inertia("/", "Auth/Bos")->name('dashboard.bos.home');
        Route::post("/bku", [BosController::class, 'getBku'])->name('dashboard.bos.bku.index');
        Route::prefix("/transaksi")->group(function() {
            Route::post("/", [BosController::class, 'transaksi'])->name('dashboard.bos.transaksi.index');
            Route::post("/store", [BosController::class, 'storeTransaksi'])->name('dashboard.bos.transaksi.store');
            Route::post("/import", [BosController::class, 'importTransaksi'])->name('dashboard.bos.transaksi.import');
        });
        Route::post('/kegiatan', [BosController::class, 'listKegiatan'])->name('dashboard.bos.kegiatan.index');
    });

    Route::prefix('inventaris')->group(function() {
        Route::inertia("/", "Auth/Inventaris")->name('dashboard.inventaris.home');
    });
    Route::prefix('tu')->group(function() {
        Route::inertia("/", "Auth/Tu")->name('dashboard.tu.home');
        Route::post("/klasifikasi_surat", [SuratController::class, 'indexKlasifikasiSurat'])->name('dashboard.tu.klasifikasi_surat.index');
        Route::post("/klasifikasi_surat/impor", [SuratController::class, 'imporKlasifikasiSurat'])->name('dashboard.tu.klasifikasi_surat.impor');
        // Route::get("/", function() {
        //     return 'Halo';
        // });
    });

    Route::prefix('settings')->group(function() {
        Route::inertia('/', 'Auth/Setting')->name('dashboard.settings');
        Route::post('/tapel', [SettingController::class, 'tapel'])->name('dashboard.tapel.index');
        Route::post('/tapel/toggle', [SettingController::class, 'toggleTapel'])->name('dashboard.tapel.toggle');
        Route::post('/tapel/store', [SettingController::class, 'storeTapel'])->name('dashboard.setting.tapel.store');
    });
    Route::prefix('profile')->group(function() {
        Route::get('/', function() {
            return 'Profil';
        })->name('profile');
    });
})->middleware('is_auth');


require __DIR__.'/auth.php';

Route::prefix("")->group(function() {

    // Tes IDC Storage
    Route::get('/s3', function() {
        $files = Storage::disk('s3')->files('images');
        return $files;
    });

    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

    Route::resource('video', 'VideoController');

    Route::get('/', [FrontPageController::class, 'index'])->name('Welcome');
    Route::get('/posts', [FrontPageController::class, 'index'])->name('Post.index');
    Route::get('/about', [FrontPageController::class, 'index'])->name('About')->breadcrumbs(fn(Trail $trail) => $trail->parent('Welcome')->push('Profil', route('About')));

    Route::get('/guru/{name}', [FrontPageController::class, 'author'])->name('Author');
    
    Route::prefix("post")->group(function() {
        Route::post('/upload-image', function(Request $request) {
            dd($request->all());
        })->name('post.upload.image');
    });

    Route::prefix("agenda")->group(function() {
        Route::post("/", [AgendaController::class, "index"])->name("agenda.index");
    });
   
    Route::get('/search', [FrontPageController::class, 'search'])->name('Search');

    Route::prefix('install')->group(function() {
        Route::inertia('/', 'Install')->name('site.install');
    });

    Route::inertia('tes', 'Tes');

    Route::get('/{kategori}/{slug}', [FrontPageController::class, 'readPost'])->name('Post.read');
})->middleware(['guest','is_configured']);

