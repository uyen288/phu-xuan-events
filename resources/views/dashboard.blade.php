@section('content')
    <div class="container-fluid px-4 py-4">
        <h2 class="mb-4 font-weight-bold text-dark">Tổng Quan Hệ Thống Báo Cáo - Thống Kê</h2>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm py-2 px-3 small mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm py-2 px-3 small mb-4 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="card-title text-uppercase text-white-50 small font-weight-bold">Tổng số sinh viên
                            </h6>
                            <h2 class="display-5 font-weight-bold mb-0">{{ $totalStudents }}</h2>
                        </div>
                        <p class="card-text small mt-3 text-white-50">Tài khoản sinh viên hoạt động</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="card-title text-uppercase text-white-50 small font-weight-bold">Tổng lượt đăng ký
                            </h6>
                            <h2 class="display-5 font-weight-bold mb-0">{{ $totalRegistrations }}</h2>
                        </div>
                        <p class="card-text small mt-3 text-white-50">Tổng số đơn đăng ký hệ thống</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-dark h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="card-title text-uppercase text-muted small font-weight-bold">Đơn chờ duyệt</h6>
                            <h2 class="display-5 font-weight-bold mb-0">{{ $pendingRegistrations }}</h2>
                        </div>
                        <p class="card-text small mt-3 text-muted">Yêu cầu cần phê duyệt gấp</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-info text-white h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="card-title text-uppercase text-white-50 small font-weight-bold">Đơn đã duyệt</h6>
                            <h2 class="display-5 font-weight-bold mb-0">{{ $confirmedRegistrations }}</h2>
                        </div>
                        <p class="card-text small mt-3 text-white-50">Sinh viên đã được xác nhận</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-5">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="bi bi-grid-3x3-gap-fill text-primary"></i>
                    Quản Lý Danh Sách Sự Kiện & Xuất Báo Cáo</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 80px;">ID</th>
                                <th>Tên Sự Kiện</th>
                                <th>Địa Điểm</th>
                                <th class="text-center">Đã Đăng Ký / Sức Chứa</th>
                                <th>Trạng Thái</th>
                                <th class="text-center" style="width: 200px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">{{ $event->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $event->title }}</div>
                                        <span class="text-muted small">Bắt đầu:
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td><i class="bi bi-geo-alt text-danger"></i> {{ $event->location }}</td>

                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center">
                                            <i class="bi bi-people text-muted me-1"></i>
                                            <span class="fw-bold text-dark">{{ $event->registrations_count ?? 0 }}</span>
                                            <span class="text-muted">/{{ $event->capacity }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        @if($event->status == 'published')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Đã xuất
                                                bản</span>
                                        @elseif($event->status == 'draft')
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Bản
                                                nháp</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Đã
                                                hủy</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.export.csv', $event->id) }}"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-file-earmark-spreadsheet"></i> Tải file CSV
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Chưa có sự kiện nào trong cơ sở dữ liệu để xuất báo cáo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-5">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="bi bi-card-checklist text-warning"></i>
                    Duyệt Đơn Đăng Ký Tham Gia</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 80px;">ID</th>
                                <th>Sinh Viên</th>
                                <th>Sự Kiện</th>
                                <th>Ngày Đăng Ký</th>
                                <th>Trạng Thái</th>
                                <th class="text-center" style="width: 220px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $reg)
                                <tr>
                                    <td class="ps-4 text-secondary fw-bold">#{{ $reg->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $reg->user ? $reg->user->name : 'N/A' }}</div>
                                        <div class="text-muted small">{{ $reg->user ? $reg->user->email : '' }}</div>
                                    </td>
                                    <td class="text-wrap" style="max-width: 250px;">
                                        <div class="text-dark fw-semibold text-primary">
                                            {{ $reg->event ? $reg->event->title : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($reg->status == 'confirmed')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Đã
                                                duyệt</span>
                                        @elseif($reg->status == 'pending')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Chờ
                                                duyệt</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Đã
                                                hủy</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($reg->status == 'pending')
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('admin.registrations.approve', $reg->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-lg"></i> Duyệt
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.registrations.reject', $reg->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn từ chối đơn này không?')">
                                                        <i class="bi bi-x-lg"></i> Từ chối
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted small"><i class="bi bi-lock-fill"></i> Đã xử lý</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Chưa có đơn đăng ký nào trong hệ thống.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
