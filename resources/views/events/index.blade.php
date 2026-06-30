@extends('layouts.app')

@section('title', 'Danh Sách Sự Kiện')

@section('content')
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Khám Phá Sự Kiện</h2>
                <p class="text-muted mb-0">Tìm kiếm và đăng ký tham gia các sự kiện mới nhất tại trường.</p>
            </div>

            @auth
                @if(auth()->user()->role === 'organizer' || auth()->user()->role === 'admin')
                    <div>
                        <a href="{{ route('events.create') }}"
                            class="btn btn-primary px-4 py-2 fw-bold text-white rounded-3 shadow-sm">
                            <i class="bi bi-calendar-plus me-1"></i> Tạo Sự Kiện Mới
                        </a>
                    </div>
                @endif
            @endauth
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-5 bg-light">
            <div class="card-body p-3">
                <form action="{{ route('events.index') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Tìm tên sự kiện hoặc địa điểm..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">-- Tất cả danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100 fw-semibold">
                            <i class="bi bi-filter me-1"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden position-relative home-card">
                        <div class="position-relative" style="height: 180px; bg-secondary">
                            @if($event->banner)
                                <img src="{{ asset('storage/' . $event->banner) }}" class="w-100 h-100 object-fit-cover"
                                    alt="{{ $event->title }}">
                            @else
                                <div
                                    class="w-100 h-100 bg-secondary-subtle d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-calendar3 fs-1"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-3 badge bg-primary px-2 py-1.5 rounded-2 shadow-sm">
                                {{ $event->category->name ?? 'Sự kiện' }}
                            </span>
                        </div>

                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold text-dark text-truncate mb-2" title="{{ $event->title }}">
                                <a href="{{ route('events.show', $event->id) }}"
                                    class="text-decoration-none text-dark link-primary">
                                    {{ $event->title }}
                                </a>
                            </h5>

                            <p class="small text-muted mb-3">
                                <i class="bi bi-person-circle me-1"></i> Người đăng: <span
                                    class="fw-semibold">{{ $event->user->name ?? 'Ban Tổ Chức' }}</span>
                            </p>

                            <div class="small text-secondary mb-3">
                                <div class="mb-1">
                                    <i class="bi bi-clock me-2 text-primary"></i>
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H:i d/m/Y') }}
                                </div>
                                <div>
                                    <i class="bi bi-geo-alt me-2 text-danger"></i>
                                    <span class="text-truncate d-inline-block align-bottom"
                                        style="max-width: 85%;">{{ $event->location }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                @foreach($event->tags as $tag)
                                    <span class="badge bg-light text-secondary border me-1 small"><i
                                            class="bi bi-tag-fill me-1 text-muted"></i>{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div
                            class="card-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-between align-items-center">
                            <small class="fw-medium text-muted">
                                <i class="bi bi-people-fill text-dark me-1"></i>
                                {{ $event->registrations_count }} / {{ $event->capacity }} Chỗ
                            </small>
                            <a href="{{ route('events.show', $event->id) }}"
                                class="btn btn-outline-primary btn-sm px-3 rounded-2 fw-semibold">
                                Chi tiết <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="text-muted mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
                    <h5 class="text-secondary fw-semibold">Hiện tại chưa có sự kiện nào sắp diễn ra</h5>
                    <p class="text-muted small">Hãy quay lại sau hoặc tạo sự kiện mới nếu bạn có quyền Organizer.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $events->appends(request()->query())->links() }}
        </div>

    </div>
@endsection