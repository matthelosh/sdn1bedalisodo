<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FrontPageController extends Controller
{
    public function index(Request $request) // Welcome Page
    {
        $datas = [
            'tes' => 'Halo',
            'starredPosts' => Post::where('starred','1')->with('category')->latest()->take(5)->get(),
            'posts' => Post::orderBy('created_at', 'DESC')->with('category')->limit(6)->get(),
        ];
        return $this->view(Route::currentRouteName(), $datas);
    }

    public function post(Request $request)
    {
        # code...
    }

    public function readPost(Request $request, $kategori, $slug)
    {
        $datas = [
            'post' => Post::where('slug', $slug)->first()
        ];
        return $this->view(Route::currentRouteName(), $datas);
    }

    public function view($routeName, $datas)
    {
        return Inertia::render(str_replace(".","/", $routeName), $datas);
    }

    
}
