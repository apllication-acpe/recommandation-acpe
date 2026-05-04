<x-admin-layout>
    @section('title', 'Analyse des Tendances - Candidatures')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Analyse des Tendances</h1>
                <p class="text-gray-400 text-sm">Visualisez l'évolution des candidatures et les comportements des postulants.</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mise à jour en direct</span>
            </div>
        </div>

        <!-- Trend Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">+24%</span>
                </div>
                <h3 class="text-sm font-bold text-[#204263] mb-1">Volume de candidatures</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6">Moyenne journalière : 42</p>
                <div class="h-16 flex items-end space-x-1">
                    @for($i=0; $i<20; $i++) <div class="flex-1 bg-blue-100 rounded-t-sm" style="height: {{ rand(20, 100) }}%"></div> @endfor
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">-12% time</span>
                </div>
                <h3 class="text-sm font-bold text-[#204263] mb-1">Temps de traitement</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6">Médiane : 2.5 jours</p>
                <div class="h-16 flex items-end space-x-1">
                    @for($i=0; $i<20; $i++) <div class="flex-1 bg-purple-100 rounded-t-sm" style="height: {{ rand(20, 100) }}%"></div> @endfor
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </div>
                    <span class="text-[10px] font-black text-acpe-orange bg-amber-50 px-2 py-1 rounded-lg">89% Match</span>
                </div>
                <h3 class="text-sm font-bold text-[#204263] mb-1">Pertinence moyenne</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6">Score de matching IA</p>
                <div class="h-16 flex items-end space-x-1">
                    @for($i=0; $i<20; $i++) <div class="flex-1 bg-amber-100 rounded-t-sm" style="height: {{ rand(20, 100) }}%"></div> @endfor
                </div>
            </div>
        </div>

        <!-- Heatmap / Table -->
        <div class="bg-[#204263] rounded-3xl p-10 text-white overflow-hidden relative">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xl font-bold mb-4">Secteurs les plus attractifs</h3>
                    <p class="text-xs opacity-60 mb-8">Nombre de candidatures par offre selon le secteur d'activité.</p>
                    <div class="space-y-6">
                        @foreach(['Digital' => 92, 'Finance' => 78, 'Marketing' => 65, 'RH' => 42] as $name => $val)
                        <div class="space-y-2">
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                                <span>{{ $name }}</span>
                                <span>{{ $val }} cand/offre</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-acpe-orange rounded-full" style="width: {{ $val }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                    <h4 class="text-xs font-black uppercase tracking-widest mb-4 opacity-50">Répartition par région</h4>
                    <div class="h-48 flex items-center justify-center">
                        <i class="fa-solid fa-earth-africa text-8xl opacity-20"></i>
                        <span class="absolute text-[10px] font-bold">Données cartographiques...</span>
                    </div>
                </div>
            </div>
            <i class="fa-solid fa-chart-line absolute -right-12 -bottom-12 text-[200px] opacity-5 -rotate-12"></i>
        </div>
    </div>
</x-admin-layout>
