<?php

namespace App\Services;

use GuzzleHttp\CLient;
use Illuminate\Support\Facades\Http;

class Waapi 
{
    protected string $url;
    // protected $client;
    protected array $headers;

    public function __construct()
    {
        $this->url = env('APP_ENV') == 'local' ? 'http://localhost:3000/api/wa' : env('WAAPI_URL');
        $this->headers =[
                    'Content-Type' => 'application/json',
                    'secret' => env('WAAPI_SECRET'),
                    'key' => env('WAAPI_KEY')
                ];

        // $this->client = $client;

    }

    public function send(array $params = []) 
    {
        try {
            $sent = Http::withHeaders(
                    $this->headers
            )->asForm()->post($this->url.'/send', [
                        'isGroup' => $params['isGroup'],
                        'chatId' => $params['chatId'],
                        'pesan' => $params['pesan'],
                        'media' => $params['media'] ?? null
                    ],
            );
            $result = $sent->json();
            return $result;
        } catch(\Exception $e) {
            // dd($e);
            return ['status' => 'fail', 'msg' => 'Maaf! Gagal mengirim pesan'];
        }
    }

    public function listGroups() {
        try {
            $groups = Http::withHeaders($this->headers)
                        ->post($this->url.'/groups');
            return $groups->json();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}