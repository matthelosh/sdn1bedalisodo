<?php

namespace App\Console;

use App\Services\Waapi;
use Illuminate\Support\Facades\Log;

class SayHello 
{
    public function __invoke(Waapi $waapi)
    {
       $sent =  $waapi->send([
            'isGroup' => true,
            'chatId' => env('APP_ENV') == 'local'? '120363149742466007@g.us' :'628563580593-1487811497@g.us',
            'pesan' => "
Assalamualaikum! 
Selamat pagi Bpk/Ibu guru. 
Semoga hari ini Bpk/Ibu sehat dan semangat untuk memberikan ilmu yang bermanfaat bagi siswa-siswa SD Negeri 1 Bedalisodo.
Salam Mandita! Mandiri, Disiplin, Bertakwa!
\u{1F603} \u{1F64F}
",
            'media' => null
        ]);

        
    }
}