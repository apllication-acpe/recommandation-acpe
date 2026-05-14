<x-admin-layout>
    @section('title', 'Offres ACPE.CG — Scraping')

    <div class="space-y-8 animate-slide-up" x-data="scrapingManager()">

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- HEADER                                            --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#204263] to-[#2d6a9f] flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-spider text-white text-sm"></i>
                    </div>
                    <h1 class="text-2xl font-black text-[#204263]">Offres ACPE.CG</h1>
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-lg tracking-widest">
                        Live Sync
                    </span>
                </div>
                <p class="text-gray-400 text-sm ml-12">
                    Offres importées automatiquement depuis <a href="https://www.acpe.cg/offres-emplois" target="_blank" class="text-[#2d6a9f] hover:underline font-medium">acpe.cg</a>
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Bouton lancer scraping --}}
                <button @click="lancerScraping()"
                        :disabled="scraping"
                        :class="scraping ? 'opacity-60 cursor-not-allowed' : 'hover:scale-105 shadow-lg shadow-blue-500/20'"
                        class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#204263] to-[#2d6a9f] text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    <i class="fa-solid" :class="scraping ? 'fa-circle-notch fa-spin' : 'fa-sync-alt'"></i>
                    <span x-text="scraping ? 'Synchronisation...' : 'Synchroniser'"></span>
                </button>
                {{-- Lien vers acpe.cg --}}
                <a href="https://www.acpe.cg/offres-emplois" target="_blank"
                   class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:border-[#204263] hover:text-[#204263] transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    acpe.cg
                </a>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- STATS CARDS                                       --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Total scrapées --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm group hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-9 w-9 rounded-xl bg-blue-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-database text-[#204263] text-sm"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase text-gray-300 tracking-widest">Total</span>
                </div>
                <p class="text-2xl font-black text-[#204263]">{{ number_format($stats['total']) }}</p>
                <p class="text-[9px] text-gray-400 font-medium mt-1">Offres importées</p>
            </div>

            {{-- Actives --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm group hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-9 w-9 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase text-gray-300 tracking-widest">Actives</span>
                </div>
                <p class="text-2xl font-black text-emerald-600">{{ number_format($stats['actives']) }}</p>
                <p class="text-[9px] text-gray-400 font-medium mt-1">En ligne</p>
            </div>

            {{-- Entreprises --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm group hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-9 w-9 rounded-xl bg-purple-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-building text-purple-500 text-sm"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase text-gray-300 tracking-widest">Employeurs</span>
                </div>
                <p class="text-2xl font-black text-purple-600">{{ number_format($stats['entreprises']) }}</p>
                <p class="text-[9px] text-gray-400 font-medium mt-1">Entreprises</p>
            </div>

            {{-- Dernière synchro --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm group hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-9 w-9 rounded-xl bg-amber-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-clock-rotate-left text-amber-500 text-sm"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase text-gray-300 tracking-widest">Synchro</span>
                </div>
                <p class="text-sm font-black text-[#204263] leading-tight">
                    {{ $stats['derniere_synchro'] ? $stats['derniere_synchro']->diffForHumans() : 'Jamais' }}
                </p>
                <p class="text-[9px] text-gray-400 font-medium mt-1">Dernière mise à jour</p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- BANNER SCRAPING EN COURS                          --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <div x-show="scraping" x-cloak
             class="bg-gradient-to-r from-[#204263] to-[#2d6a9f] rounded-2xl p-4 flex items-center gap-4 shadow-lg">
            <div class="h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-notch fa-spin text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-white font-black text-sm">Synchronisation en cours...</p>
                <p class="text-white/70 text-xs">Les offres sont importées depuis acpe.cg — la page se rechargera automatiquement.</p>
            </div>
            <div class="flex-shrink-0">
                <div class="flex gap-1">
                    <span class="h-2 w-2 rounded-full bg-white/40 animate-bounce" style="animation-delay:0ms"></span>
                    <span class="h-2 w-2 rounded-full bg-white/40 animate-bounce" style="animation-delay:150ms"></span>
                    <span class="h-2 w-2 rounded-full bg-white/40 animate-bounce" style="animation-delay:300ms"></span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- FILTRES                                           --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <form method="GET" action="{{ route('admin.offres.acpe') }}"
              class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap gap-3">
            {{-- Recherche --}}
            <div class="flex-1 min-w-[220px] relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Titre, entreprise, qualification..."
                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-xs focus:ring-2 focus:ring-[#204263] shadow-inner">
            </div>
            {{-- Département --}}
            <select name="dept" class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-xs focus:ring-2 focus:ring-[#204263] text-gray-500 font-medium shadow-inner">
                <option value="">Tous les départements</option>
                @foreach($departements as $dept)
                    <option value="{{ $dept }}" {{ request('dept') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
            {{-- Type contrat --}}
            <select name="type_cont" class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-xs focus:ring-2 focus:ring-[#204263] text-gray-500 font-medium shadow-inner">
                <option value="">Tous les contrats</option>
                @foreach($typesContrat as $tc)
                    <option value="{{ $tc->id_type_cont }}" {{ request('type_cont') == $tc->id_type_cont ? 'selected' : '' }}>
                        {{ $tc->libelle }}
                    </option>
                @endforeach
            </select>
            {{-- Boutons --}}
            <button type="submit" class="px-5 py-2.5 bg-[#204263] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#2d6a9f] transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['search','dept','type_cont']))
                <a href="{{ route('admin.offres.acpe') }}" class="px-4 py-2.5 bg-gray-50 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-colors">
                    <i class="fa-solid fa-xmark mr-1"></i> Reset
                </a>
            @endif
        </form>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- RÉSULTATS INFO BAR                                --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-400">
                <span class="font-black text-[#204263]">{{ $offres->total() }}</span> offre(s) trouvée(s)
                @if(request('search'))
                    pour "<span class="font-bold text-[#204263]">{{ request('search') }}</span>"
                @endif
            </p>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-gray-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Source: acpe.cg
                </span>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- GRILLE DES OFFRES                                 --}}
        {{-- ══════════════════════════════════════════════════ --}}
        @if($offres->count())
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
            @foreach($offres as $offre)
            <div class="group bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all hover:-translate-y-0.5">

                {{-- Haut : entreprise + badges --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        {{-- Avatar entreprise --}}
                        <div class="h-11 w-11 flex-shrink-0 rounded-2xl bg-gradient-to-br from-[#204263]/10 to-[#2d6a9f]/10 flex items-center justify-center border border-[#204263]/10 group-hover:scale-110 transition-transform">
                            @if($offre->entreprise?->logo_path)
                                <img src="{{ asset('storage/' . $offre->entreprise->logo_path) }}" class="h-full w-full object-cover rounded-2xl">
                            @else
                                <span class="text-[10px] font-black text-[#204263] uppercase leading-none">
                                    {{ mb_substr($offre->entreprise?->raison_sociale ?? 'E', 0, 2) }}
                                </span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-black text-[#204263] uppercase tracking-tight group-hover:text-[#2d6a9f] transition-colors truncate">
                                {{ $offre->titre }}
                            </h3>
                            <p class="text-[10px] font-bold text-gray-400 truncate">
                                {{ $offre->entreprise?->raison_sociale ?? 'Entreprise' }}
                            </p>
                        </div>
                    </div>

                    {{-- Badges statut --}}
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0 ml-2">
                        <span class="px-2.5 py-1 bg-blue-50 text-[#204263] text-[8px] font-black uppercase rounded-lg tracking-wide flex items-center gap-1">
                            <i class="fa-solid fa-link text-[7px]"></i> acpe.cg
                        </span>
                        @if($offre->active)
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase rounded-lg">Active</span>
                        @else
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-[8px] font-black uppercase rounded-lg">Inactive</span>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                <p class="text-xs text-gray-500 leading-relaxed mb-5 line-clamp-2">
                    {{ Str::limit(strip_tags($offre->description), 140) }}
                </p>

                {{-- Méta infos --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-5">
                    <div class="p-2.5 bg-gray-50/80 rounded-xl">
                        <p class="text-[7px] font-black text-gray-300 uppercase mb-0.5">Contrat</p>
                        <p class="text-[10px] font-bold text-[#204263]">{{ $offre->typeContrat?->libelle ?? 'CDD' }}</p>
                    </div>
                    <div class="p-2.5 bg-gray-50/80 rounded-xl">
                        <p class="text-[7px] font-black text-gray-300 uppercase mb-0.5">Lieu</p>
                        <p class="text-[10px] font-bold text-[#204263] truncate">{{ $offre->departement ?? 'Congo' }}</p>
                    </div>
                    <div class="p-2.5 bg-gray-50/80 rounded-xl">
                        <p class="text-[7px] font-black text-gray-300 uppercase mb-0.5">Salaire</p>
                        <p class="text-[10px] font-bold text-[#204263]">{{ $offre->statut_salaire ?? 'Négociable' }}</p>
                    </div>
                    <div class="p-2.5 bg-gray-50/80 rounded-xl">
                        <p class="text-[7px] font-black text-gray-300 uppercase mb-0.5">Expire</p>
                        <p class="text-[10px] font-bold {{ $offre->date_expiration?->isPast() ? 'text-red-500' : 'text-[#204263]' }}">
                            {{ $offre->date_expiration?->format('d M Y') ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                {{-- Footer : qualification + actions --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        @if($offre->qualification_requise)
                            <span class="px-2.5 py-1 bg-purple-50 text-purple-600 text-[8px] font-black rounded-lg max-w-[160px] truncate">
                                {{ $offre->qualification_requise }}
                            </span>
                        @endif
                        @if($offre->acpe_id)
                            <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[8px] font-mono rounded-lg">
                                #{{ $offre->acpe_id }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        {{-- Voir sur acpe.cg --}}
                        @if($offre->url_source)
                        <a href="{{ $offre->url_source }}" target="_blank"
                           class="h-8 w-8 flex items-center justify-center rounded-xl bg-[#204263]/5 text-[#204263] hover:bg-[#204263] hover:text-white transition-all shadow-sm" title="Voir sur acpe.cg">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                        @endif
                        {{-- Toggle actif --}}
                        <button @click="toggleOffre({{ $offre->id_offre }})"
                                class="h-8 w-8 flex items-center justify-center rounded-xl {{ $offre->active ? 'bg-amber-50 text-amber-500 hover:bg-amber-500' : 'bg-emerald-50 text-emerald-500 hover:bg-emerald-500' }} hover:text-white transition-all shadow-sm"
                                title="{{ $offre->active ? 'Désactiver' : 'Activer' }}">
                            <i class="fa-solid {{ $offre->active ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                        </button>
                        {{-- Supprimer --}}
                        <button @click="supprimerOffre({{ $offre->id_offre }})"
                                class="h-8 w-8 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                title="Supprimer">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        {{-- État vide --}}
        <div class="bg-white rounded-3xl p-16 border border-gray-100 shadow-sm text-center">
            <div class="h-20 w-20 rounded-3xl bg-gradient-to-br from-[#204263]/10 to-[#2d6a9f]/10 flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-spider text-4xl text-[#204263]/30"></i>
            </div>
            <h3 class="text-lg font-black text-[#204263] mb-2">Aucune offre importée</h3>
            <p class="text-sm text-gray-400 mb-6 max-w-sm mx-auto">
                Cliquez sur <strong>Synchroniser</strong> pour importer les offres depuis acpe.cg
            </p>
            <button @click="lancerScraping()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#204263] to-[#2d6a9f] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg">
                <i class="fa-solid fa-sync-alt"></i>
                Importer maintenant
            </button>
        </div>
        @endif

        {{-- Pagination --}}
        @if($offres->hasPages())
            <div>{{ $offres->appends(request()->query())->links() }}</div>
        @endif

    </div>

    @push('scripts')
    <script>
    function scrapingManager() {
        return {
            scraping: false,

            async lancerScraping() {
                if (this.scraping) return;
                this.scraping = true;
                try {
                    const response = await fetch('{{ route("admin.offres.acpe.sync") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ pages: 5 })
                    });
                    const data = await response.json();
                    if (data.success) {
                        setTimeout(() => location.reload(), 1500);
                    }
                } catch (e) {
                    console.error('Erreur synchro:', e);
                    this.scraping = false;
                }
            },

            async toggleOffre(id) {
                const res = await fetch(`/admin/offres/${id}/toggle`, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (res.ok) location.reload();
            },

            async supprimerOffre(id) {
                if (!confirm('Supprimer cette offre importée ?')) return;
                const res = await fetch(`/admin/offres/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (res.ok) location.reload();
            }
        }
    }
    </script>
    @endpush
</x-admin-layout>
