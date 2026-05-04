<x-admin-layout>
    @section('title', 'Gestion des entreprises')

    <div class="space-y-8 animate-slide-up" x-data="entreprisesManager()">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Entreprises</h1>
                <p class="text-gray-400 text-sm">Administrez les comptes entreprises et vérifiez leur authenticité.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.entreprises.create') }}" class="px-6 py-2.5 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Nouvelle Entreprise
                </a>
                <button @click="exporter" class="px-6 py-2.5 bg-white border border-gray-100 text-[#204263] text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-file-export mr-2 text-acpe-orange"></i> Exporter
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" placeholder="Rechercher une entreprise..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border-none rounded-xl text-xs focus:ring-acpe-orange shadow-inner">
            </div>
            <div class="flex items-center space-x-2">
                <select class="px-4 py-2 bg-gray-50 border-none rounded-xl text-xs font-bold text-gray-500 focus:ring-acpe-orange">
                    <option>Tous les statuts</option>
                    <option>Vérifiées</option>
                    <option>En attente</option>
                </select>
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
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Entreprise</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact & Ville</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Activité</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Vérification</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($entreprises as $entreprise)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-acpe-blue group-hover:scale-110 transition-transform overflow-hidden">
                                        @if($entreprise->logo_path)
                                            <img src="{{ $entreprise->logo_url }}" class="h-full w-full object-cover">
                                        @else
                                            <i class="fa-solid fa-building text-lg opacity-20"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-black text-[#204263] uppercase tracking-tight">{{ $entreprise->raison_sociale }}</h3>
                                        <p class="text-[9px] font-bold text-gray-400">{{ $entreprise->forme_juridique ?? 'S.A.S' }} • {{ $entreprise->taille ?? '10-50' }} emp.</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs font-bold text-[#204263]">{{ $entreprise->email_contact }}</p>
                                <p class="text-[10px] text-gray-400">{{ $entreprise->telephone ?? 'Brazzaville, Congo' }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-black text-[#204263]">{{ $entreprise->offres_count ?? 0 }}</span>
                                    <span class="text-[8px] font-bold text-gray-300 uppercase">Offres</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($entreprise->verifiee)
                                    <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-lg">
                                        <i class="fa-solid fa-circle-check mr-2"></i> Vérifiée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-600 text-[9px] font-black uppercase rounded-lg">
                                        <i class="fa-solid fa-clock mr-2"></i> En attente
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.entreprises.edit', $entreprise) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                       title="Modifier">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if(!$entreprise->verifiee)
                                        <button @click="valider({{ $entreprise->id_entreprise }})" 
                                                class="h-9 px-4 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                            <i class="fa-solid fa-check mr-2"></i> Valider
                                        </button>
                                    @endif
                                    <button @click="suspendre({{ $entreprise->id_entreprise }})" 
                                            class="h-9 w-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                            title="Suspendre">
                                        <i class="fa-solid fa-pause"></i>
                                    </button>
                                    <button @click="supprimer({{ $entreprise->id_entreprise }})" 
                                            class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-building-circle-exclamation text-4xl text-gray-100 mb-4"></i>
                                    <p class="text-gray-400 text-sm font-medium">Aucune entreprise enregistrée</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($entreprises->hasPages())
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-50">
                    {{ $entreprises->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    function entreprisesManager() {
        return {
            exporter() {
                window.location.href = '{{ route("admin.entreprises.export") }}';
            },
            valider(id) {
                if(!confirm('Valider l\'authenticité de cette entreprise ?')) return;
                this.performAction(`/admin/entreprises/${id}/valider`, 'POST');
            },
            suspendre(id) {
                if(!confirm('Suspendre l\'accès de cette entreprise ?')) return;
                this.performAction(`/admin/entreprises/${id}/suspendre`, 'POST');
            },
            supprimer(id) {
                if(confirm('Supprimer définitivement cette entreprise et ses offres ?')) {
                    this.performAction(`/admin/entreprises/${id}`, 'DELETE');
                }
            },
            async performAction(url, method) {
                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    if(response.ok) {
                        location.reload();
                    }
                } catch (e) {
                    console.error('Erreur:', e);
                }
            }
        }
    }
    </script>
    @endpush
</x-admin-layout>