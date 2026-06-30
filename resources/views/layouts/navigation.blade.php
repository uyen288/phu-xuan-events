<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex items-center space-x-10">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('image/logo-pxu.png') }}" alt="PXU Logo" class="h-14 w-auto object-contain">
                </a>

                <ul class="hidden md:flex items-center space-x-8 font-medium">
                    <li>
                        <a href="/"
                            class="{{ request()->is('/') ? 'text-red-700 font-bold' : 'text-gray-600 hover:text-red-700' }} transition-colors duration-200">
                            Trang Chủ
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('events.index') }}"
                            class="{{ request()->routeIs('events.index') ? 'text-red-700 font-bold' : 'text-gray-600 hover:text-red-700' }} transition-colors duration-200">
                            Sự Kiện
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->isOrganizer() || auth()->user()->isAdmin())
                            <li>
                                <a href="{{ route('events.create') }}"
                                    class="{{ request()->routeIs('events.create') ? 'text-red-700 font-bold' : 'text-gray-600 hover:text-red-700' }} transition-colors duration-200">
                                    Tạo Sự Kiện
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isStudent())
                            <li>
                                <a href="{{ route('my-events') }}"
                                    class="{{ request()->routeIs('my-events') ? 'text-red-700 font-bold' : 'text-gray-600 hover:text-red-700' }} transition-colors duration-200">
                                    Sự Kiện Của Tôi
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="{{ request()->routeIs('dashboard') ? 'text-red-700 font-bold' : 'text-gray-600 hover:text-red-700' }} transition-colors duration-200">
                                    Quản Trị
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="{{ request()->routeIs('admin.users.*') ? 'text-red-700 font-bold' : 'text-gray-600 hover:text-red-700' }} transition-colors duration-200">
                                    Quản Lý Users
                                </a>
                            </li>
                        @endif
                    @endauth

                </ul>
            </div>

            <div class="flex items-center space-x-6 font-medium">
                @auth
                    <div class="flex items-center space-x-2 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                        <span class="text-gray-800 font-semibold">{{ auth()->user()->name }}</span>
                        <span
                            class="text-[10px] uppercase tracking-wider font-bold px-1.5 py-0.5 rounded bg-red-50 text-red-700 border border-red-100">
                            {{ auth()->user()->role }}
                        </span>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="text-gray-600 hover:text-red-700 transition-colors duration-200">
                        Hồ sơ
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                        @csrf
                        <button type="submit"
                            class="text-red-600 hover:text-red-800 font-semibold transition-colors duration-200">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-red-700 transition-colors duration-200">
                        Đăng nhập
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 transition-colors duration-200 shadow-sm">
                            Đăng ký
                        </a>
                    @endif
                @endauth
            </div>

        </div>
    </div>
</nav>