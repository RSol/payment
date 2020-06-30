<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoice extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'amount.regex'  => 'Amount should be like 120.22 format',
        ];
    }
}
