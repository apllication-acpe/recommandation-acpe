<x-admin-layout>
    @section('title', 'Statistiques Avancées')

    <div class="space-y-8 animate-slide-up">
        <h1 class="text-2xl font-bold text-[#204263]">Analytique & Statistiques</h1>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
                $totalUsers    = \App\Models\User::count();
                $totalOffres   = \App\Models\Offre::count();
                $offresActives = \App\Models\Offre::where('active', true)->count();
                $totalCands    = \App\Models\Candidature::count();
                $acceptees     = \App\Models\Candidature::where('statut', 'acceptee')->count();
                $taux          = $totalCands > 0 ? round(($acceptees / $totalCands) * 100) : 0;
            @endphp
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Utilisateurs</p>
                <p class="text-3xl font-black text-[#204263]">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Offres actives</p>
                <p class="text-3xl font-black text-acpe-orange">{{ $offresActives }}<span class="text-lg text-gray-300">/{{ $totalOffres }}</span></p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Candidatures</p>
                <p class="text-3xl font-black text-acpe-blue">{{ number_format($totalCands) }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Taux d'embauche</p>
                <p class="text-3xl font-black text-emerald-500">{{ $taux }}<span class="text-lg">%</span></p>
            </div>
        </div>

        <!-- Candidatures par statut -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6">Candidatures par statut</h3>
                @php
                    $statuts = \App\Models\Candidature::selectRaw('statut, count(*) as total')->groupBy('statut')->get();
                    $totalStatuts = $statuts->sum('total') ?: 1;
                @endphp
                <div class="space-y-4">
                    @foreach($statuts as $s)
                        @php
                            $pct = round(($s->total / $totalStatuts) * 100);
                            $colors = ['en_attente' => 'bg-orange-400', 'acceptee' => 'bg-emerald-400', 'refusee' => 'bg-red-400', 'annulee' => 'bg-gray-300'];
                            $color  = $colors[$s->statut] ?? 'bg-gray-300';
                        @endphp
                        <div>
                            <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase mb-1.5">
                                <span>{{ ucfirst($s->statut) }}</span>
                                <span>{{ $s->total }} ({{ $pct }}%)</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="{{ $color }} h-full rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top 5 offres les plus postulées -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6">Top 5 offres les plus postulées</h3>
                @php
                    $topOffres = \App\Models\Offre::withCount('candidatures')->orderByDesc('candidatures_count')->take(5)->get();
                @endphp
                <div class="space-y-4">
                    @forelse($topOffres as $offre)
                        <div class="flex justify-between items-center p-3 bg-gray-50/50 rounded-xl">
                            <div class="overflow-hidden">
                                <p class="text-xs font-bold text-[#204263] truncate">{{ $offre->titre }}</p>
                                <p class="text-[9px] text-gray-400">{{ $offre->entreprise->raison_sociale ?? '—' }}</p>
                            </div>
                            <span class="flex-shrink-0 ml-4 px-3 py-1 bg-acpe-blue text-white text-[9px] font-black rounded-lg">
                                {{ $offre->candidatures_count }} candidats
                            </span>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-gray-300 italic text-center py-8">Aucune donnée disponible</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Inscriptions par mois -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6">Nouvelles inscriptions – 6 derniers mois</h3>
            @php
                $inscriptions = \App\Models\User::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mois, count(*) as total")
                    ->where('created_at', '>=', now()->subMonths(6))
                    ->groupBy('mois')
                    ->orderBy('mois')
                    ->get();
                $maxInsc = $inscriptions->max('total') ?: 1;
            @endphp
            <div class="flex items-end justify-around h-32 gap-4">
                @foreach($inscriptions as $insc)
                    @php $height = round(($insc->total / $maxInsc) * 100); @endphp
                    <div class="flex flex-col items-center gap-2 flex-1">
                        <span class="text-[9px] font-black text-gray-400">{{ $insc->total }}</span>
                        <div class="w-full bg-acpe-blue/80 rounded-t-lg transition-all" style="height: {{ $height }}%"></div>
                        <span class="text-[8px] font-black text-gray-300 uppercase">{{ \Carbon\Carbon::parse($insc->mois . '-01')->format('M') }}</span>
                    </div>
                @endforeach
                @if($inscriptions->isEmpty())
                    <p class="text-xs font-bold text-gray-300 italic">Aucune inscription récente</p>
                @endif
            </div>
        </div>

    </div>
</x-admin-layout>
