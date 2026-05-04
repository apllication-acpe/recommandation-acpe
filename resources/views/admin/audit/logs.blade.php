<x-admin-layout>
    @section('title', 'Logs Système')

    <div class="space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Logs Système</h1>
                <p class="text-gray-400 text-sm">Surveillez l'activité technique et les erreurs de la plateforme en temps réel.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-4 py-2 bg-white border border-gray-100 text-[#204263] text-sm font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-download mr-2"></i> Télécharger les logs
                </button>
                <button class="px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition-all">
                    <i class="fa-solid fa-trash-can mr-2"></i> Effacer tout
                </button>
            </div>
        </div>

        <!-- Log Viewer Container -->
        <div class="bg-[#1e1e1e] rounded-2xl shadow-2xl border border-white/5 overflow-hidden font-mono">
            <!-- Toolbar -->
            <div class="px-6 py-4 border-b border-white/5 bg-white/5 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-[#ff5f56]"></span>
                        <span class="w-3 h-3 rounded-full bg-[#ffbd2e]"></span>
                        <span class="w-3 h-3 rounded-full bg-[#27c93f]"></span>
                    </div>
                    <div class="h-4 w-[1px] bg-white/10 mx-2"></div>
                    <div class="flex items-center space-x-3 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        <span class="text-emerald-500"><i class="fa-solid fa-circle text-[6px] mr-1"></i> Live</span>
                        <span>Laravel.log</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <select class="bg-transparent border-none text-[10px] font-bold text-gray-400 focus:ring-0 cursor-pointer hover:text-white transition-colors">
                        <option value="all">Tous les niveaux</option>
                        <option value="error">Erreurs uniquement</option>
                        <option value="warning">Avertissements</option>
                    </select>
                </div>
            </div>

            <!-- Terminal Content -->
            <div class="p-6 h-[500px] overflow-y-auto custom-scrollbar space-y-3 text-[12px] leading-relaxed">
                <div class="flex space-x-4 opacity-80">
                    <span class="text-gray-500 whitespace-nowrap">[2026-05-02 05:30:12]</span>
                    <span class="text-emerald-500 font-bold uppercase w-16">INFO</span>
                    <span class="text-gray-300">Application started. Environment: production</span>
                </div>
                <div class="flex space-x-4">
                    <span class="text-gray-500 whitespace-nowrap">[2026-05-02 05:31:45]</span>
                    <span class="text-blue-500 font-bold uppercase w-16">DEBUG</span>
                    <span class="text-gray-300">User #45 (Recruteur) logged in from IP 192.168.1.1</span>
                </div>
                <div class="flex space-x-4 bg-red-500/10 -mx-6 px-6 py-1">
                    <span class="text-gray-500 whitespace-nowrap">[2026-05-02 05:35:22]</span>
                    <span class="text-red-500 font-bold uppercase w-16">ERROR</span>
                    <span class="text-gray-100 italic">SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'admin@acpe.com' for key 'users_email_unique'</span>
                </div>
                <div class="flex space-x-4">
                    <span class="text-gray-500 whitespace-nowrap">[2026-05-02 05:38:01]</span>
                    <span class="text-amber-500 font-bold uppercase w-16">WARN</span>
                    <span class="text-gray-300">Memory usage exceeded 128MB during report generation</span>
                </div>
                <div class="flex space-x-4 opacity-80">
                    <span class="text-gray-500 whitespace-nowrap">[2026-05-02 05:40:15]</span>
                    <span class="text-emerald-500 font-bold uppercase w-16">INFO</span>
                    <span class="text-gray-300">Backup scheduled successfully. Target: AWS-S3</span>
                </div>
                
                @for($i=0; $i<10; $i++)
                <div class="flex space-x-4 opacity-80">
                    <span class="text-gray-500 whitespace-nowrap">[{{ now()->subMinutes(rand(1, 60))->format('Y-m-d H:i:s') }}]</span>
                    <span class="text-emerald-500 font-bold uppercase w-16">INFO</span>
                    <span class="text-gray-300">Worker processing job: App\Jobs\ProcessRecommendationEngine</span>
                </div>
                @endfor
            </div>

            <!-- Footer Stats -->
            <div class="px-6 py-3 border-t border-white/5 bg-white/5 flex items-center justify-between text-[10px] font-bold">
                <div class="flex items-center space-x-6 text-gray-400">
                    <span class="flex items-center"><i class="fa-solid fa-circle text-emerald-500 text-[6px] mr-2"></i> 2.4k INFO</span>
                    <span class="flex items-center"><i class="fa-solid fa-circle text-amber-500 text-[6px] mr-2"></i> 12 WARN</span>
                    <span class="flex items-center"><i class="fa-solid fa-circle text-red-500 text-[6px] mr-2"></i> 3 ERROR</span>
                </div>
                <div class="text-gray-500 uppercase tracking-tighter">
                    File size: 1.2 MB | Last modified: Just now
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
