<x-admin-layout>
    @section('title', 'Rapports Exportables')

    <div class="max-w-4xl mx-auto space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Rapports Exportables</h1>
                <p class="text-gray-400 text-sm">Générez et téléchargez des rapports détaillés au format PDF ou Excel.</p>
            </div>
            <i class="fa-solid fa-file-contract text-4xl text-gray-100 hidden md:block"></i>
        </div>

        <!-- Report Generation Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Type de rapport -->
                    <div class="space-y-4">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Type de rapport</label>
                        <div class="grid grid-cols-1 gap-3">
                            <label class="relative flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20 has-[:checked]:bg-white">
                                <input type="radio" name="report_type" class="sr-only" checked>
                                <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#204263]">Activité Mensuelle</p>
                                    <p class="text-[10px] text-gray-400">Statistiques globales du mois écoulé</p>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20 has-[:checked]:bg-white">
                                <input type="radio" name="report_type" class="sr-only">
                                <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#204263]">Rapport Entreprises</p>
                                    <p class="text-[10px] text-gray-400">Analyse du recrutement par secteur</p>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20 has-[:checked]:bg-white">
                                <input type="radio" name="report_type" class="sr-only">
                                <div class="h-10 w-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-user-group"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#204263]">Base Candidats</p>
                                    <p class="text-[10px] text-gray-400">Exportation des profils et compétences</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Options & Dates -->
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Période</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="date" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs focus:ring-2 focus:ring-acpe-blue/20">
                                <input type="date" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs focus:ring-2 focus:ring-acpe-blue/20">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Format de sortie</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="format" class="sr-only" checked>
                                    <span class="px-6 py-2 bg-gray-50 text-gray-400 text-[10px] font-black rounded-xl group-has-[:checked]:bg-red-50 group-has-[:checked]:text-red-500 transition-all border border-transparent group-has-[:checked]:border-red-100 uppercase tracking-widest">PDF</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="format" class="sr-only">
                                    <span class="px-6 py-2 bg-gray-50 text-gray-400 text-[10px] font-black rounded-xl group-has-[:checked]:bg-emerald-50 group-has-[:checked]:text-emerald-500 transition-all border border-transparent group-has-[:checked]:border-emerald-100 uppercase tracking-widest">Excel (XLSX)</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4">
                            <div class="p-4 bg-acpe-blue rounded-2xl text-white">
                                <div class="flex items-center space-x-3 mb-2">
                                    <i class="fa-solid fa-circle-info text-acpe-orange"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Note</span>
                                </div>
                                <p class="text-[11px] leading-relaxed opacity-80">La génération peut prendre quelques secondes pour les gros volumes de données.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-50">
                    <button class="w-full py-4 bg-acpe-blue text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-acpe-dark-blue shadow-xl shadow-acpe-blue/10 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-gear fa-spin mr-3 opacity-50"></i> Générer le rapport maintenant
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Exports -->
        <div class="space-y-4">
            <h3 class="text-xs font-black text-gray-300 uppercase tracking-widest ml-1">Exports récents</h3>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-50">
                @for($i=0; $i<3; $i++)
                <div class="px-6 py-4 flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="h-10 w-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-red-50 group-hover:text-red-500 transition-all">
                            <i class="fa-solid fa-file-pdf text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#204263]">Rapport_Activite_Avril_2026.pdf</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Généré le 01/05/2026 • 2.4 MB</p>
                        </div>
                    </div>
                    <button class="h-10 w-10 rounded-full hover:bg-gray-50 text-gray-400 hover:text-acpe-blue transition-all">
                        <i class="fa-solid fa-download"></i>
                    </button>
                </div>
                @endfor
            </div>
        </div>
    </div>
</x-admin-layout>
