<x-admin-layout>
    @section('title', 'Ajustement des Algorithmes')

    <div class="max-w-5xl mx-auto space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Ajustement des Algorithmes</h1>
                <p class="text-gray-400 text-sm">Configurez les poids et les paramètres du moteur de recommandation.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-4 py-2 bg-white border border-gray-100 text-gray-400 text-sm font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    Restaurer par défaut
                </button>
                <button class="px-6 py-2 bg-acpe-blue text-white text-sm font-bold rounded-xl shadow-lg shadow-acpe-blue/10 hover:bg-acpe-dark-blue transition-all">
                    Enregistrer les réglages
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Weights Configuration -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="text-[10px] font-black text-acpe-blue uppercase tracking-widest">Poids des critères de matching</h3>
                    </div>
                    <div class="p-8 space-y-8">
                        <!-- Competences -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-bold text-[#204263]">Compétences techniques</label>
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg">45% (Priorité Haute)</span>
                            </div>
                            <input type="range" min="0" max="100" value="45" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-acpe-blue">
                            <p class="text-[10px] text-gray-400">Définit l'importance des "hard skills" dans le calcul du score de correspondance.</p>
                        </div>

                        <!-- Experience -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-bold text-[#204263]">Expérience professionnelle</label>
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg">30%</span>
                            </div>
                            <input type="range" min="0" max="100" value="30" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                            <p class="text-[10px] text-gray-400">Prend en compte la durée et la pertinence des postes occupés précédemment.</p>
                        </div>

                        <!-- Localisation -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-bold text-[#204263]">Proximité géographique</label>
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black rounded-lg">15%</span>
                            </div>
                            <input type="range" min="0" max="100" value="15" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-acpe-orange">
                            <p class="text-[10px] text-gray-400">Favorise les candidats résidant à proximité du lieu de travail.</p>
                        </div>

                        <!-- Diplômes -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-bold text-[#204263]">Niveau d'études / Diplômes</label>
                                <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[10px] font-black rounded-lg">10%</span>
                            </div>
                            <input type="range" min="0" max="100" value="10" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-gray-400">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Algorithm Info -->
            <div class="space-y-6">
                <div class="bg-[#204263] text-white rounded-2xl p-6 shadow-xl shadow-acpe-blue/20">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center">
                            <i class="fa-solid fa-brain text-acpe-orange"></i>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest">Version Actuelle</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-white/50 font-bold uppercase tracking-widest">Algorithme</p>
                            <p class="text-lg font-bold">NeuralMatch v4.2</p>
                        </div>
                        <div class="flex justify-between border-t border-white/5 pt-4">
                            <div>
                                <p class="text-[10px] text-white/50 font-bold">Dernière mise à jour</p>
                                <p class="text-xs font-bold">12 Avril 2026</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-white/50 font-bold">Statut</p>
                                <span class="text-[8px] font-black bg-emerald-500 text-white px-2 py-0.5 rounded uppercase">Optimisé</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-[#204263] mb-4">Tests A/B</h3>
                    <p class="text-[11px] text-gray-500 leading-relaxed mb-6">Activez le test A/B pour comparer deux configurations d'algorithmes sur un échantillon de 10% des utilisateurs.</p>
                    <button class="w-full py-3 bg-gray-50 text-acpe-blue text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-acpe-blue hover:text-white transition-all">
                        Lancer un nouveau test
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
