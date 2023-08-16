<?php

namespace App\Http\Middleware;

use App\Models\Anggaran;
use App\Models\Guru;
use App\Models\Menu;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use App\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? User::where('id',$request->user()->id)->with('userable')->first() : null,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'sekolah' => $this->sekolah(),
            'menus' => $request->user() ? $this->menus($request->user()) : null,
            'layout' => $this->frontLayout(),
            'tapel' => $this->tapel(),
            'anggaran' => $this->anggaran(),
            'rombels' => $request->user() ? $this->rombels($request->user()) : null,
        ]);
    }

    protected function rombels($user) {
        $user = User::where('id', $user->id)->with('userable')->first();
        if ($user->userable->role == 'gkel') {
            $rombels = Rombel::where('tapel', $this->tapel()->kode)->where('guru_id', $user->userable->nip)->with('siswas','mapels')->get();
        } else {
            $rombels = Rombel::where('tapel', $this->tapel()->kode)->with('siswas','guru')->get();
        }

        return $rombels;
    }


    protected function frontLayout() {
        $site = \App\Models\Config::select('layout')->first();
        return $site ? $site->layout : 'Default';
    }

    protected function sekolah() {
        $sekolah = Sekolah::with('ks','bendahara','operator')->first();
        return $sekolah ?? null;
    }

    protected function menus($user) {
        $guru = Guru::where('id', $user->userable_id)->first();
        $all = Menu::whereDoesntHave('parent')->where('roles', 'all')->with('children')->get();
        $roled = Menu::whereDoesntHave('parent')->where('roles', 'LIKE', '%'.$user->level.'%')->with('children')->get();
        $menus = $all->merge($roled);
        return $menus;
    }

    protected function tapel() {
        $tapel = Tapel::where("status", "1")->first();
        return $tapel ?? null;
    }

    protected function anggaran() {
        return Anggaran::where('status', 'aktif')->first();
    }
}
