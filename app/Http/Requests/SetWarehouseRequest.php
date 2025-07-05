<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SetWarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse' => [
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
            'warehouse.required' => 'Оберіть відділення або поштомат',
            'warehouse.string' => 'Ідентифікатор відділення повинен бути текстом',
            'warehouse.size' => 'Некоректний ідентифікатор відділення',
            'warehouse.regex' => 'Некоректний формат ідентифікатора відділення'
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse' => 'відділення'
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
