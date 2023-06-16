<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FrontPageController extends Controller
{
    public function index(Request $request) // Welcome Page
    {
        $datas = [
            'tes' => 'Halo',
            'infos' => Post::where('category_id','inf')->with('category','author')->take(5)->get(),
            'beritas' => Post::where('category_id','brt')->with('author')->take(5)->get(),
            'prestasis' => Prestasi::latest()->take(5)->get(),
            'starredPosts' => Post::where('starred','1')->with('category')->latest()->take(5)->get(),
            'posts' => Post::orderBy('updated_at', 'DESC')->with('category')->limit(6)->get(),
        ];
        return $this->view(Route::currentRouteName(), $datas);
    }

    public function post(Request $request)
    {
        # code...
    }

    function search(Request $request) {
        $q = $request->query('q');
        $posts = Post::where('title', 'LIKE' ,'%'.$q.'%')->orWhere('slug','LIKE','%'.$q.'%')->orWhere('content','LIKE','%'.$q.'%')->with('category')->orderBy('created_at','DESC')->get();
        $datas = [
            'posts' => $posts
        ];
        return $this->view(Route::currentRouteName(), $datas);
    }

    public function readPost(Request $request, $kategori, $slug)
    {
        $datas = [
            'post' => $post = Post::where('slug', $slug)->with('category')->first(),
            'posts' => Post::where('category_id', $post->category_id)->with('category')->orderBy('created_at','DESC')->limit(6)->get()
        ];
        return $this->view(Route::currentRouteName(), $datas);
    }

    public function view($routeName, $datas)
    {
        return Inertia::render(str_replace(".","/", $routeName), $datas);
    }

    
}
