<nav x-data="{ open: false }" class="bg-[#204263] border-b-[4px] border-[#ea9d60]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Gauche : Logo et Liens -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @php
                        $dashboardRoute = 'dashboard';
                        if (Auth::user()->hasRole('admin')) $dashboardRoute = 'admin.dashboard';
                        elseif (Auth::user()->hasRole('candidat')) $dashboardRoute = 'candidat.dashboard';
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" class="flex items-center space-x-2 text-white hover:opacity-90 transition-opacity">
                        <i class="fa-solid fa-briefcase text-[#ea9d60] text-xl"></i>
                        <span class="font-bold text-xl tracking-tight">ACPE Reco</span>
                    </a>
                </div>

                <!-- Liens de navigation Desktop -->
                <div class="hidden sm:flex space-x-6">
                    @if(Auth::user()->hasRole('candidat'))
                        <a href="{{ route('candidat.dashboard') }}" class="{{ request()->routeIs('candidat.dashboard') ? 'text-white' : 'text-[#b1c3d4] hover:text-white' }} flex items-center px-3 py-2 text-sm font-semibold transition-colors">
                            <i class="fa-solid fa-gauge mr-2"></i> Tableau de bord
                        </a>
                        <a href="{{ route('candidat.reco.criteres') }}" class="{{ request()->routeIs('candidat.reco.criteres') ? 'text-white' : 'text-[#b1c3d4] hover:text-white' }} flex items-center px-3 py-2 text-sm font-semibold transition-colors">
                            <i class="fa-solid fa-chart-column mr-2"></i> Reco. par critères
                        </a>
                        <a href="{{ route('candidat.reco.professionnelle') }}" class="{{ request()->routeIs('candidat.reco.professionnelle') ? 'text-white' : 'text-[#b1c3d4] hover:text-white' }} flex items-center px-3 py-2 text-sm font-semibold transition-colors">
                            <i class="fa-solid fa-briefcase mr-2"></i> Reco. professionnelle
                        </a>
                        <a href="{{ route('candidat.profil.edit') }}" class="{{ request()->routeIs('candidat.profil.edit') ? 'text-white' : 'text-[#b1c3d4] hover:text-white' }} flex items-center px-3 py-2 text-sm font-semibold transition-colors">
                            <i class="fa-solid fa-user-circle mr-2"></i> Mon profil
                        </a>

                    @elseif(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-[#b1c3d4] hover:text-white' }} flex items-center px-3 py-2 text-sm font-semibold transition-colors">
                            <i class="fa-solid fa-gauge mr-2"></i> Tableau de bord
                        </a>
                    @endif
                </div>
            </div>

            <!-- Droite : Profil et Déconnexion (Desktop) -->
            <div class="hidden sm:flex items-center space-x-6 text-sm font-medium">
                <span class="text-[#b1c3d4]">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-[#b1c3d4] hover:text-white transition-colors flex items-center">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Déconnexion
                    </button>
                </form>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#b1c3d4] hover:text-white hover:bg-[#1a3550] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#1a3550] border-t border-[#152a40]">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->hasRole('candidat'))
                <a href="{{ route('candidat.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-[#b1c3d4] hover:text-white hover:bg-[#152a40] hover:border-[#ea9d60]">Tableau de bord</a>
                <a href="{{ route('candidat.reco.criteres') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-[#b1c3d4] hover:text-white hover:bg-[#152a40] hover:border-[#ea9d60]">Reco. par critères</a>
                <a href="{{ route('candidat.reco.professionnelle') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-[#b1c3d4] hover:text-white hover:bg-[#152a40] hover:border-[#ea9d60]">Reco. professionnelle</a>
                <a href="{{ route('candidat.profil.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-[#b1c3d4] hover:text-white hover:bg-[#152a40] hover:border-[#ea9d60]">Mon profil</a>
            @endif
        </div>
        <div class="pt-4 pb-1 border-t border-[#152a40]">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                <div class="font-medium text-sm text-[#b1c3d4]">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-[#b1c3d4] hover:text-white hover:bg-[#152a40] hover:border-[#ea9d60]">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
