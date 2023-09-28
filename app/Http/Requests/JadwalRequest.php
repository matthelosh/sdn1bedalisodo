<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JadwalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'guru_id' => 'required',
            'tapel' => 'required',
            'mapel_id' => 'required',
            'rombel_id' => 'required',
            'jamke' => 'required',
            'jml_jam' => 'required',
            'hari' => 'required'
        ];
    }
}
