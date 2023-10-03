<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Post;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\RecordView;
use App\Models\User;

class FrontPageController extends Controller
{
    public function index(Request $request, RecordView $recordView) // Welcome Page
    {
        $datas = [
            'tes' => 'Halo',
            'infos' => Post::where('category_id','inf')->with('category','author')->take(5)->get(),
            'beritas' => Post::where('category_id','brt')->with('author')->take(5)->get(),
            'prestasis' => Prestasi::latest()->take(5)->get(),
            'starredPosts' => Post::where('starred','1')->with('category')->latest()->take(5)->get(),
            'gurus' => Guru::whereNot('nama','Administrator')->get(),
            'posts' => Post::orderBy('updated_at', 'DESC')->with('category', 'views', 'author.userable')->limit(16)->get(),
        ];
        // dd($datas);
        try {
            $recordView->handle($request, null);
            return $this->view(Route::currentRouteName(), $datas);
        } catch(\Exception $e) {
            dd($e);
        }
    }

    public function author(Request $request, RecordView $recordView, $name)
    {
        $datas = [
            'author' => User::where('name', $name)->with('posts.category', 'userable')->first()
        ];
        try {
            $recordView->handle($request, null);
            return $this->view(Route::currentRouteName(), $datas);
        } catch(\Exception $e) {
            dd($e);
        }
    }

    function search(Request $request) {
        $q = $request->query('q');
        $posts = Post::where('title', 'LIKE' ,'%'.$q.'%')->orWhere('slug','LIKE','%'.$q.'%')->orWhere('content','LIKE','%'.$q.'%')->with('category', 'author', 'views')->orderBy('created_at','DESC')->get();
        $datas = [
            'posts' => $posts
        ];
        return $this->view(Route::currentRouteName(), $datas);
    }

    public function readPost(Request $request, RecordView $recordView, $kategori, $slug)
    {
        $keys = [];
        $categories = Category::select('label')->get();
        foreach ($categories as $category) {
            array_push($keys, strtolower($category->label));
        }
        if(in_array($kategori,$keys)) {
            $post = Post::where('slug', $slug)->with('category', 'author.userable','views')->first();
            $datas = [
                'post' => $post,
                'posts' => Post::where('category_id', $post->category_id)->with('category', 'author.userable', 'views')->orderBy('created_at','DESC')->limit(6)->get()
            ];
            $recordView->handle($request, $post);
            // return $this->view(Route::currentRouteName(), $datas);
            return Inertia::render('Post', $datas);
        } else {
            abort(404);
        }
    }

    public function view($routeName, $datas)
    {
        return Inertia::render(str_replace(".","/", $routeName), $datas);
    }

    public function byCategory(Request $request, $kategori) {
        $category = Category::where('label', $kategori)->first();
        $posts = Post::where('category_id', $category->kode)->with('category', 'author.userable')->orderBy('updated_at', 'DESC')->get();
        return Inertia::render('Post/Kategori', [
            'posts' => $posts,
            'kategori' => $category->label
        ]);
    }

    
}
