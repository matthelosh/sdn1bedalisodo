<?php
namespace App\Services;

use App\Models\Cp;

class CpService
{
    public function store($data)
    {
        $store = Cp::updateOrCreate(
            [
                'id' => $data->id ?? null,
            ],
            [
                'kode' => $data->kode,
                'fase' => $data->fase,
                'tingkat' => $data->tingkat,
                'elemen' => $data->elemen,
                'teks' => $data->teks,
                'mapel_id' => $data->mapel_id
            ]
        );

        return $store;
    }
}