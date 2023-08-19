<?php
namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Services\Waapi;

class PostService
{
    public function write( $post  = null, string $featured_image = null)
    {
        $waapi = new Waapi();
        $data = json_decode($post);
        // dd($args);
        try {
            $slug = strtolower(str_replace(" ", "-",$data->title));
            $chatId = env('APP_ENV') == 'production' ? '628563580593-1487811497@g.us' : '120363149742466007@g.us';
            $store = Post::updateOrCreate(
                [
                    'id' => $data->id ?? null,
                    'author_id' => $data->author_id ?? auth()->user()->name,
                ],
                [
                    'category_id' => $data->category_id,
                    'slug' => $slug,
                    'title' => $data->title,
                    'featured_image' => $featured_image,
                    'content' => $data->content,
                    'status' => 'published',
                    'starred' => true
                ]
            );
            // $category = Category::where('kode', $store->category_id)->first();
            $stored = Post::where('id', $store->id)->with('category','author.userable')->first();
            $url = env('APP_URL').'/'. strtolower($stored->category->label).'/'.$store->slug;
            $pesan = $stored->author->userable->nama . ' baru saja menulis postingan dengan judul: "'.$stored->title.'". Baca di '.$url;
            $waapi->send(['isGroup' => '1', 'chatId' => $chatId, 'pesan' => $pesan]);
            
            return $store;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function storeFeatureImage($image = null) 
    {
        $name = $image->getClientOriginalName();
        // dd($image);
        // $store = Storage::putFileAs('public/images', $image, uniqid("featured_image-").".jpg");
        $store = $image->storePubliclyAs('images', $name, 's3');
        // dd(Storage::url($store));
        return Storage::diks('s3')->url($store);
    }
}