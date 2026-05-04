<x-admin-layout>
    @section('title', 'Gestion des Utilisateurs')

    <div class="space-y-6 animate-slide-up" x-data="utilisateursManager()">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Gestion des Utilisateurs</h1>
                <p class="text-gray-400 text-sm">Gérez l'ensemble des comptes de la plateforme.</p>
            </div>
            <a href="{{ route('admin.utilisateurs.create') }}" class="inline-flex items-center px-4 py-2 bg-acpe-blue hover:bg-acpe-blue/90 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter un Utilisateur
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                <input type="text" x-model="filters.search" placeholder="Rechercher par nom, prénom ou email..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
            </div>
            <div class="w-full md:w-48">
                <select x-model="filters.role" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 appearance-none text-gray-500 font-medium cursor-pointer">
                    <option value="">Tous les rôles</option>
                    <option value="admin">Administrateurs</option>
                    <option value="demandeur">Demandeurs</option>
                </select>
            </div>
            <div class="w-full md:w-48">
                <select x-model="filters.status" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 appearance-none text-gray-500 font-medium cursor-pointer">
                    <option value="">Tous les statuts</option>
                    <option value="actif">Actifs</option>
                    <option value="inactif">Suspendus</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button @click="applyFilters" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-[#204263] text-sm font-bold rounded-xl transition-colors">
                    <i class="fa-solid fa-filter mr-2"></i> Filtrer
                </button>
                <button @click="resetFilters" class="px-4 py-2.5 text-gray-400 hover:text-gray-600 text-sm font-bold rounded-xl transition-colors">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Utilisateur</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Rôle</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Statut</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Inscription</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($utilisateurs as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-acpe-blue/10 text-acpe-blue flex items-center justify-center font-bold text-xs border-2 border-white shadow-sm overflow-hidden">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-[#204263]">{{ $user->prenom }} {{ $user->nom }}</p>
                                        <p class="text-[10px] text-gray-400">UID: #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                @if($user->telephone)
                                <p class="text-[10px] text-gray-400 mt-0.5"><i class="fa-solid fa-phone text-[8px] mr-1"></i> {{ $user->telephone }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleName = $user->getRoleNames()->first() ?? 'N/A';
                                    $roleColor = match($roleName) {
                                        'admin' => 'bg-purple-50 text-purple-600',
                                        'recruteur' => 'bg-orange-50 text-orange-600',
                                        'demandeur' => 'bg-blue-50 text-blue-600',
                                        default => 'bg-gray-50 text-gray-600'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black {{ $roleColor }} uppercase tracking-tight">
                                    {{ ucfirst($roleName) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->actif)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-tight">
                                        <i class="fa-solid fa-circle-check mr-1.5"></i> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-red-50 text-red-600 uppercase tracking-tight">
                                        <i class="fa-solid fa-ban mr-1.5"></i> Suspendu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('messagerie.create', ['receiver_id' => $user->id]) }}" class="h-8 w-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:text-acpe-blue hover:bg-blue-50 transition-colors" title="Envoyer un message">
                                        <i class="fa-regular fa-envelope text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.utilisateurs.edit', $user) }}" class="h-8 w-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Modifier">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    @if(Auth::id() !== $user->id)
                                    <button @click="suspendre({{ $user->id }})" class="h-8 w-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="{{ $user->actif ? 'Suspendre' : 'Réactiver' }}">
                                        <i class="fa-solid {{ $user->actif ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                                    </button>
                                    <button @click="supprimer({{ $user->id }})" class="h-8 w-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Supprimer définitivement">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-users-slash text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold">Aucun utilisateur trouvé</p>
                                    <p class="text-xs text-gray-400 mt-1">Modifiez vos filtres de recherche.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($utilisateurs->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $utilisateurs->links() }}
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    function utilisateursManager() {
        return {
            filters: { 
                search: new URLSearchParams(window.location.search).get('search') || '', 
                role: new URLSearchParams(window.location.search).get('role') || '', 
                status: new URLSearchParams(window.location.search).get('status') || '' 
            },
            applyFilters() {
                let url = new URL(window.location.href);
                if(this.filters.search) url.searchParams.set('search', this.filters.search); else url.searchParams.delete('search');
                if(this.filters.role) url.searchParams.set('role', this.filters.role); else url.searchParams.delete('role');
                if(this.filters.status) url.searchParams.set('status', this.filters.status); else url.searchParams.delete('status');
                window.location.href = url.toString();
            },
            resetFilters() {
                window.location.href = window.location.pathname;
            },
            suspendre(id) {
                if(confirm('Voulez-vous modifier le statut de cet utilisateur ?')) {
                    fetch(`/admin/utilisateurs/${id}/suspendre`, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(res => res.json()).then(data => {
                        if(data.success) location.reload();
                        else alert(data.error || 'Erreur inconnue');
                    }).catch(e => console.error(e));
                }
            },
            supprimer(id) {
                if(confirm('Supprimer définitivement cet utilisateur ? (Action irréversible)')) {
                    fetch(`/admin/utilisateurs/${id}`, {
                        method: 'DELETE',
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => location.reload());
                }
            }
        }
    }
    </script>
    @endpush
</x-admin-layout>