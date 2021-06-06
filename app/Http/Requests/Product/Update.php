<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'title'        => 'required|min:2|max:190|unique:products,title,' . $this->id,
            'description'  => 'min:2|max:1500',
            'price'        => 'required|numeric|min:0.01',
            'categories'   => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ];
    }
}
