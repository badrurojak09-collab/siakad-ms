<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicPmbRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'identity_number' => ['required', 'string', 'max:50'],
            'school_origin' => ['required', 'string', 'max:150'],
            'admission_period_id' => ['required', 'integer'],
            'program_choices' => ['required', 'array', 'min:1', 'max:3'],
            'program_choices.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Surel wajib diisi.',
            'email.email' => 'Format surel tidak valid.',
            'phone.required' => 'Nomor WhatsApp/telepon wajib diisi.',
            'identity_number.required' => 'Nomor identitas wajib diisi.',
            'school_origin.required' => 'Asal sekolah wajib diisi.',
            'admission_period_id.required' => 'Gelombang pendaftaran wajib dipilih.',
            'program_choices.required' => 'Pilih minimal satu program studi.',
            'program_choices.max' => 'Maksimal 3 pilihan program studi.',
        ];
    }
}
