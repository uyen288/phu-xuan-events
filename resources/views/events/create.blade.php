@extends('layouts.app')

@section('title', 'Tạo Sự Kiện Mới')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="mb-4">
                <a href="{{ route('events.index') }}" class="btn btn-light border text-secondary btn-sm px-3 shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h4 class="mb-0 font-weight-bold text-dark d-flex align-items-center">
                        <i class="bi bi-calendar-plus-fill text-primary me-2"></i>
                        Đăng Ký Khởi Tạo Sự Kiện Mới
                    </h4>
                    <small class="text-muted">Điền đầy đủ thông tin chi tiết dưới đây để ban tổ chức phê duyệt sự kiện.</small>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold text-dark">Tên Sự Kiện <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Ví dụ: Ngày hội việc làm IT 2026 hoặc Workshop Laravel từ A-Z">
                            @error('title')
                                <div class="invalid-feedback fw-semibold"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="category_id" class="form-label fw-bold text-dark">Danh Mục <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="" selected disabled>-- Chọn danh mục phù hợp --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback fw-semibold"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="capacity" class="form-label fw-bold text-dark">Sức Chứa Tối Đa (Người) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity', 50) }}" min="10" max="5000">
                                @error('capacity')
                                    <div class="invalid-feedback fw-semibold"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="start_time" class="form-label fw-bold text-dark">Thời Gian Bắt Đầu <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                       id="start_time" name="start_time" value="{{ old('start_time') }}">
                                @error('start_time')
                                    <div class="invalid-feedback fw-semibold"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="end_time" class="form-label fw-bold text-dark">Thời Gian Kết Thúc <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                       id="end_time" name="end_time" value="{{ old('end_time') }}">
                                @error('end_time')
                                    <div class="invalid-feedback fw-semibold"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label fw-bold text-dark">Địa Điểm Tổ Chức <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" 
                                       placeholder="Ví dụ: Hội trường A, Phòng máy B2, Sân Campus...">
                                @error('location')
                                    <div class="invalid-feedback fw-semibold"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Ảnh Banner Sự Kiện</label>
                            <div class="border border-dashed rounded-3 p-3 text-center bg-light position-relative">
                                <i class="bi bi-image text-muted fs-1 d-block mb-2"></i>
                                <input type="file" class="form-control @error('banner') is-invalid @enderror" 
                                       id="banner" name="banner" accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted d-block mt-1">Chấp nhận định dạng: JPG, PNG, JPEG, GIF. Tối đa 2MB.</small>
                                @error('banner')
                                    <div class="invalid-feedback fw-semibold d-block mt-1"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-3 text-center d-none" id="imagePreviewContainer">
                                <img id="outputPreview" class="img-fluid rounded-3 border" style="max-height: 250px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Gắn Thẻ Từ Khóa (Tags)</label>
                            <div class="p-3 bg-light border rounded-3">
                                <div class="row g-3">
                                    @forelse($tags as $tag)
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <div class="form-check card-check p-2 border rounded bg-white text-truncate shadow-sm-hover">
                                                <input class="form-check-input ms-1" type="checkbox" name="tags[]" 
                                                       value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                                       {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'checked' : '' }}>
                                                <label class="form-check-label text-dark fw-medium small ms-2 ps-3 w-100" for="tag_{{ $tag->id }}" style="cursor: pointer;">
                                                    <i class="bi bi-tag text-primary me-1"></i>{{ $tag->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-muted small">Chưa có dữ liệu thẻ/tag nào trong hệ thống.</div>
                                    @endforelse
                                </div>
                            </div>
                            @error('tags')
                                <div class="text-danger small fw-semibold mt-1"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-dark">Nội Dung Mô Tả Chi Tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6" 
                                      placeholder="Mô tả mục đích, lịch trình cụ thể, diễn giả, quyền lợi sinh viên tham gia... (Tối thiểu 50 ký tự)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback fw-semibold"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark d-block">Trạng Thái Đăng Tải <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4 p-3 bg-light border rounded-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_draft" value="draft" 
                                        {{ old('status', 'published') === 'draft' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-secondary" for="status_draft">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary p-2 rounded-2"><i class="bi bi-file-earmark-lock-fill me-1"></i> Lưu Bản Nháp</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_published" value="published" 
                                        {{ old('status', 'published') === 'published' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-success" for="status_published">
                                        <span class="badge bg-success-subtle text-success border border-success p-2 rounded-2"><i class="bi bi-globe-americas me-1"></i> Xuất Bản Ngay</span>
                                    </label>
                                </div>
                            </div>
                            @error('status')
                                <div class="text-danger small fw-semibold mt-1"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary px-4 py-2 fw-medium rounded-3">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold text-white rounded-3 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Hoàn Tất Khởi Tạo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Hàm JavaScript xử lý render hình ảnh xem trước lập tức (Image Preview) khi người dùng chọn file ảnh
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('outputPreview');
            var container = document.getElementById('imagePreviewContainer');
            output.src = reader.result;
            container.classList.remove('d-none');
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection