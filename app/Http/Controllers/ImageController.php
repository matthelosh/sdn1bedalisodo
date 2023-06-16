<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    
    function store(Request $request) {
        $images = $request->file("images");
        $path = 'public/images';
        $urls = [];
        foreach($images as $image) {
            $name = $image->getClientOriginalName();
            $ext = $image->extension();
            $store = Storage::putFileAs($path, $image, $name);
            array_push($urls, Storage::url($store));
        }
        return response()->json([
            'status' => 'ok',
            'urls' => $urls
        ], 200);
    }

    function list(Request $request) {
        if ($request->query("disk") == 'local') {
            $images = Storage::allFiles('public/images');
        } else {
            $images = [];
            $urls = Storage::disk('s3')->files('images');
            foreach($urls as $url) {
                array_push($images, env('AWS_URL').'/'.$url);
            }
        }
        return response()->json([
            'status' => 'ok',
            'images' => $images
        ], 200);

    }
}
