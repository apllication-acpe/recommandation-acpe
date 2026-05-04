<x-admin-layout>
    @section('title', 'Exportations de Candidatures')

    <div class="max-w-4xl mx-auto space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Exportations de Candidatures</h1>
                <p class="text-gray-400 text-sm">Gérez les exports de données pour les recruteurs et les rapports externes.</p>
            </div>
            <i class="fa-solid fa-file-export text-4xl text-gray-100 hidden md:block"></i>
        </div>

        <!-- Export Manager -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Config Export -->
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Sélectionner les colonnes</label>
                            <div class="space-y-2">
                                @foreach(['Identité du candidat', 'Contact (Email/Tél)', 'Offre concernée', 'Date de postulation', 'Statut actuel', 'Score de matching'] as $col)
                                <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-all border border-transparent has-[:checked]:border-acpe-blue/20">
                                    <input type="checkbox" class="rounded text-acpe-blue focus:ring-acpe-blue/20" checked>
                                    <span class="text-xs font-bold text-[#204263]">{{ $col }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Filtre de date</label>
                            <select class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 shadow-sm">
                                <option>Dernières 24 heures</option>
                                <option>Dernière semaine</option>
                                <option selected>Dernier mois</option>
                                <option>Toute la base</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Format de fichier</label>
                            <div class="flex items-center space-x-4">
                                <button class="flex-1 py-4 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 flex flex-col items-center justify-center space-y-2 hover:bg-emerald-100 transition-all">
                                    <i class="fa-solid fa-file-excel text-2xl"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Excel (CSV)</span>
                                </button>
                                <button class="flex-1 py-4 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100 flex flex-col items-center justify-center space-y-2 hover:bg-blue-100 transition-all">
                                    <i class="fa-solid fa-file-code text-2xl"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">JSON</span>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100 flex items-start space-x-4">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-1"></i>
                            <p class="text-[11px] text-amber-700 leading-relaxed">
                                L'exportation de données personnelles est soumise à la réglementation RGPD. Assurez-vous d'avoir les droits nécessaires.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-50">
                    <button class="w-full py-4 bg-[#204263] text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-acpe-dark-blue shadow-xl shadow-acpe-blue/10 transition-all">
                        Préparer le téléchargement (1,248 lignes)
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
