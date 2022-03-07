<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['nullable', 'string'],
            'cart_number' => ['nullable', 'string', 'regex:/^[0-9]{16}$/'],
            'shaba_number' => ['required', 'string', 'regex:/^[0-9]{26}$/'],
        ];
    }
}
