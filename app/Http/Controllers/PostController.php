<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

use App\Services\PostService;

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
    public function store(Request $request, PostService $post)
    {
        $data = $request->post;
        // dd(json_decode($data));
        
        try {

            if($request->file('featured_image')) {
                $featured_image = $post->storeFeatureImage($request->file("featured_image"));
            } else {
                $featured_image = $data->featured_image ?? '0';
            }
            // dd($featured_image);
            $store = $post->write($data, $featured_image);


            
            return redirect(route('dashboard.post.home'))->with('status', $store);
        } catch(\Exception $e) {
            return back()->with(['error' => $e->getMessage()], 500);
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
