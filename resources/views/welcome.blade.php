<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cổng Thông Tin Sự Kiện - PXU</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

<body class="antialiased bg-gray-50 flex flex-col min-h-screen">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            <div class="flex items-center">
                <a href="/">
                    <img src="{{ asset('image/logo-pxu.png') }}" alt="PXU University Logo"
                        class="h-14 w-auto object-contain">
                </a>
            </div>

            <div class="flex items-center space-x-6 text-gray-600 font-medium">
                <button class="hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('events.index') }}" class="text-red-700 font-semibold hover:underline">Vào Hệ
                            Thống</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-red-700">Đăng nhập</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="hover:text-red-700">
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

    <footer class="py-12 w-full mt-auto"
        style="background-color: #000000 !important; color: #ffffff !important; border-top: 1px solid #111827;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-xl font-bold uppercase tracking-wide mb-2" style="color: #ffffff !important;">Hệ Thống Quản
                Lý Sự Kiện Phu Xuan Events</h3>
            <p class="text-sm mt-2" style="color: #9ca3af !important;">Phòng Công tác sinh viên - Trường Đại học Phú
                Xuân</p>
            <p class="text-xs mt-1" style="color: #6b7280 !important;">Địa chỉ: 176 Hai Bà Trưng, Phường Vĩnh Ninh,
                Thành phố Huế</p>

            <div class="flex justify-center gap-6 mt-6">
                <a href="#" class="text-gray-400 hover:text-red-500 transition-colors">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 12.991 22 12z">
                        </path>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-red-500 transition-colors">
                    <span class="sr-only">Website</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </footer>

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