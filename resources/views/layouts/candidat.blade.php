<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ACPE Candidat') }}</title>

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
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        </style>
    </head>
    <body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true }">
        
        <!-- Sidebar -->
        <aside 
            class="fixed left-0 top-0 h-full bg-[#204263] text-white transition-all duration-300 z-50 shadow-2xl flex flex-col"
            :class="sidebarOpen ? 'w-64' : 'w-20'"
        >
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-white/5">
                <i class="fa-solid fa-graduation-cap text-acpe-orange text-2xl"></i>
                <span x-show="sidebarOpen" class="ml-3 font-extrabold text-xl tracking-tight whitespace-nowrap">ACPE Demandeur</span>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 mt-6 px-3 space-y-1 overflow-y-auto custom-scrollbar">
                <!-- Tableau de bord -->
                <a href="{{ route('candidat.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.dashboard') ? 'bg-white/10 text-[#eda268]' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Tableau de bord</span>
                </a>

                <!-- Matching & Recommandations -->
                <div class="pt-4 pb-2 px-4">
                    <p x-show="sidebarOpen" class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em]">Matching & Recommandations</p>
                    <div x-show="!sidebarOpen" class="h-px bg-white/10 w-full"></div>
                </div>

                <a href="{{ route('candidat.reco.criteres') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.reco.criteres') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-sliders text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Reco. par critères</span>
                </a>

                <a href="{{ route('candidat.reco.professionnelle') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.reco.professionnelle') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-wand-magic-sparkles text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Reco. professionnelle</span>
                </a>

                <!-- Recherche d'offres -->
                <div class="pt-4 pb-2 px-4">
                    <p x-show="sidebarOpen" class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em]">Espace Demandeur</p>
                    <div x-show="!sidebarOpen" class="h-px bg-white/10 w-full"></div>
                </div>

                <a href="{{ route('candidat.offres.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.offres.index') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-magnifying-glass text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Recherche d'offres</span>
                </a>

                <!-- Mes Candidatures -->
                <a href="{{ route('candidat.candidatures') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.candidatures') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-file-signature text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Mes Candidatures</span>
                </a>

                <!-- Mes Favoris -->
                <a href="{{ route('candidat.favoris') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.favoris') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-heart text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Mes Favoris</span>
                </a>

                <!-- Messagerie -->
                <a href="{{ route('candidat.messagerie') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.messagerie') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-comments text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Messagerie Demandeur</span>
                </a>

                <div class="pt-4 pb-2 px-4">
                    <p x-show="sidebarOpen" class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em]">Mon Compte</p>
                    <div x-show="!sidebarOpen" class="h-px bg-white/10 w-full"></div>
                </div>

                <!-- Profil & CV -->
                <a href="{{ route('candidat.profil.edit') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.profil.edit') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-id-card text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Mon Profil & CV</span>
                </a>

                <!-- Alertes Emploi -->
                <a href="{{ route('candidat.alertes') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidat.alertes') ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-[#b1c3d4] hover:text-white' }}">
                    <i class="fa-solid fa-bell text-lg w-8"></i>
                    <span x-show="sidebarOpen" class="font-semibold text-sm">Alertes Emploi</span>
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
            class="transition-all duration-300 min-h-screen"
            :class="sidebarOpen ? 'pl-64' : 'pl-20'"
        >
            <!-- Topbar -->
            <header class="h-16 bg-[#204263] border-b border-white/5 flex items-center justify-between px-6 sticky top-0 z-40 text-white">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- Help -->
                    <a href="#" class="text-sm font-medium text-[#b1c3d4] hover:text-white transition-colors hidden md:block">Besoin d'aide ?</a>
                    
                    <!-- Notifications -->
                    <a href="{{ route('candidat.messagerie') }}" class="relative cursor-pointer hover:opacity-80 transition-opacity text-white/70 hover:text-white">
                        <i class="fa-regular fa-bell text-xl"></i>
                        @php $unreadCount = Auth::user()->unreadMessagesCount(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 h-4 w-4 bg-[#eda268] text-[9px] font-black text-white flex items-center justify-center rounded-full border-2 border-[#204263]">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    
                    <!-- User Profile -->
                    <a href="{{ route('candidat.profil.edit') }}" class="flex items-center space-x-3 pl-4 border-l border-white/10 group">
                        <div class="h-9 w-9 rounded-full bg-white p-0.5 shadow-lg group-hover:scale-105 transition-transform">
                            <div class="h-full w-full rounded-full bg-gray-200 overflow-hidden flex items-center justify-center text-[#204263] font-bold text-sm">
                                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="h-full w-full object-cover">
                            </div>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-xs font-bold leading-none group-hover:text-[#eda268] transition-colors">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                            <p class="text-[9px] font-bold text-acpe-light-blue uppercase tracking-widest mt-1">Demandeur</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
