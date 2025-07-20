<?php

namespace App\Http\Requests;

use App\Enums\ProductApplyingEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateBannerRequest extends FormRequest
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
            'title' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'reference' => 'required|string|max:500',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];
    }
}
