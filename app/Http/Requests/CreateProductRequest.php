<?php

namespace App\Http\Requests;

use App\Enums\ProductApplyingEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|string|max:1000|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'books_quantity' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'content' => 'required|string|max:10000',
            'for_whom' => 'required|string|max:10000',
            'appointment' => 'required|string|max:1000',
            'applying' => ['required', Rule::enum(ProductApplyingEnum::class)],
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];
    }
}
