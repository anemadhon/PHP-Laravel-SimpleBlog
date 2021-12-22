<?php

namespace App\Http\Requests;

use App\Models\ArticleImage;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'min:4', 'max:255'],
            'content' => ['required', 'string', 'min:2'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags' => ['required', 'min:1', $this->max_tag_rules],
            'tags.*' => ['required', 'integer', 'exists:tags,id'],
            'path' => [$this->max_image_rules],
            'path.*' => ['image', 'mimes:png,jpg,jpeg', 'max:2048']
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Please select 1 Category',
            'category_id.exists' => 'You selected The Wrong Category',
            'tags.required' => 'Please choose at least 1 Tag',
            'tags.*.required' => 'Please choose at least 1 Tag',
            'tags.*.exists' => 'You choosed The Wrong Tag',
            'tags.max' => "You've reached the maximum of the tagged articles ({$this->max_tags})",
            'path.max' => "You've reached the maximum of the attached file ({$this->max_images})"
        ];
    }

    protected function prepareForValidation()
    {
        $max_tags = Tag::MAX_TAGS - ($this->article ? $this->article->tags->count() : 0);
        $max_images = ArticleImage::MAX_IMAGES - ($this->article ? $this->article->images->count() : 0);

        $this->merge([
            'max_tags' => $max_tags,
            'max_tag_rules' => $max_tags < 1 ? '' : "max:{$max_tags}",
            'max_images' => $max_images,
            'max_image_rules' => ($max_images < 1 || $this->image_flag === 'edit') ? '' : "max:{$max_images}"
        ]);
    }
}
