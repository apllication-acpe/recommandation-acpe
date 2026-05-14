<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Admin') | {{ config('app.name', 'ACPE') }}</title>

        {{-- Favicon ACPE --}}
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'acpe-blue': '#204263',
                            'acpe-orange': '#eda268',
                            'acpe-light-blue': '#7a9bb8',
                            'acpe-dark-blue': '#1a3550',
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>

        <style>
            [x-cloak] { display: none !important; }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-up { animation: slideUp 0.4s ease-out forwards; }
            
            /* Custom Scrollbar for sidebar */
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: window.innerWidth > 1024 }" @resize.window="sidebarOpen = window.innerWidth > 1024">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-40 transition-opacity lg:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak></div>
        
        <!-- Sidebar -->
        <aside 
            class="fixed left-0 top-0 h-full bg-[#204263] text-white transition-all duration-300 z-50 shadow-2xl flex flex-col"
            :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-20 lg:translate-x-0 -translate-x-full'"
        >
            <!-- Logo Area -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-white/5">
                <div class="flex items-center">
                    <i class="fa-solid fa-briefcase text-acpe-orange text-2xl"></i>
                    <span x-show="sidebarOpen" class="ml-3 font-extrabold text-xl tracking-tight whitespace-nowrap">ACPE Admin</span>
                </div>
                <!-- Close button for mobile inside sidebar -->
                <button @click="sidebarOpen = false" class="lg:hidden text-white/50 hover:text-white" x-show="sidebarOpen">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 mt-4 px-3 space-y-1 overflow-y-auto custom-scrollbar">
                
                <!-- Tableau de bord -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white shadow-lg' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Tableau de bord</span>
                </a>

                <div x-show="sidebarOpen" class="px-4 pt-4 pb-2 text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">Gestion</div>

                <!-- Demandeurs -->
                <a href="{{ route('admin.candidats') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.candidats*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-user-group text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Demandeurs</span>
                </a>

                <!-- Entreprises -->
                <a href="{{ route('admin.entreprises') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.entreprises*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-building text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Entreprises</span>
                </a>

                <!-- Offres & Candidatures -->
                <a href="{{ route('admin.offres') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.offres') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-briefcase text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Offres d'emploi</span>
                </a>
                
                <a href="{{ route('admin.offres.acpe') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.offres.acpe') ? 'bg-white/10 text-white shadow-lg' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-spider text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm flex items-center gap-2">
                        ACPE.CG <span class="bg-[#eda268] text-white text-[8px] px-1.5 py-0.5 rounded-md uppercase tracking-wider font-black">Live</span>
                    </span>
                </a>

                <a href="{{ route('admin.candidatures') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.candidatures*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-file-contract text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Candidatures</span>
                </a>

                <!-- Messagerie -->
                <a href="{{ route('admin.messagerie') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.messagerie*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-envelope text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Messagerie</span>
                </a>

                <div x-show="sidebarOpen" class="px-4 pt-4 pb-2 text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">Outils & IA</div>

                <!-- Recommandation IA -->
                <a href="{{ route('admin.recommandation.analyse') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.recommandation*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-brain text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Recommandation IA</span>
                </a>

                <!-- Modération -->
                <a href="{{ route('admin.moderation.signalements') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.moderation*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-shield-halved text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Modération</span>
                </a>

                <div x-show="sidebarOpen" class="px-4 pt-4 pb-2 text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">Système</div>

                <!-- Configuration -->
                <a href="{{ route('admin.config') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.config*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-gears text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Configuration</span>
                </a>
                
                <!-- Utilisateurs -->
                <a href="{{ route('admin.utilisateurs') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.utilisateurs') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-users text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Utilisateurs</span>
                </a>

                <!-- Support client -->
                <a href="{{ route('admin.support') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.support') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-headset text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Support client</span>
                </a>

                <!-- Rôles -->
                <a href="{{ route('admin.roles') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.roles') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-user-shield text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Rôles & Accès</span>
                </a>

            </nav>

            <!-- Bottom Profile / Logout -->
            <div class="p-4 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center px-4 py-3 rounded-xl hover:bg-red-500/10 text-red-300 hover:text-red-400 transition-all group">
                        <i class="fa-solid fa-arrow-right-from-bracket text-lg w-8"></i>
                        <span x-show="sidebarOpen" class="font-semibold text-sm">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main 
            class="transition-all duration-300 min-h-screen flex flex-col"
            :class="sidebarOpen ? 'lg:pl-64 pl-0' : 'lg:pl-20 pl-0'"
        >
            <!-- Topbar -->
            <header class="h-16 bg-[#204263] lg:bg-white border-b border-white/5 lg:border-gray-100 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-30 lg:text-gray-600 text-white">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-white/10 lg:hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
                
                <div class="flex items-center space-x-4 lg:space-x-6">
                    <!-- Notifications -->
                    <a href="{{ route('admin.messagerie') }}" class="relative cursor-pointer hover:opacity-80 transition-opacity text-white/70 lg:text-gray-400 lg:hover:text-[#204263]">
                        <i class="fa-regular fa-bell text-xl"></i>
                        @php $unreadCount = Auth::user()->unreadMessagesCount(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 h-4 w-4 bg-[#eda268] text-[9px] font-black text-white flex items-center justify-center rounded-full border-2 border-[#204263] lg:border-white">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    
                    <!-- User Profile Dropdown -->
                    <div x-data="{ profileOpen: false }" class="relative border-l border-white/10 lg:border-gray-100 pl-4">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center space-x-3 text-left hover:opacity-80 transition-opacity">
                            <div class="h-9 w-9 rounded-full bg-white p-0.5 shadow-sm">
                                <div class="h-full w-full rounded-full bg-gray-200 overflow-hidden flex items-center justify-center text-[#204263] font-bold text-sm">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                                    @else
                                        {{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}
                                    @endif
                                </div>
                            </div>
                            <div class="hidden md:block text-white lg:text-gray-800">
                                <p class="text-xs font-bold leading-none">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                                <p class="text-[9px] font-bold text-acpe-light-blue lg:text-gray-400 uppercase tracking-widest mt-1">Administrateur</p>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-white/50 lg:text-gray-400"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="profileOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 overflow-hidden text-gray-700" x-cloak>
                            <div class="px-4 py-3 border-b border-gray-50 mb-1 lg:hidden">
                                <p class="text-xs font-bold text-[#204263]">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">Administrateur</p>
                            </div>
                            <a href="{{ route('admin.utilisateurs.edit', Auth::user()) }}" class="flex items-center px-4 py-2.5 text-xs font-bold hover:bg-gray-50 hover:text-acpe-blue transition-colors">
                                <i class="fa-solid fa-user-pen w-5 text-gray-400"></i> Mon Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-50 mt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 transition-colors text-left">
                                    <i class="fa-solid fa-power-off w-5"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </main>

        @stack('scripts')
    </body>
</html>
