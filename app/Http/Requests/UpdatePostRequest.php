<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            // 'slug' => ['required', Rule::unique('posts', 'slug')->ignore($post->id)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề bài viết không được để trống.',
            'title.min' => 'Tiêu đề phải có ít nhất :min ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá :max ký tự.',
            'content.required' => 'Nội dung bài viết không được để trống.',
            'content.min' => 'Nội dung phải có ít nhất :min ký tự.',
        ];
    }

    public function attributes(): array
    {
        return ['title' => 'tiêu đề', 'content' => 'nội dung',];
    }
}
