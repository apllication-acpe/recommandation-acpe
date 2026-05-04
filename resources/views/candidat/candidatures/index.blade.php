<x-candidat-layout>
    <div class="space-y-8 animate-slide-up">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-[#204263]">Mes Candidatures</h1>
                <p class="text-gray-500 mt-2 font-medium">Suivez l'état d'avancement de vos postulations en temps réel.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                    <span class="text-xs font-bold text-gray-600">En attente: {{ $candidatures->where('statut', 'en_attente')->count() }}</span>
                </div>
                <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span class="text-xs font-bold text-gray-600">Acceptées: {{ $candidatures->where('statut', 'acceptee')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Candidatures List -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Offre & Entreprise</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date de candidature</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Statut</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($candidatures as $candidature)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-12 w-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100 group-hover:border-acpe-blue transition-colors">
                                            <i class="fa-solid fa-building text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-[#204263]">{{ $candidature->offre->titre }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $candidature->offre->entreprise->raison_sociale }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm font-medium text-gray-600">{{ $candidature->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">à {{ $candidature->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClasses = [
                                            'en_attente' => 'bg-orange-50 text-orange-500 border-orange-100',
                                            'acceptee' => 'bg-emerald-50 text-emerald-500 border-emerald-100',
                                            'refusee' => 'bg-red-50 text-red-500 border-red-100',
                                            'entretien' => 'bg-blue-50 text-blue-500 border-blue-100',
                                        ];
                                        $statusLabels = [
                                            'en_attente' => 'En attente',
                                            'acceptee' => 'Acceptée',
                                            'refusee' => 'Refusée',
                                            'entretien' => 'Entretien',
                                        ];
                                        $class = $statusClasses[$candidature->statut] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                        $label = $statusLabels[$candidature->statut] ?? $candidature->statut;
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $class }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('candidat.offres.show', $candidature->offre->id_offre) }}" class="p-2 bg-gray-50 text-gray-400 hover:text-acpe-blue rounded-lg transition-colors" title="Voir l'offre">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <button class="p-2 bg-gray-50 text-gray-400 hover:text-red-500 rounded-lg transition-colors" title="Annuler ma candidature">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                                            <i class="fa-solid fa-file-signature text-4xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-bold">Vous n'avez pas encore postulé à des offres.</p>
                                        <a href="{{ route('candidat.offres.index') }}" class="mt-4 px-6 py-2 bg-acpe-blue text-white rounded-xl font-bold text-sm hover:bg-acpe-dark-blue transition-all">
                                            Découvrir les offres
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($candidatures->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-50">
                    {{ $candidatures->links() }}
                </div>
            @endif
        </div>

    </div>
</x-candidat-layout>
