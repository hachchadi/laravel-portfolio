<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        @auth
            @if(auth()->user()->isAdmin())
                <nav class="bg-white shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">
                                        Admin Panel
                                    </a>
                                </div>

                                <!-- Navigation Links -->
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('admin.profile') }}" 
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.profile') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Profile
                                    </a>
                                    <a href="{{ route('admin.projects') }}" 
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.projects') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Projects
                                    </a>
                                    <a href="{{ route('admin.skills') }}" 
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.skills') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        Skills
                                    </a>
                                </div>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                                <div class="ml-3 relative">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Hamburger -->
                            <div class="-mr-2 flex items-center sm:hidden">
                                <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </nav>
            @endif
        @endauth

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>
    </div>

    @livewireScripts
</body>
</html>