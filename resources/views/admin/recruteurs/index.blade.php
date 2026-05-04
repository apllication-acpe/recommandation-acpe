<x-admin-layout>
    @section('title', 'Gestion des Recruteurs')

    <div class="space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Gestion des Recruteurs</h1>
                <p class="text-gray-400 text-sm">Consultez la liste des recruteurs inscrits sur la plateforme.</p>
            </div>
            <a href="{{ route('admin.utilisateurs.create') }}" class="inline-flex items-center px-4 py-2 bg-acpe-blue hover:bg-acpe-blue/90 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter un Utilisateur
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Recruteur</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Statut</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Inscription</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recruteurs as $recruteur)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-acpe-orange/10 text-acpe-orange flex items-center justify-center font-bold text-xs border-2 border-white shadow-sm">
                                        {{ substr($recruteur->prenom, 0, 1) }}{{ substr($recruteur->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-[#204263]">{{ $recruteur->prenom }} {{ $recruteur->nom }}</p>
                                        <p class="text-[10px] text-gray-400">UID: #{{ $recruteur->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $recruteur->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($recruteur->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-tight">
                                        <i class="fa-solid fa-circle-check mr-1.5"></i> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-amber-50 text-amber-600 uppercase tracking-tight">
                                        <i class="fa-solid fa-clock mr-1.5"></i> En attente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $recruteur->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('messagerie.create', ['receiver_id' => $recruteur->id]) }}" class="p-2 text-gray-400 hover:text-emerald-500 transition-colors" title="Envoyer un message">
                                        <i class="fa-regular fa-envelope"></i>
                                    </a>
                                    <a href="{{ route('admin.utilisateurs.edit', $recruteur) }}" class="p-2 text-gray-400 hover:text-acpe-blue transition-colors" title="Modifier">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-user-tie text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold">Aucun recruteur trouvé</p>
                                    <p class="text-xs text-gray-400 mt-1">Il n'y a pas encore de recruteurs inscrits.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($recruteurs->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $recruteurs->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
