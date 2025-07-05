<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchSettlementRequest extends FormRequest
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
            'search' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'search.required' => 'Введіть назву населеного пункту',
            'search.string' => 'Назва повинна бути текстом',
            'search.min' => 'Назва повинна містити мінімум 2 символи',
            'search.max' => 'Назва занадто довга (максимум 100 символів)',
            'search.regex' => 'Назва може містити лише українські букви, пробіли, дефіси та апострофи'
        ];
    }

    public function attributes(): array
    {
        return [
            'search' => 'пошуковий запит'
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
