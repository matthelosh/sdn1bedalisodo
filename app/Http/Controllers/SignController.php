<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SignController extends Controller
{
    public function verify(Request $request) {
        $url = $request->getHttpHost(). "/ttd/".$this->sekolah()->ks.".png";
        // return "<img src=".$url." />";
        return QrCode::generate($url);
        // return $url;
        // return QrCode::generate("/ttd/".$this->sekolah()->ks->nip.".png");
    }

    private function sekolah() {
        return Sekolah::first();
    }
}
