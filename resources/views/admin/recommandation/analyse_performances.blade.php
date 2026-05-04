<x-admin-layout>
    @section('title', 'Analyse des Performances - Algorithmes')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Analyse des Performances</h1>
                <p class="text-gray-400 text-sm">Mesurez l'efficacité des algorithmes de recommandation et de matching.</p>
            </div>
            <div class="flex items-center space-x-3">
                <select class="px-4 py-2 bg-white border border-gray-100 text-[#204263] text-sm font-bold rounded-xl shadow-sm focus:ring-0">
                    <option>Derniers 30 jours</option>
                    <option>Dernière semaine</option>
                    <option>Cette année</option>
                </select>
                <button class="p-2 bg-white border border-gray-100 text-[#204263] rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </button>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Précision Moyenne</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-[#204263]">92.4%</h3>
                    <span class="text-[10px] font-bold text-emerald-500"><i class="fa-solid fa-arrow-up mr-1"></i> 1.2%</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Temps de Matching</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-[#204263]">1.5s</h3>
                    <span class="text-[10px] font-bold text-emerald-500"><i class="fa-solid fa-arrow-down mr-1"></i> 0.3s</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Matches Générés</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-[#204263]">12.5k</h3>
                    <span class="text-[10px] font-bold text-acpe-orange"><i class="fa-solid fa-arrow-up mr-1"></i> 8%</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Taux de Rejet</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-[#204263]">4.2%</h3>
                    <span class="text-[10px] font-bold text-emerald-500"><i class="fa-solid fa-arrow-down mr-1"></i> 0.5%</span>
                </div>
            </div>
        </div>

        <!-- Performance Chart Mockup -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Évolution de la pertinence</h3>
                    <p class="text-xs text-gray-400 mt-1">Comparaison entre l'IA et les critères manuels</p>
                </div>
                <div class="flex items-center space-x-6 text-[10px] font-bold">
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-acpe-blue mr-2"></span> Algorithme IA</span>
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-acpe-orange mr-2"></span> Critères fixes</span>
                </div>
            </div>
            <div class="h-64 flex items-end space-x-4">
                @for($i=0; $i<12; $i++)
                <div class="flex-1 flex flex-col items-center justify-end space-y-1">
                    <div class="w-full bg-acpe-orange/10 rounded-t-lg group relative" style="height: {{ rand(30, 60) }}%">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#204263] text-white text-[8px] px-1.5 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">78%</div>
                    </div>
                    <div class="w-full bg-acpe-blue rounded-t-lg group relative" style="height: {{ rand(60, 95) }}%">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#204263] text-white text-[8px] px-1.5 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">94%</div>
                    </div>
                    <span class="text-[8px] font-black text-gray-300 mt-2 uppercase">Mois {{ $i+1 }}</span>
                </div>
                @endfor
            </div>
        </div>

        <!-- Detailed Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="text-[10px] font-black text-acpe-blue uppercase tracking-widest">Top Secteurs (Matching)</h3>
                </div>
                <div class="p-6 space-y-6">
                    @foreach(['Informatique', 'Santé', 'Finance', 'Education'] as $secteur)
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-[#204263]">{{ $secteur }}</span>
                            <span class="text-gray-400 font-bold">{{ rand(80, 98) }}%</span>
                        </div>
                        <div class="h-2 w-full bg-gray-50 rounded-full overflow-hidden">
                            <div class="h-full bg-acpe-blue rounded-full" style="width: {{ rand(80, 98) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="text-[10px] font-black text-acpe-blue uppercase tracking-widest">Temps de réponse serveur</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center h-48">
                        <div class="relative h-40 w-40">
                            <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                                <circle cx="18" cy="18" r="16" fill="none" stroke="#f1f5f9" stroke-width="4"></circle>
                                <circle cx="18" cy="18" r="16" fill="none" stroke="#eda268" stroke-width="4" stroke-dasharray="85 100"></circle>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-black text-[#204263]">185ms</span>
                                <span class="text-[9px] font-black text-emerald-500 uppercase">Optimal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
