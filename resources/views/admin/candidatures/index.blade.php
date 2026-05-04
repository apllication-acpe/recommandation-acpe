<x-admin-layout>
    @section('title', 'Gestion des Candidatures')

    <div class="space-y-8 animate-slide-up" x-data="candidaturesManager()">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Candidatures</h1>
                <p class="text-gray-400 text-sm">Suivez et modérez les demandes d'emploi en cours.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="exporter()" class="px-6 py-2.5 bg-white border border-gray-100 text-[#204263] text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-file-export mr-2 text-acpe-orange"></i> Export CSV
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">Total Candidatures</p>
                <p class="text-2xl font-black text-[#204263]">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">En attente</p>
                <p class="text-2xl font-black text-amber-500">{{ $stats['en_attente'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">Acceptées</p>
                <p class="text-2xl font-black text-emerald-500">{{ $stats['acceptees'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">Taux de Succès</p>
                <p class="text-2xl font-black text-acpe-blue">{{ $stats['taux_succes'] }}%</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" placeholder="Chercher un candidat ou une offre..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border-none rounded-xl text-xs focus:ring-acpe-orange shadow-inner">
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-blue-50 text-acpe-blue text-[10px] font-black uppercase rounded-xl">Toutes</button>
                <button class="px-4 py-2 bg-gray-50 text-gray-400 text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-colors">En attente</button>
                <button class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-xl">Acceptées</button>
                <button class="px-4 py-2 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-xl">Refusées</button>
                <button onclick="window.location.href=window.location.pathname" class="px-4 py-2 text-gray-400 text-sm hover:text-gray-600 font-bold transition-all">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Candidat</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Offre & Entreprise</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Score IA</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($candidatures as $candidature)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 font-black text-xs">
                                        {{ substr($candidature->demandeur->user->prenom ?? '?', 0, 1) }}{{ substr($candidature->demandeur->user->nom ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-black text-[#204263] uppercase tracking-tight">{{ $candidature->demandeur->user->nom_complet ?? 'Inconnu' }}</h3>
                                        <p class="text-[9px] font-bold text-gray-400">{{ $candidature->demandeur->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs font-bold text-[#204263]">{{ $candidature->offre->titre }}</p>
                                <p class="text-[10px] text-gray-400">{{ $candidature->offre->entreprise->raison_sociale }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg">
                                    {{ $candidature->score ?? 85 }}%
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $statusClasses = [
                                        'en_attente' => 'bg-amber-50 text-amber-600',
                                        'acceptee' => 'bg-emerald-50 text-emerald-600',
                                        'refusee' => 'bg-red-50 text-red-600',
                                        'annulee' => 'bg-gray-50 text-gray-400'
                                    ];
                                    $statusLabels = [
                                        'en_attente' => 'En attente',
                                        'acceptee' => 'Acceptée',
                                        'refusee' => 'Refusée',
                                        'annulee' => 'Annulée'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 {{ $statusClasses[$candidature->statut] ?? 'bg-gray-50' }} text-[9px] font-black uppercase rounded-lg">
                                    {{ $statusLabels[$candidature->statut] ?? $candidature->statut }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('messagerie.create', ['receiver_id' => $candidature->demandeur->user->id]) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                       title="Envoyer un message">
                                        <i class="fa-solid fa-paper-plane text-xs"></i>
                                    </a>
                                    <button @click="voirDetail({{ $candidature->id_candidature }})" 
                                            class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                            title="Détails">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <button @click="supprimer({{ $candidature->id_candidature }})" 
                                            class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-gray-400 italic">Aucune candidature pour le moment.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($candidatures->hasPages())
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-50">
                    {{ $candidatures->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    function candidaturesManager() {
        return {
            voirDetail(id) {
                alert('Détails de la candidature #' + id);
            },
            exporter() {
                window.location.href = '{{ route("admin.candidatures.export") }}';
            },
            supprimer(id) {
                if(confirm('Supprimer cette candidature ?')) {
                    fetch(`/admin/candidatures/${id}`, {
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