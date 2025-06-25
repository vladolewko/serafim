<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'
            ],
            'surname' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^(\+38)?0\d{9}$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255'
            ],
            'payment' => [
                'required',
                'in:cash,card'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Введіть ім\'я',
            'name.string' => 'Ім\'я повинно бути текстом',
            'name.min' => 'Ім\'я повинно містити мінімум 2 символи',
            'name.max' => 'Ім\'я занадто довге (максимум 50 символів)',
            'name.regex' => 'Ім\'я може містити лише українські букви, пробіли, дефіси та апострофи',

            'surname.required' => 'Введіть прізвище',
            'surname.string' => 'Прізвище повинно бути текстом',
            'surname.min' => 'Прізвище повинно містити мінімум 2 символи',
            'surname.max' => 'Прізвище занадто довге (максимум 50 символів)',
            'surname.regex' => 'Прізвище може містити лише українські букви, пробіли, дефіси та апострофи',

            'phone.required' => 'Введіть номер телефону',
            'phone.string' => 'Номер телефону повинен бути текстом',
            'phone.regex' => 'Номер телефону має бути у форматі +380XXXXXXXXX або 0XXXXXXXXX',

            'email.required' => 'Введіть електронну пошту',
            'email.email' => 'Введіть коректну електронну пошту',
            'email.max' => 'Електронна пошта занадто довга (максимум 255 символів)',

            'payment.required' => 'Оберіть спосіб оплати',
            'payment.in' => 'Оберіть коректний спосіб оплати (готівка або картка)'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'ім\'я',
            'surname' => 'прізвище',
            'phone' => 'номер телефону',
            'email' => 'електронна пошта',
            'payment' => 'спосіб оплати'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Нормалізуємо номер телефону
        if ($this->phone) {
            $phone = preg_replace('/[^\d+]/', '', $this->phone);

            // Якщо номер починається з 0, додаємо +38
            if (preg_match('/^0\d{9}$/', $phone)) {
                $phone = '+38' . $phone;
            }

            $this->merge([
                'phone' => $phone
            ]);
        }

        // Нормалізуємо імена (перша літера велика)
        if ($this->name) {
            $this->merge([
                'name' => ucfirst(strtolower(trim($this->name)))
            ]);
        }

        if ($this->surname) {
            $this->merge([
                'surname' => ucfirst(strtolower(trim($this->surname)))
            ]);
        }

        // Нормалізуємо email
        if ($this->email) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'error' => $validator->errors()->first(),
                'validation_errors' => $validator->errors()->all(),
                'field_errors' => $validator->errors()->toArray(),
                'timestamp' => now()->toISOString()
            ], 422)
        );
    }
}
