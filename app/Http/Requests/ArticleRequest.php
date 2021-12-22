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
        $max_images = ArticleImage::MAX_IMAGES - ($this->article ? $this->article->images->count() : 0);
        $max_image_rules = ($max_images < 1 || $this->image_flag === 'edit') ? '' : "max:{$max_images}";

        $max_tags = Tag::MAX_TAGS - ($this->article ? $this->article->tags->count() : 0);
        $max_tag_rules = $max_tags < 1 ? '' : "max:{$max_tags}";

        return [
            'title' => ['required', 'string', 'min:4', 'max:255'],
            'content' => ['required', 'string', 'min:2'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags' => ['required', $max_tag_rules],
            'tags.*' => ['integer', 'exists:tags,id'],
            'path' => [$max_image_rules],
            'path.*' => ['image', 'mimes:png,jpg,jpeg', 'max:2048']
        ];
    }

    public function messages()
    {
        $max_images = ArticleImage::MAX_IMAGES - ($this->article ? $this->article->images->count() : 0);
        $max_tags = Tag::MAX_TAGS - ($this->article ? $this->article->tags->count() : 0);

        return [
            'category_id.required' => 'Please select at least 1 Category',
            'tags.required' => 'Please choose at least 1 Tag',
            'tags.max' => "You've reached the maximum of the tagged articles ({$max_tags})",
            'path.max' => "You've reached the maximum of the attached file ({$max_images})"
        ];
    }
}
