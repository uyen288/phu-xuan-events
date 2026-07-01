<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cổng Thông Tin Sự Kiện - PXU</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        /* Tùy chỉnh nút bấm của Slider theo tông màu đỏ PXU */
        .swiper-button-next,
        .swiper-button-prev {
            color: #b91c1c !important;
            background: rgba(255, 255, 255, 0.8);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px !important;
            font-weight: bold;
        }

        .swiper-pagination-bullet-active {
            background: #b91c1c !important;
        }

        /* Đảm bảo khối swiper chiếm trọn 100% chiều ngang */
        .swiper {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 flex flex-col min-h-screen m-0 p-0">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">

            <div class="flex items-center space-x-10">
                <a href="/">
                    <img src="{{ asset('image/logo-pxu.png') }}" alt="PXU University Logo"
                        class="h-14 w-auto object-contain">
                </a>

                <ul class="hidden md:flex items-center space-x-8 font-medium list-none m-0 p-0">
                    <li>
                        <a href="/" class="text-red-700 font-bold text-decoration-none transition-colors duration-200">
                            Trang Chủ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.index') }}"
                            class="text-gray-600 hover:text-red-700 text-decoration-none transition-colors duration-200">
                            Sự Kiện
                        </a>
                    </li>
                </ul>
            </div>

            <div class="flex items-center space-x-6 text-gray-600 font-medium">
                <button class="hover:text-red-700 bg-transparent border-0 p-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('events.index') }}"
                            class="text-red-700 font-semibold text-decoration-none hover:underline">
                            Vào Hệ Thống
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-red-700 text-white text-decoration-none px-4 py-2 rounded-lg hover:bg-red-800 transition-colors duration-200 shadow-sm">
                            Đăng nhập
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="bg-red-700 text-white text-decoration-none px-4 py-2 rounded-lg hover:bg-red-800 transition-colors duration-200 shadow-sm">
                                Đăng ký
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <div class="flex-grow">
        <div class="w-full bg-white">
            <div class="swiper mySwiper w-full">
                <div class="swiper-wrapper">
                    <div class="swiper-slide overflow-hidden">
                        <img src="{{ asset('image/slider1.jpg') }}" alt="Dai Hoc Phu Xuan"
                            class="w-full h-[450px] md:h-[600px] lg:h-[650px] object-cover">
                    </div>
                    <div class="swiper-slide overflow-hidden">
                        <img src="{{ asset('image/slider2.jpg') }}" alt="Sinh vien PXU"
                            class="w-full h-[450px] md:h-[600px] lg:h-[650px] object-cover">
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-10">
                <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Cổng Thông Tin Kết Nối Sự Kiện Sinh
                    Viên</h1>
                <p class="text-gray-600 mt-2">Khám phá, đăng ký và tham gia các hoạt động ngoại khóa, hội thảo khoa học
                    và ngày hội phong trào sôi nổi nhất.</p>
            </div>
        </main>
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
        });
    </script>
</body>

</html>