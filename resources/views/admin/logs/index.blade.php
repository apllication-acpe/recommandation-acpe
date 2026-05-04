<x-admin-layout>
    @section('title', 'Audit & Logs Système')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Audit & Logs</h1>
                <p class="text-gray-400 text-sm">Surveillez l'activité en temps réel de la plateforme.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-6 py-2.5 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                    <i class="fa-solid fa-broom mr-2"></i> Purger les logs
                </button>
            </div>
        </div>

        <!-- Real-time Log Stream -->
        <div class="bg-[#1a3550] rounded-3xl shadow-2xl overflow-hidden border border-white/5">
            <div class="px-8 py-4 bg-black/20 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">En direct</span>
                </div>
                <div class="flex space-x-4 text-[9px] font-bold text-white/40 uppercase">
                    <span>{{ now()->format('d/m/Y H:i:s') }}</span>
                    <span>Server: ACPE-PROD-01</span>
                </div>
            </div>
            <div class="p-8 font-mono text-xs space-y-2 max-h-[500px] overflow-y-auto custom-scrollbar">
                <p class="text-emerald-400/80"><span class="text-white/20">[{{ now()->subMinutes(2)->format('H:i:s') }}]</span> <span class="px-1.5 py-0.5 bg-emerald-500/10 rounded border border-emerald-500/20 mr-2 text-[8px]">INFO</span> User #12 logged in from IP 197.157.2.45</p>
                <p class="text-blue-400/80"><span class="text-white/20">[{{ now()->subMinutes(5)->format('H:i:s') }}]</span> <span class="px-1.5 py-0.5 bg-blue-500/10 rounded border border-blue-500/20 mr-2 text-[8px]">DB</span> Query executed: SELECT * FROM offres WHERE active = 1 (14ms)</p>
                <p class="text-emerald-400/80"><span class="text-white/20">[{{ now()->subMinutes(8)->format('H:i:s') }}]</span> <span class="px-1.5 py-0.5 bg-emerald-500/10 rounded border border-emerald-500/20 mr-2 text-[8px]">INFO</span> New company registered: "Congo Tech Solutions"</p>
                <p class="text-orange-400/80"><span class="text-white/20">[{{ now()->subMinutes(12)->format('H:i:s') }}]</span> <span class="px-1.5 py-0.5 bg-orange-500/10 rounded border border-orange-500/20 mr-2 text-[8px]">WARN</span> Failed login attempt for user "admin@test.com" from IP 45.12.33.1</p>
                <p class="text-emerald-400/80"><span class="text-white/20">[{{ now()->subMinutes(15)->format('H:i:s') }}]</span> <span class="px-1.5 py-0.5 bg-emerald-500/10 rounded border border-emerald-500/20 mr-2 text-[8px]">INFO</span> Recommendation engine updated for User #45</p>
                <p class="text-white/40 italic mt-4 text-[10px]">... fin du flux récent ...</p>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Historique des actions</h3>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 bg-gray-50 text-[9px] font-black uppercase text-gray-400 rounded-xl">Aujourd'hui</button>
                    <button class="px-4 py-2 bg-gray-50 text-[9px] font-black uppercase text-gray-400 rounded-xl">7 derniers jours</button>
                </div>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Utilisateur</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Module</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="text-[11px] font-black text-[#204263]">Jean-Pierre M.</p>
                            <p class="text-[9px] text-gray-400">admin@acpe.cg</p>
                        </td>
                        <td class="px-8 py-4">
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase rounded">Validation</span>
                            <span class="text-[10px] text-gray-500 ml-2">Entreprise "Digital Hub"</span>
                        </td>
                        <td class="px-8 py-4 text-[10px] text-gray-400 font-bold uppercase">Entreprises</td>
                        <td class="px-8 py-4 text-right text-[10px] text-gray-300">Il y a 2h</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="text-[11px] font-black text-[#204263]">Marie L.</p>
                            <p class="text-[9px] text-gray-400">moderateur@acpe.cg</p>
                        </td>
                        <td class="px-8 py-4">
                            <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[8px] font-black uppercase rounded">Suppression</span>
                            <span class="text-[10px] text-gray-500 ml-2">Offre inappropriée #452</span>
                        </td>
                        <td class="px-8 py-4 text-[10px] text-gray-400 font-bold uppercase">Offres</td>
                        <td class="px-8 py-4 text-right text-[10px] text-gray-300">Hier, 14:30</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
