<?php
namespace App\Actions;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

use App\Models\ViewCounter;

class RecordView {
    function handle(Request $request, $post=null) {
        $agent = new Agent();
        $record = ViewCounter::create([
            'route' => $request->url(),
            'post_id' => $post ? $post->id: null,
            'user_agent' => $agent->browser().' | '.$agent->platform(). ' \ '.$agent->device(),
            'location' => env('APP_ENV') == 'Local' ? 'Indo Lokasl' : (Location::get() ? Location::get()->countryName : 'Undefined Location'),
            'ip_address' => $request->ip()
        ]);

        return $record;
    }
}