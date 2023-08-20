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

    Route::prefix('wa')->group(function() {
        Route::post("/send", [WaController::class, 'send'])->name('dashboard.wa.send');
        Route::post("/send-many", [WaController::class, 'sendToUsers'])->name('dashboard.wa.users.send');
        Route::post("/group/list", [WaController::class, 'listGroup'])->name('dashboard.wa.group.index');
        Route::post("/group/list/local", [WaController::class, 'listGroupLocal'])->name('dashboard.wa.grup.list');
    });

    Route::prefix("post")->group(function() {
        Route::get('/', [PostController::class, 'index'])->name('dashboard.post.home');
        Route::post('/store', [PostController::class, 'store'])->name('dashboard.post.store');
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
        Route::post('/store', [GuruController::class, 'store'])->name('dashboard.guru.store');
        Route::post('/account/add', [GuruController::class, 'addAccount'])->name('dashboard.guru.account.add');
        Route::post('/account/remove', [GuruController::class, 'removeAccount'])->name('dashboard.guru.account.remove');
    });
    Route::prefix("rombel")->group(function() {
        Route::get("/", [RombelController::class, 'page'])->name('dashboard.rombel.page');
        Route::post("/store", [RombelController::class, 'store'])->name('dashboard.rombel.store');
        Route::post('/nonmember', [RombelController::class, 'nonMember'])->name('dashboard.rombel.nonmember');
        Route::delete('/{id}', [RombelController::class, 'destroy'])->name('dashboard.rombel.destroy');
        Route::post('/{id}/masukkan', [RombelController::class, 'masukkan'])->name('dashboard.rombel.masukkan');
        Route::post('/{id}/member/impor', [RombelController::class, 'imporMember'])->name('dashboard.rombel.member.impor');
        Route::post('/{id}/mapel/masukkan', [RombelController::class, 'attachMapel'])->name('dashboard.rombel.mapel.masukkan');
        Route::post('/{id}/mapel/keluarkan', [RombelController::class, 'detachMapel'])->name('dashboard.rombel.mapel.keluarkan');
        Route::post('/{id}/keluarkan/{siswa_id}', [RombelController::class, 'keluarkan'])->name('dashboard.rombel.keluarkan');
    });
    Route::prefix("siswa")->group(function() {
        Route::get("/", [SiswaController::class, 'page'])->name('dashboard.siswa.page');
        Route::post("/store", [SiswaController::class, 'store'])->name('dashboard.siswa.store');
        Route::post("/impor", [SiswaController::class, 'impor'])->name('dashboard.siswa.impor');
        Route::put("/foto/update", [SiswaController::class, 'updateFoto'])->name('dashboard.siswa.foto.update');
        Route::delete("/{id}", [SiswaController::class, 'destroy'])->name('dashboard.siswa.destroy');

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

    Route::prefix('intra')->group(function() {
        Route::inertia("/", "Auth/Intra")->name('dashboard.intra.home');
    });

    Route::prefix('mapel')->group(function() {
        Route::post('/', [MapelController::class, 'index'])->name('dashboard.mapel.index');
        Route::post('/excluded-from-rombel', [MapelController::class, 'rombelExcluded'])->name('dashboard.mapel.excluded');
        Route::post('/store', [MapelController::class, 'store'])->name('dashboard.mapel.store');
        Route::delete('/{id}', [MapelController::class, 'destroy'])->name('dashboard.mapel.destroy');
    });

    Route::prefix("bos")->group(function() {
        Route::inertia("/", "Auth/Bos")->name('dashboard.bos.home');
        Route::prefix('anggaran')->group(function() {
            Route::post('/', [BosController::class, 'indexAnggaran'])->name('dashboard.bos.anggaran.mata');
            Route::post('/store', [BosController::class, 'storeAnggaran'])->name('dashboard.bos.anggaran.store');
            Route::delete('/{id}', [BosController::class, 'deleteAnggaran'])->name('dashboard.bos.anggaran.hapus');
            Route::put('/{id}/status/change', [BosController::class, 'changeStatusAnggaran'])->name('dashbaord.bos.anggaran.status.change');
        });
        Route::post("/bku", [BosController::class, 'getBku'])->name('dashboard.bos.bku.index');
        Route::prefix("/transaksi")->group(function() {
            Route::post("/", [BosController::class, 'transaksi'])->name('dashboard.bos.transaksi.index');
            Route::post("/store", [BosController::class, 'storeTransaksi'])->name('dashboard.bos.transaksi.store');
            Route::post("/import", [BosController::class, 'importTransaksi'])->name('dashboard.bos.transaksi.import');
        });
        Route::prefix('rkas')->group(function() {
            Route::post('/', [BosController::class, 'indexRkas'])->name('dashboard.bos.rkas.index');
            Route::post('/store', [BosController::class, 'storeRkas'])->name('dashboard.bos.rkas.store');
            Route::post('/impor', [BosController::class, 'imporRkas'])->name('dashboard.bos.rkas.impor');
            Route::put('/{id}/status/change', [BosController::class, 'changeStatusRkas'])->name('dashbaord.bos.rkas.status.change');
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
        Route::put('/sekolah', [SekolahController::class, 'update'])->name('dashboard.sekolah.update');
        Route::prefix('menu')->group(function() {
            Route::post("/", [SettingController::class, 'menuIndex'])->name('dashboard.menu.index');
            Route::post("/store", [SettingController::class, 'menuStore'])->name('dashboard.menu.store');
            Route::delete("/{id}", [SettingController::class, 'menuDestroy'])->name('dashboard.menu.destroy');
        });
        Route::prefix('agenda')->group(function() {
            Route::post("/", [AgendaController::class, "index"])->name("dashboard.agenda.index");
            Route::post("/store", [AgendaController::class, "store"])->name("dashboard.agenda.store");
        });
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

