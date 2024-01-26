<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Services\PostService;
use Illuminate\Support\Facades\Storage;

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
     * UPload Image of the Post.
     */
    public function uploadImage(Request $request)
    {
        // dd($request->all());
        $file = $request->file('image');
        $filename = Str::random(16);
        // $store = Storage::putFileAs("public/images/post/", $file, $filename.'.'.$file->extension());
        $store = $file->storePubliclyAs("public/images/post/", $filename.'.'.$file->extension(), 's3');
        $url = Storage::disk('s3')->url($store);
        return response()->json(['url' => $url], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PostService $post)
    {
        $data = json_decode($request->post);
        // dd(json_decode($data));
        
        try {

            if($request->file('featured_image')) {
                $featured_image = $post->storeFeatureImage($request->file("featured_image"));
            } else {
                $featured_image = $data->featured_image ?? '0';
            }
            // dd($featured_image);
            $store = $post->write($data, $featured_image);


            
            // return redirect(route('dashboard.post.home'))->with('status', $store);
            return response()->json(['status' => 'Ok', 'post' => $store], 200);
        } catch(\Exception $e) {
            return response()->json(['status' => 'Fail', 'msg' => $e->getMessage()], 500);
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
