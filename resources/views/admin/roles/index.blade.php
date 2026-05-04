<x-admin-layout>
    @section('title', 'Gestion des Rôles & Permissions')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Rôles & Permissions</h1>
                <p class="text-gray-400 text-sm">Gérez les niveaux d'accès et les autorisations des utilisateurs.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-6 py-2 bg-acpe-blue text-white text-sm font-bold rounded-xl shadow-lg shadow-acpe-blue/10 hover:bg-acpe-dark-blue transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Nouveau Rôle
                </button>
            </div>
        </div>

        <!-- Roles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($roles as $role)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:border-acpe-blue/20 transition-all">
                <div class="p-8 flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[10px] font-black uppercase rounded-lg border border-gray-100">
                            {{ $role->users_count ?? 0 }} Utilisateurs
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-[#204263] mb-2 capitalize">{{ $role->name }}</h3>
                    <p class="text-xs text-gray-400 mb-6">Accès complet aux modules {{ $role->name === 'admin' ? 'système, utilisateurs et configuration' : ($role->name === 'recruteur' ? 'offres et candidatures' : 'recherche et profil') }}.</p>
                    
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Permissions Clés</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($role->permissions->take(5) as $permission)
                                <span class="px-2 py-1 bg-gray-50 text-[#7a9bb8] text-[9px] font-bold rounded-md border border-gray-100">{{ $permission->name }}</span>
                            @endforeach
                            @if($role->permissions->count() > 5)
                                <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[9px] font-bold rounded-md border border-gray-100">+{{ $role->permissions->count() - 5 }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between">
                    <button class="text-[10px] font-black text-acpe-blue uppercase hover:underline">Modifier les accès</button>
                    <div class="flex items-center space-x-2">
                        <button class="h-8 w-8 rounded-lg bg-white border border-gray-100 text-gray-400 hover:text-red-500 hover:border-red-100 transition-all shadow-sm">
                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Detailed Permissions Audit Link -->
        <div class="bg-[#204263] rounded-3xl p-8 relative overflow-hidden group">
            <div class="absolute -right-12 -bottom-12 h-64 w-64 bg-white/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="max-w-xl">
                    <h2 class="text-xl font-bold text-white mb-2">Audit de Sécurité & Permissions</h2>
                    <p class="text-acpe-light-blue text-sm">Consultez l'historique détaillé des changements de permissions et les accès par utilisateur pour garantir l'intégrité du système.</p>
                </div>
                <a href="{{ route('admin.audit.permissions') }}" class="px-8 py-3 bg-acpe-orange text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-acpe-orange/20 hover:scale-105 transition-all">
                    Voir le rapport d'audit
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
