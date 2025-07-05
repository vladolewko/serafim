<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChooseSettlementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settlement' => [
                'required',
                'string',
                'size:36',
                'regex:/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'settlement.required' => 'Оберіть населений пункт зі списку',
            'settlement.string' => 'Ідентифікатор населеного пункту повинен бути текстом',
            'settlement.size' => 'Некоректний ідентифікатор населеного пункту',
            'settlement.regex' => 'Некоректний формат ідентифікатора населеного пункту'
        ];
    }

    public function attributes(): array
    {
        return [
            'settlement' => 'населений пункт'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'error' => $validator->errors()->first(),
                'validation_errors' => $validator->errors()->all(),
                'timestamp' => now()->toISOString()
            ], 422)
        );
    }
}
