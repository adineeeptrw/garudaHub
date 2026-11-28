<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nilai' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nilai.required' => 'Nilai wajib dipilih.',
            'nilai.between' => 'Nilai harus antara 1 sampai 5.',
            'komentar.max' => 'Komentar maksimal 1000 karakter.',
        ];
    }
}
