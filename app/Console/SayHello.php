<?php

namespace App\Actions;

use App\Services\Waapi;

class SayHello 
{
    public function __invoke(Waapi $waapi)
    {
        $waapi->send([
            'isGroup' => true,
            'chatId' => '628563580593-1487811497@g.us',
            'pesan' => "
Assalamualaikum! 
Selamat pagi Bpk/Ibu guru. 
Semoga hari ini Bpk/Ibu sehat dan semangat untuk memberikan ilmu yang bermanfaat bagi siswa-siswa SD Negeri 1 Bedalisodo.
Salam Mandita!
\u{1F603}
\u{1F64F}
",
            'media' => null
        ]);
    }
}