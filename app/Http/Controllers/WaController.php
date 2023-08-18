<?php

namespace App\Http\Controllers;

use App\Traits\WablasTrait;
use Illuminate\Http\Request;
use App\Models\Guru;
use GuzzleHttp\Client;
use App\Services\Waapi;

class WaController extends Controller
{

    public function sendToUsers(Request $request) {
        $gurus = Guru::whereNot('role', 'admin')->get();
        // dd($gurus);
        $message = json_decode($request->data);
        $datas = [];
        foreach($gurus as $guru)
        {
           
            
            $data['phone'] = $guru->hp;
            $data['message'] = $message->text;
            $data['secret'] = false;
            $data['retry'] = false;
            $data['isGroup'] = false;

            array_push($datas, $data);

           
        }
        $this->kirim($datas);

        return response()->json([
            'result' => 'PEsan berhasil dikirim'
        ], 200);
    }


    public function send(Request $request, Waapi $waapi)
    {
        // dd(json_decode($request->data));
        try {
            $message = json_decode($request->data);
                $params = [
                    'isGroup' => $message->isGroup,
                    'chatId' => $message->chatId,
                    'pesan' => $message->text,
                    'media' => $message->media
                ];
            $result = $waapi->send($params);
            return response()->json(
                $result
            , 200);
        } catch(\Exception $e) {
            return response()->json([
                'result' => $e->getMessage()
            ], 500);
        }
    }  

    public function listGroup(Waapi $waapi) {
        try {
            $groups = $waapi->listGroups();
            
            return response()->json($groups, 200);
        } catch(\Exception $e) {
            dd($e);
        }
    }
    
    function kirim($datas) {
    } 
    
}
