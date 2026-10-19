<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{8,}$/'],
            'shipping_address' => ['required', 'string', 'min:10', 'max:500'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'recipient_name' => 'nama penerima',
            'phone' => 'nomor telepon',
            'shipping_address' => 'alamat pengiriman',
            'notes' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return ['phone.regex' => 'Nomor telepon tidak valid.'];
    }
}
