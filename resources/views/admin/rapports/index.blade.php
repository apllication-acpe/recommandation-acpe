<x-admin-layout>
    @section('title', 'Rapports & Statistiques')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Rapports & Statistiques</h1>
                <p class="text-gray-400 text-sm">Analysez les performances et exportez les données clés.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-6 py-2.5 bg-acpe-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Exporter PDF Mensuel
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Taux de réponse</p>
                <h3 class="text-3xl font-black text-[#204263]">64.2%</h3>
                <div class="mt-4 h-2 w-full bg-gray-50 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: 64%"></div>
                </div>
                <p class="mt-4 text-[10px] font-bold text-green-500"><i class="fa-solid fa-caret-up mr-1"></i> +4.1% <span class="text-gray-300 ml-1">vs mois dernier</span></p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Offres actives</p>
                <h3 class="text-3xl font-black text-[#204263]">128</h3>
                <div class="mt-4 h-2 w-full bg-gray-50 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: 78%"></div>
                </div>
                <p class="mt-4 text-[10px] font-bold text-green-500"><i class="fa-solid fa-caret-up mr-1"></i> +12 <span class="text-gray-300 ml-1">nouvelles cette semaine</span></p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Temps moyen recrutement</p>
                <h3 class="text-3xl font-black text-[#204263]">14j</h3>
                <div class="mt-4 h-2 w-full bg-gray-50 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-500 rounded-full" style="width: 45%"></div>
                </div>
                <p class="mt-4 text-[10px] font-bold text-emerald-500"><i class="fa-solid fa-clock mr-1"></i> -2j <span class="text-gray-300 ml-1">amélioration</span></p>
            </div>
        </div>

        <!-- Exportable Reports -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50">
                <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Rapports exportables</h3>
            </div>
            <div class="divide-y divide-gray-50">
                <div class="px-8 py-6 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center space-x-6">
                        <div class="h-12 w-12 rounded-2xl bg-blue-50 text-acpe-blue flex items-center justify-center">
                            <i class="fa-solid fa-users-gear text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-[#204263] uppercase tracking-tight">Activité des Candidats</p>
                            <p class="text-[10px] text-gray-400">Inscriptions, connexions et candidatures sur la période.</p>
                        </div>
                    </div>
                    <button class="h-10 px-6 bg-white border border-gray-100 text-[#204263] text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 shadow-sm">Exporter CSV</button>
                </div>
                <div class="px-8 py-6 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center space-x-6">
                        <div class="h-12 w-12 rounded-2xl bg-orange-50 text-acpe-orange flex items-center justify-center">
                            <i class="fa-solid fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-[#204263] uppercase tracking-tight">Performance des Entreprises</p>
                            <p class="text-[10px] text-gray-400">Nombre d'offres, vues et taux de transformation par entreprise.</p>
                        </div>
                    </div>
                    <button class="h-10 px-6 bg-white border border-gray-100 text-[#204263] text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 shadow-sm">Exporter CSV</button>
                </div>
                <div class="px-8 py-6 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center space-x-6">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-database text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-[#204263] uppercase tracking-tight">Audit Système Complet</p>
                            <p class="text-[10px] text-gray-400">Logs d'activité, erreurs et accès sécurisés.</p>
                        </div>
                    </div>
                    <button class="h-10 px-6 bg-white border border-gray-100 text-[#204263] text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 shadow-sm">Exporter CSV</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
