<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = request()->isMethod('post') ? 'unique:categories,name' : Rule::unique('categories')->ignore($this->category);
        
        return [
            'name' => ['required', 'string', 'min:4', 'max:255', $unique]
        ];
    }
}
