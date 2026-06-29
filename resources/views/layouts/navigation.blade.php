<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('events.index') }}" class="text-xl font-bold text-gray-800">
                    {{ config('app.name') }}
                </a>

                <!-- Navigation -->
                <ul class="hidden md:flex items-center space-x-6">
                    <li>
                        <a href="{{ route('events.index') }}"
                           class="{{ request()->routeIs('events.index') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                            Home
                        </a>
                    </li>

                    @if(auth()->user()->role == 'admin')
                        <li>
                            <a href="{{ route('dashboard') }}"
                               class="{{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                Dashboard
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Right -->
            <div class="flex items-center space-x-4">

                <span class="text-gray-700">
                    {{ Auth::user()->name }}
                </span>

                <a href="{{ route('profile.edit') }}"
                   class="text-gray-700 hover:text-blue-600">
                    Profile
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button
                        type="submit"
                        class="text-red-600 hover:text-red-800">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>
