<x-admin-layout>
    @section('title', 'Sécurité & Accès')

    <div class="space-y-8 animate-slide-up">
        <!-- Strategic Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-[#204263] tracking-tight">Sécurité & Autorisations</h1>
                <p class="text-gray-400 text-sm mt-1">Contrôlez les accès critiques et la hiérarchie des privilèges système.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.audit.permissions') }}" class="px-5 py-2.5 bg-white border border-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Journal d'audit
                </a>
                <a href="{{ route('admin.roles.matrix') }}" class="px-5 py-2.5 bg-[#204263] text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-blue-900/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-shield-halved mr-2"></i> Matrice des accès
                </a>
            </div>
        </div>

        <!-- Security Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-10 w-10 bg-blue-50 text-acpe-blue rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-users-gear text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md">Actif</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Utilisateurs avec rôle</p>
                <h3 class="text-2xl font-black text-[#204263]">{{ $roles->sum('users_count') }}</h3>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-10 w-10 bg-orange-50 text-acpe-orange rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-key text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 bg-gray-50 px-2 py-1 rounded-md">Système</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Permissions définies</p>
                <h3 class="text-2xl font-black text-[#204263]">{{ \Spatie\Permission\Models\Permission::count() }}</h3>
            </div>

            <div class="md:col-span-2 bg-gradient-to-br from-[#204263] to-[#1a3550] p-6 rounded-3xl text-white relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="h-2 w-2 bg-emerald-400 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-white/60">État de la plateforme</span>
                    </div>
                    <h3 class="text-lg font-bold mb-1 text-white">Intégrité des Rôles : Optimale</h3>
                    <p class="text-xs text-white/60">Tous les accès sont synchronisés avec les politiques de sécurité v2.0</p>
                </div>
                <i class="fa-solid fa-fingerprint absolute -right-4 -bottom-4 text-white/5 text-8xl"></i>
            </div>
        </div>

        <!-- Roles Canvas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($roles as $role)
            @php
                $roleTheme = match($role->name) {
                    'admin' => ['icon' => 'fa-user-shield', 'bg' => 'blue-50', 'text' => 'blue-500', 'desc' => 'Contrôle total des infrastructures et utilisateurs.'],
                    'recruteur' => ['icon' => 'fa-briefcase', 'bg' => 'orange-50', 'text' => 'orange-500', 'desc' => 'Gestion des offres, talents et abonnements entreprise.'],
                    'demandeur' => ['icon' => 'fa-user-graduate', 'bg' => 'emerald-50', 'text' => 'emerald-500', 'desc' => 'Recherche d\'emploi, profil public et candidatures.'],
                    default => ['icon' => 'fa-user', 'bg' => 'gray-50', 'text' => 'gray-500', 'desc' => 'Rôle utilisateur standard avec accès limités.']
                };
            @endphp
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-500 hover:-translate-y-1">
                <div class="p-8 flex-1">
                    <div class="flex items-start justify-between mb-8">
                        <div class="h-14 w-14 rounded-2xl bg-{{ $roleTheme['bg'] }} text-{{ $roleTheme['text'] }} flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform duration-500">
                            <i class="fa-solid {{ $roleTheme['icon'] }}"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-[20px] font-black text-[#204263] block leading-none mb-1">{{ $role->users_count ?? 0 }}</span>
                            <span class="text-[9px] font-black text-gray-300 uppercase tracking-tighter">Utilisateurs</span>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-[#204263] mb-3 capitalize tracking-tight">{{ $role->name }}</h3>
                    <p class="text-xs text-gray-400 leading-relaxed mb-8">{{ $roleTheme['desc'] }}</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Privilèges Actifs</p>
                            <span class="text-[10px] font-bold text-{{ $roleTheme['text'] }} bg-{{ $roleTheme['bg'] }} px-2 py-0.5 rounded-full">{{ $role->permissions->count() }}</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($role->permissions->take(6) as $permission)
                                <span class="px-2.5 py-1.5 bg-gray-50/80 text-[#204263] text-[9px] font-black rounded-lg border border-gray-100 uppercase tracking-tighter group-hover:bg-white transition-colors">{{ str_replace('_', ' ', $permission->name) }}</span>
                            @endforeach
                            @if($role->permissions->count() > 6)
                                <span class="px-2.5 py-1.5 bg-[#204263] text-white text-[9px] font-black rounded-lg">+{{ $role->permissions->count() - 6 }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex items-center justify-between group-hover:bg-white transition-colors duration-500">
                    <button class="flex items-center space-x-2 text-[10px] font-black text-acpe-blue uppercase tracking-widest hover:text-acpe-dark-blue transition-colors">
                        <i class="fa-solid fa-sliders text-xs"></i>
                        <span>Configurer les accès</span>
                    </button>
                    <div class="h-8 w-8 rounded-xl bg-white border border-gray-100 text-gray-300 flex items-center justify-center hover:text-red-500 hover:border-red-100 transition-all cursor-pointer">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Modern Security Policy Box -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-10 overflow-hidden relative">
            <div class="absolute top-0 right-0 h-40 w-40 bg-blue-50/50 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="space-y-4">
                    <div class="h-12 w-12 bg-acpe-orange/10 text-acpe-orange rounded-2xl flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-[#204263] tracking-tight">Vérification de Conformité</h2>
                        <p class="text-sm text-gray-400 mt-2 max-w-lg leading-relaxed">Assurez-vous que chaque rôle respecte le principe du moindre privilège pour minimiser les risques de sécurité sur la plateforme ACPE.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4">
                    <button class="px-8 py-4 bg-white border border-gray-100 text-[#204263] text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-gray-50 transition-all shadow-sm">
                        Calculer le risque
                    </button>
                    <button class="px-8 py-4 bg-acpe-orange text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-orange-500/20 hover:scale-105 transition-all">
                        Lancer un audit complet
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
