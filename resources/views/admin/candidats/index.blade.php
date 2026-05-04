<x-admin-layout>
    @section('title', 'Gestion des Demandeurs')

    <div class="space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Gestion des Demandeurs</h1>
                <p class="text-gray-400 text-sm">Gérez tous les profils de demandeurs d'emploi inscrits sur la plateforme.</p>
            </div>
            <a href="{{ route('admin.candidats.create') }}" class="inline-flex items-center px-4 py-2 bg-acpe-orange hover:bg-acpe-orange/90 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                <i class="fa-solid fa-plus mr-2"></i> Nouveau Demandeur
            </a>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.candidats') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un demandeur..." 
                           class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                </div>
                <select name="status" class="w-full px-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('status') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('status') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                <div class="flex items-center space-x-2">
                    <button type="submit" class="px-4 py-2 bg-acpe-blue text-white text-sm font-bold rounded-xl hover:bg-acpe-dark-blue transition-all">
                        Filtrer
                    </button>
                    <a href="{{ route('admin.candidats') }}" class="px-4 py-2 text-gray-400 text-sm hover:text-gray-600 font-bold transition-all">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Demandeur</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Expériences</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Statut</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Inscription</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($candidats as $candidat)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center font-bold text-xs border-2 border-white shadow-sm">
                                        {{ substr($candidat->user->prenom, 0, 1) }}{{ substr($candidat->user->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-[#204263]">{{ $candidat->user->prenom }} {{ $candidat->user->nom }}</p>
                                        <p class="text-[10px] text-gray-400">UID: #{{ $candidat->user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $candidat->user->email }}</p>
                                <p class="text-[10px] text-gray-400">{{ $candidat->telephone ?? 'Non renseigné' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-600">
                                    {{ $candidat->experiences->count() }} exp.
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($candidat->user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-tight">
                                        <i class="fa-solid fa-circle-check mr-1.5"></i> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-amber-50 text-amber-600 uppercase tracking-tight">
                                        <i class="fa-solid fa-clock mr-1.5"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $candidat->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <form action="{{ route('admin.candidats.toggle', $candidat) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="Changer le statut">
                                            <i class="fa-solid fa-power-off"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('messagerie.create', ['receiver_id' => $candidat->user->id]) }}" class="p-2 text-gray-400 hover:text-emerald-500 transition-colors" title="Envoyer un message">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </a>
                                    <a href="{{ route('admin.candidats.edit', $candidat) }}" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="Modifier">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.candidats.destroy', $candidat) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce demandeur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Supprimer">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-user-slash text-4xl text-gray-100 mb-4"></i>
                                    <p class="text-gray-400 text-sm font-medium">Aucun demandeur trouvé</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($candidats->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-50">
                    {{ $candidats->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
