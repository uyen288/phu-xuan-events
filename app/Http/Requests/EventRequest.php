<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Chỉ Organizer và Admin mới có quyền gửi request này
        return Auth::check() && (Auth::user()->role === 'organizer' || Auth::user()->role === 'admin');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:10|max:200',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'capacity' => 'required|integer|min:10|max:5000',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => 'required|in:draft,published,cancelled',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Module M2.4 nâng cao
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tên sự kiện.',
            'title.min' => 'Tên sự kiện phải dài ít nhất 10 ký tự.',
            'title.max' => 'Tên sự kiện không được vượt quá 200 ký tự.',
            'description.required' => 'Vui lòng nhập mô tả sự kiện.',
            'description.min' => 'Mô tả sự kiện phải dài ít nhất 50 ký tự.',
            'location.required' => 'Vui lòng nhập địa điểm tổ chức.',
            'start_time.required' => 'Vui lòng chọn thời gian bắt đầu.',
            'start_time.after' => 'Thời gian bắt đầu phải là một mốc thời gian trong tương lai.',
            'end_time.required' => 'Vui lòng chọn thời gian kết thúc.',
            'end_time.after' => 'Thời gian kết thúc phải diễn ra sau thời gian bắt đầu.',
            'capacity.required' => 'Vui lòng nhập sức chứa (số chỗ).',
            'capacity.min' => 'Sức chứa tối thiểu phải từ 10 người trở lên.',
            'category_id.required' => 'Vui lòng chọn danh mục sự kiện.',
            'category_id.exists' => 'Danh mục được chọn không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái sự kiện.',
            'status.in' => 'Trạng thái sự kiện không hợp lệ.',
            'banner.image' => 'File tải lên bắt buộc phải là định dạng ảnh.',
            'banner.mimes' => 'Ảnh banner chỉ chấp nhận các định dạng: jpeg, png, jpg, gif.',
            'banner.max' => 'Dung lượng ảnh banner không được vượt quá 2MB.',
        ];
    }
}