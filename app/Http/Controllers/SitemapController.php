<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::latest()->with('category')->get();
  
        return response()->view('sitemap', [
            'posts' => $posts
        ])->header('Content-Type', 'text/xml');
    }
}
