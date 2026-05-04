<x-admin-layout>
    @section('title', 'Gestion des Offres')

    <div class="space-y-8 animate-slide-up" x-data="offresManager()">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Offres d'emploi</h1>
                <p class="text-gray-400 text-sm">Gérez les opportunités publiées par les entreprises partenaires.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.offres.create') }}" class="px-6 py-2.5 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Nouvelle Offre
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" placeholder="Rechercher une offre..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border-none rounded-xl text-xs focus:ring-acpe-orange shadow-inner">
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-gray-50 text-gray-400 text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-colors">Tous</button>
                <button class="px-4 py-2 bg-blue-50 text-acpe-blue text-[10px] font-black uppercase rounded-xl">En attente</button>
                <button class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-xl">Publiées</button>
                <button class="px-4 py-2 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-xl">Signalées</button>
                <button onclick="window.location.href=window.location.pathname" class="px-4 py-2 text-gray-400 text-sm hover:text-gray-600 font-bold transition-all">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Offres Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            @foreach($offres as $offre)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-2xl bg-gray-50 flex items-center justify-center text-acpe-blue border border-gray-100 group-hover:scale-110 transition-transform">
                            @if($offre->entreprise->logo_path)
                                <img src="{{ asset('storage/' . $offre->entreprise->logo_path) }}" class="h-full w-full object-cover rounded-2xl">
                            @else
                                <i class="fa-solid fa-building text-lg opacity-20"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-[#204263] uppercase tracking-tight group-hover:text-acpe-orange transition-colors">{{ $offre->titre }}</h3>
                            <p class="text-[10px] font-bold text-gray-400">{{ $offre->entreprise->raison_sociale }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if(!$offre->active)
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[8px] font-black uppercase rounded-lg">En attente</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase rounded-lg">Publiée</span>
                        @endif
                        @if($offre->signalee)
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[8px] font-black uppercase rounded-lg">Signalée</span>
                        @endif
                    </div>
                </div>

                <p class="text-xs text-gray-500 leading-relaxed mb-6 line-clamp-2">
                    {{ $offre->description }}
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="p-3 bg-gray-50/50 rounded-2xl">
                        <p class="text-[8px] font-black text-gray-300 uppercase mb-1">Type</p>
                        <p class="text-[10px] font-bold text-[#204263]">{{ $offre->typeContrat->libelle ?? 'CDI' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50/50 rounded-2xl">
                        <p class="text-[8px] font-black text-gray-300 uppercase mb-1">Vues</p>
                        <p class="text-[10px] font-bold text-[#204263]">{{ number_format($offre->nb_vues ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-gray-50/50 rounded-2xl">
                        <p class="text-[8px] font-black text-gray-300 uppercase mb-1">Postulants</p>
                        <p class="text-[10px] font-bold text-[#204263]">{{ $offre->candidatures_count ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-gray-50/50 rounded-2xl">
                        <p class="text-[8px] font-black text-gray-300 uppercase mb-1">Expire le</p>
                        <p class="text-[10px] font-bold text-[#204263]">{{ $offre->date_expiration->format('d M') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                    <div class="flex items-center -space-x-2">
                        @for($i=0; $i<min(3, $offre->candidatures_count ?? 0); $i++)
                            <div class="h-6 w-6 rounded-full bg-blue-50 border-2 border-white flex items-center justify-center text-[8px] font-black text-blue-500">
                                {{ chr(65 + $i) }}
                            </div>
                        @endfor
                        @if(($offre->candidatures_count ?? 0) > 3)
                            <div class="h-6 w-6 rounded-full bg-gray-50 border-2 border-white flex items-center justify-center text-[8px] font-black text-gray-400">
                                +{{ $offre->candidatures_count - 3 }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if(!$offre->active)
                            <button @click="valider({{ $offre->id_offre }})" 
                                    class="h-9 px-4 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-check mr-2"></i> Valider
                            </button>
                        @endif
                        <a href="{{ route('admin.offres.edit', $offre) }}" 
                           class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <button @click="toggle({{ $offre->id_offre }})" 
                                class="h-9 w-9 flex items-center justify-center rounded-xl {{ $offre->active ? 'bg-amber-50 text-amber-600 hover:bg-amber-600' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600' }} hover:text-white transition-all shadow-sm">
                            <i class="fa-solid {{ $offre->active ? 'fa-pause' : 'fa-play' }}"></i>
                        </button>
                        <button @click="supprimer({{ $offre->id_offre }})" 
                                class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($offres->hasPages())
            <div class="mt-8">
                {{ $offres->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
    function offresManager() {
        return {
            valider(id) {
                if(!confirm('Valider cette offre pour publication ?')) return;
                this.performAction(`/admin/offres/${id}/valider`, 'POST');
            },
            toggle(id) {
                this.performAction(`/admin/offres/${id}/toggle`, 'PATCH');
            },
            supprimer(id) {
                if(confirm('Supprimer définitivement cette offre ?')) {
                    this.performAction(`/admin/offres/${id}`, 'DELETE');
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