<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->check()) {
            return Inertia::render('Auth/Post', [
                'posts' => Post::orderBy('updated_at', 'DESC')->with('category', 'author.userable')->get(),
            ], 200);
        } else {

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function write()
    {
        // return Inertia::render('Auth/')
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = json_decode($request->post);
        try {
            $slug = strtolower(str_replace(" ", "-",$post->title));
            if($request->file('featured_image')) {
                $store = Storage::putFileAs('public/images', $request->file("featured_image"), uniqid("img").".jpg");
                $featured_image = Storage::url($store);
            } else {
                $featured_image = $post->featured_image ?? '0';
            }
            $post = Post::updateOrCreate(
                [
                    'id' => $post->id ?? null,
                    'author_id' => $post->author_id ?? $request->user()->name,
                ],
                [
                    
                    'category_id' => $post->category_id,
                    'slug' => $slug,
                    'title' => $post->title,
                    'featured_image' => $featured_image,
                    'content' => $post->content,
                    'status' => 'published',
                    'starred' => true
                ]
                );
            return redirect(route('dashboard.post'));
        } catch(\Exception $e) {

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
