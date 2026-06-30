<footer class="bg-dark text-white pt-5 pb-4 mt-5 border-top border-secondary">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-primary">Phú Xuân Events</h5>
                <p class="small text-secondary">
                    Hệ thống quản lý và đăng ký tham gia sự kiện nội bộ dành riêng cho Sinh viên trường Đại học Phú
                    Xuân.
                </p>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-primary">Liên Kết</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('events.index') }}"
                            class="text-secondary text-decoration-none hover-link">Sự Kiện</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none hover-link">Tin Tức</a></li>
                </ul>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3 small text-secondary">
                <h5 class="text-uppercase mb-4 fw-bold text-primary">Liên Hệ</h5>
                <p><i class="bi bi-geo-alt-fill me-2"></i> Campus Phú Xuân, Huế</p>
                <p><i class="bi bi-envelope-fill me-2"></i> lienhe@phuxuan.edu.vn</p>
            </div>
        </div>

        <hr class="mb-4 opacity-25">

        <div class="row align-items-center">
            <div class="col-md-12 text-center">
                <p class="small text-secondary mb-0">
                    © {{ date('Y') }} <strong>Phú Xuân Events</strong>. Phát triển bởi Sinh Viên Kỷ Nguyên Số.
                </p>
            </div>
        </div>
    </div>
</footer>