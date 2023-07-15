<?php

namespace App\Http\Middleware;

use App\Models\Menu;
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
                'user' => User::where('id',$request->user()->id)->with('userable')->first(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'sekolah' => $this->sekolah(),
            'menus' => $request->user() ? $this->menus($request->user()) : null,
            'layout' => $this->frontLayout() ?? 'Default',
            'tapel' => Tapel::where("status", "1")->first(),
        ]);
    }

    protected function frontLayout() {
        $site = \App\Models\Config::select('layout')->first();
        return $site->layout;
    }

    protected function sekolah() {
        $sekolah = Sekolah::with('ks','bendahara','operator')->first();
        return $sekolah ?? null;
    }

    protected function menus($user) {
        $all = Menu::whereDoesntHave('parent')->where('roles', 'all')->with('children')->get();
        $roled = Menu::whereDoesntHave('parent')->where('roles', 'LIKE', '%'.$user->level.'%')->with('children')->get();
        $menus = $all->merge($roled);
        return $menus;
    }
}
