<?php

namespace App\Http\Requests;

use App\Enums\ProductApplyingEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'books_quantity' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
            'dimension' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'for_whom' => 'required|string|max:1000',
            'appointment' => 'required|string|max:255',
            'applying' => ['required', Rule::enum(ProductApplyingEnum::class)],
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];
    }
}
