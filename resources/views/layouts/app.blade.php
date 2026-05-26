<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{ config('app.name', 'PT Mitsuba Indonesia') }}</title>

        <link rel="icon" href="{{ asset('img/logomitsuba.svg') }}" type="image/svg+xml">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
        <div class="min-h-screen flex">
            <!-- Sidebar Navigation -->
            <aside
                class="fixed inset-y-0 left-0 z-40 flex flex-col bg-slate-900 text-slate-400 border-r border-slate-800 transition-all duration-300 transform md:translate-x-0 md:static shrink-0"
                :class="{
                    'translate-x-0': sidebarOpen,
                    '-translate-x-full': !sidebarOpen,
                    'w-[280px]': !sidebarCollapsed,
                    'w-[80px]': sidebarCollapsed
                }"
            >
                <!-- Brand logo area -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800 overflow-hidden bg-slate-950/40">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/logomitsuba.svg') }}" alt="Logo Mitsuba" class="w-9 h-9 object-contain" />
                        <div class="transition-all duration-300 whitespace-nowrap" x-show="!sidebarCollapsed">
                            <span class="text-white font-bold text-base tracking-tight">PT Mitsuba Indonesia</span>
                            <span class="block text-[10px] font-semibold text-primary-500 uppercase tracking-wider">Press-3 Dept</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation link groups -->
                <nav class="flex-1 px-4 py-6 space-y-7 overflow-y-auto">
                    <!-- Dashboard link -->
                    <div class="space-y-1.5">
                        @if(auth()->user()->hasRole('admin', 'operator', 'leader', 'assistant_manager'))
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                               title="Dashboard"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                                </svg>
                                <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Dashboard</span>
                            </a>
                        @endif
                    </div>

                    <!-- Produksi Section -->
                    <div class="space-y-1.5">
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2" x-show="!sidebarCollapsed">
                            Produksi
                        </div>
                        <a href="{{ route('produksis.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('produksis.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                           title="Produksi Harian"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Produksi Harian</span>
                        </a>

                        @if(auth()->user()->hasRole('admin', 'leader', 'assistant_manager'))
                            <a href="{{ route('laporans.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('laporans.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                               title="Laporan Produksi"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Laporan Harian</span>
                            </a>
                        @endif
                    </div>

                    <!-- Admin & Master Section -->
                    @if(auth()->user()->isAdmin())
                        <div class="space-y-1.5">
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2" x-show="!sidebarCollapsed">
                                Master Data
                            </div>
                            <a href="{{ route('master.shifts.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('master.shifts.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                               title="Data Shift"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Data Shift</span>
                            </a>
                            <a href="{{ route('master.mesins.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('master.mesins.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                               title="Data Mesin"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Data Mesin</span>
                            </a>
                            <a href="{{ route('master.parts.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('master.parts.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                               title="Data Part"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Data Part</span>
                            </a>
                            <a href="{{ route('master.kategori-ngs.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('master.kategori-ngs.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                               title="Kategori NG"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Kategori NG</span>
                            </a>
                        </div>

                        <div class="space-y-1.5">
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2" x-show="!sidebarCollapsed">
                                Keamanan
                            </div>
                            <a href="{{ route('users.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}"
                               title="Kelola User"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="whitespace-nowrap" x-show="!sidebarCollapsed">Kelola User</span>
                            </a>
                        </div>
                    @endif
                </nav>

                <!-- Sidebar footer & Collapse trigger -->
                <div class="p-4 border-t border-slate-800 bg-slate-950/20 flex items-center justify-between overflow-hidden">
                    <button
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        class="hidden md:inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white transition-colors duration-200"
                        title="Collapse Sidebar"
                    >
                        <svg class="w-5 h-5 transform transition-transform duration-200" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </aside>

            <!-- Mobile Sidebar Overlay Backdrop -->
            <div
                x-show="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm z-20 md:hidden"
                style="display: none;"
            ></div>

            <!-- Page Container -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Top Header Bar -->
                <header class="h-16 bg-white border-b border-slate-200/60 px-6 flex items-center justify-between sticky top-0 z-10 shadow-sm/5 bg-opacity-90 backdrop-blur-md">
                    <!-- Left top bar -->
                    <div class="flex items-center gap-4">
                        <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:bg-slate-50 border border-slate-200 md:hidden"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="hidden sm:block">
                            <h2 class="text-sm font-semibold text-slate-700 tracking-tight">@yield('page-title', 'Sistem Informasi Produksi')</h2>
                        </div>
                    </div>

                    <!-- Right top bar -->
                    <div class="flex items-center gap-4">
                        <!-- User display info -->
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                            <div class="text-right hidden md:block">
                                <span class="block text-sm font-bold text-slate-800 leading-none mb-1">{{ auth()->user()->nama }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-primary-50 text-primary-700 uppercase border border-primary-200/50 tracking-wider">
                                    {{ auth()->user()->role->label() }}
                                </span>
                            </div>

                            <!-- User Profile initials circle -->
                            @php
                                $roleIcon = match(auth()->user()->role->value) {
                                    'admin' => 'admin.png',
                                    'assistant_manager' => 'asmen.png',
                                    'leader' => 'leader.png',
                                    'operator' => 'operator.png',
                                    default => 'operator.png'
                                };
                            @endphp
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center overflow-hidden shadow-sm border border-slate-200">
                                <img src="{{ asset('img/' . $roleIcon) }}" alt="Profile" class="w-full h-full object-cover">
                            </div>

                            <!-- Logout form -->
                            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-2xl border border-slate-200 hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors duration-200 shadow-sm"
                                    title="Keluar Aplikasi"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page content -->
                <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto overflow-y-auto">
                    {{ $slot }}
                </main>

                <footer class="border-t border-slate-200/60 bg-white/70 backdrop-blur-md">
                    <div class="max-w-7xl mx-auto px-6 md:px-8 py-4 text-xs text-slate-500 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <div class="font-semibold text-slate-600">PT Mitsuba Indonesia Press-3 Department</div>
                        <div>Copyright © {{ date('Y') }}. All rights reserved.</div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Session SweetAlert Notification handler -->
        @if(session()->has('swal_msg'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const data = @json(session()->pull('swal_msg'));
                    Swal.fire({
                        title: data.title,
                        text: data.text,
                        icon: data.icon,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4f46e5',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm shadow-sm'
                        }
                    });
                });
            </script>
        @endif

        @stack('scripts')
    </body>
</html>
