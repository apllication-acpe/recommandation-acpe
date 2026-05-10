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
                                    <a href="{{ route('admin.messagerie', ['user_id' => $candidature->demandeur->user->id]) }}" 
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

        <!-- Modal Détails & Décision -->
        <div x-show="modalOuverte" 
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-[#204263]/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-cloak>
            
            <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-slide-up" @click.away="modalOuverte = false">
                <!-- Modal Header -->
                <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-black text-[#204263] uppercase tracking-tight">Détails de la candidature</h2>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Décision administrative</p>
                    </div>
                    <button @click="modalOuverte = false" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-circle-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8" x-if="candidatureSelectionnee">
                    <div class="flex items-start space-x-6 mb-8">
                        <div class="h-16 w-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl font-black shadow-inner">
                            <span x-text="candidatureSelectionnee.demandeur.user.prenom[0] + candidatureSelectionnee.demandeur.user.nom[0]"></span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-[#204263]" x-text="candidatureSelectionnee.demandeur.user.nom + ' ' + candidatureSelectionnee.demandeur.user.prenom"></h3>
                            <p class="text-sm text-gray-400" x-text="candidatureSelectionnee.demandeur.user.email"></p>
                            <div class="flex items-center mt-2 space-x-3">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg" x-text="candidatureSelectionnee.offre.titre"></span>
                                <span class="text-[10px] font-bold text-gray-300" x-text="'Postulé ' + candidatureSelectionnee.date_candidature_formatee"></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] font-black text-gray-300 uppercase mb-1">Score IA</div>
                            <div class="text-2xl font-black text-blue-600" x-text="(candidatureSelectionnee.score || 85) + '%'"></div>
                        </div>
                    </div>

                    <!-- Message de motivation -->
                    <div class="bg-gray-50 rounded-2xl p-5 mb-8 border border-gray-100">
                        <h4 class="text-[10px] font-black text-[#204263] uppercase tracking-widest mb-3">Message de motivation</h4>
                        <p class="text-xs text-gray-600 italic leading-relaxed" x-text="candidatureSelectionnee.message_motivation || 'Aucun message fourni.'"></p>
                    </div>

                    <!-- Actions de décision -->
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="changerStatut('acceptee')" 
                                class="flex items-center justify-center space-x-3 py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-200">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Accepter le candidat</span>
                        </button>
                        <button @click="changerStatut('refusee')" 
                                class="flex items-center justify-center space-x-3 py-4 bg-white border-2 border-red-100 text-red-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-50 transition-all">
                            <i class="fa-solid fa-times-circle"></i>
                            <span>Refuser la candidature</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function candidaturesManager() {
        return {
            modalOuverte: false,
            candidatureSelectionnee: null,
            
            async voirDetail(id) {
                try {
                    const response = await fetch(`/admin/candidatures/${id}`);
                    this.candidatureSelectionnee = await response.json();
                    
                    // Formater la date proprement
                    const date = new Date(this.candidatureSelectionnee.date_candidature);
                    this.candidatureSelectionnee.date_candidature_formatee = date.toLocaleDateString('fr-FR', {
                        day: 'numeric', month: 'long', year: 'numeric'
                    });
                    
                    this.modalOuverte = true;
                } catch (error) {
                    alert('Erreur lors du chargement des détails');
                }
            },

            async changerStatut(nouveauStatut) {
                if(!confirm(`Confirmer le statut : ${nouveauStatut} ?`)) return;

                try {
                    const response = await fetch(`/admin/candidatures/${this.candidatureSelectionnee.id_candidature}/statut`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ statut: nouveauStatut })
                    });

                    if(response.ok) {
                        this.modalOuverte = false;
                        location.reload(); // Recharger pour voir le changement de statut
                    }
                } catch (error) {
                    alert('Erreur lors de la mise à jour du statut');
                }
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