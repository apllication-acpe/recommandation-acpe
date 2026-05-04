<x-admin-layout>
    @section('title', 'Analyse Recommandation IA')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Recommandation IA</h1>
                <p class="text-gray-400 text-sm">Analysez la pertinence des appariements générés par l'IA.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-6 py-2.5 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-sync mr-2"></i> Recalculer les scores
                </button>
            </div>
        </div>

        <!-- IA Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">Précision Moyenne</p>
                <p class="text-2xl font-black text-[#204263]">89.4%</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">Appariements/Jour</p>
                <p class="text-2xl font-black text-[#204263]">1,240</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">Satisfaction Recruteur</p>
                <p class="text-2xl font-black text-emerald-500">4.8/5</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-[9px] font-black text-gray-300 uppercase mb-2">Gain de temps</p>
                <p class="text-2xl font-black text-acpe-orange">-45%</p>
            </div>
        </div>

        <!-- Latest Matches -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Derniers Appariements Pertinents</h3>
                <span class="text-[10px] font-bold text-gray-400">Filtré par score > 75%</span>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Candidat</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Offre Cible</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Score IA</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Analyse</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="text-[11px] font-black text-[#204263]">Arnaud Ngoma</p>
                            <p class="text-[9px] text-gray-400">Développeur PHP / Laravel</p>
                        </td>
                        <td class="px-8 py-4">
                            <p class="text-[11px] font-black text-[#204263]">Lead Developer</p>
                            <p class="text-[9px] text-gray-400">Congo Digital SA</p>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg">94%</span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <button class="h-8 w-8 rounded-lg bg-gray-50 text-gray-400 hover:text-acpe-blue transition-all">
                                <i class="fa-solid fa-magnifying-glass-chart text-xs"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
