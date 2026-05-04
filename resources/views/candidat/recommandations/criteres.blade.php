<x-candidat-layout>
    @section('title', 'Recommandations par critères')

    <div class="max-w-6xl mx-auto space-y-8 animate-slide-up">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 bg-gradient-to-r from-[#204263] to-[#3a6896] p-8 rounded-3xl shadow-2xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h1 class="text-3xl font-black text-white">Le Radar à Emploi</h1>
                <p class="text-sm font-medium text-white/80 mt-2">Offres filtrées en temps réel selon votre profil et vos contraintes.</p>
            </div>
            <button class="relative z-10 bg-white text-[#204263] px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                Ajuster mon profil
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filter Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-[10px] font-black text-[#204263] uppercase tracking-widest mb-8">Vos critères</h3>
                    
                    <div class="space-y-8">
                        <!-- Localisation -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Localisation</label>
                            <select class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                                <option>Dakar, Sénégal</option>
                                <option>Thiès, Sénégal</option>
                                <option>Saint-Louis, Sénégal</option>
                            </select>
                        </div>

                        <!-- Type de contrat -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type de contrat</label>
                            <div class="space-y-2">
                                @foreach(['CDI', 'CDD', 'Stage', 'Freelance'] as $type)
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <div class="h-5 w-5 border-2 border-gray-100 rounded-lg flex items-center justify-center group-hover:border-acpe-orange transition-colors">
                                            @if($loop->first) <div class="h-2.5 w-2.5 bg-acpe-orange rounded-sm"></div> @endif
                                        </div>
                                        <span class="text-xs font-bold text-[#204263]">{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Salaire souhaité -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fourchette salariale (CFA)</label>
                            <input type="range" class="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-acpe-orange">
                            <div class="flex justify-between text-[10px] font-black text-gray-300 uppercase">
                                <span>150k</span>
                                <span>2M+</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Area -->
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100/50 rounded-3xl p-6 flex items-center space-x-5 shadow-sm">
                    <div class="h-12 w-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-emerald-500/30">
                        <i class="fa-solid fa-radar text-white animate-pulse"></i>
                    </div>
                    <p class="text-sm font-bold text-emerald-900 leading-relaxed">
                        L'algorithme a trouvé <span class="font-black text-emerald-600 text-lg">{{ count($recommandations) }} offres</span> correspondant à vos critères logistiques.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    @foreach($recommandations as $reco)
                        @php
                            $score = $reco['score_global'];
                            $colorClass = $score >= 80 ? 'text-emerald-500 bg-emerald-50 border-emerald-100' : ($score >= 50 ? 'text-orange-500 bg-orange-50 border-orange-100' : 'text-gray-500 bg-gray-50 border-gray-100');
                            $gradientClass = $score >= 80 ? 'from-emerald-500 to-teal-400' : ($score >= 50 ? 'from-orange-500 to-amber-400' : 'from-gray-400 to-gray-300');
                        @endphp
                        <div class="relative bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-white/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group overflow-hidden">
                            <!-- Progress Bar Background -->
                            <div class="absolute top-0 left-0 h-1 bg-gradient-to-r {{ $gradientClass }} transition-all duration-1000" style="width: {{ $score }}%"></div>
                            
                            <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                                <!-- Company Logo Area -->
                                <div class="h-20 w-20 shrink-0 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center text-gray-300 border border-gray-200 group-hover:border-acpe-blue transition-colors shadow-inner">
                                    <i class="fa-solid fa-building text-3xl"></i>
                                </div>
                                
                                <!-- Job Info -->
                                <div class="flex-grow">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="text-lg font-black text-[#204263] mb-1 group-hover:text-acpe-blue transition-colors">{{ $reco['offre']->titre }}</h3>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $reco['offre']->entreprise->raison_sociale ?? 'Entreprise Anonyme' }} • {{ $reco['offre']->lieu ?? 'Non spécifié' }}</p>
                                        </div>
                                        
                                        <!-- Score Badge -->
                                        <div class="flex flex-col items-end shrink-0">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Compatibilité</span>
                                            <div class="px-4 py-1.5 rounded-xl border font-black text-lg shadow-sm {{ $colorClass }}">
                                                {{ $score }}%
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Dynamic Justifications -->
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach($reco['justifications'] as $justification)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-blue-50/50 text-acpe-blue border border-blue-100/50">
                                                <i class="fa-solid fa-check-circle mr-2 opacity-70"></i>
                                                {{ $justification }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Bar -->
                            <div class="mt-6 pt-6 border-t border-gray-50 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-[10px] font-black bg-gray-50 text-gray-400 px-3 py-1.5 rounded-lg border border-gray-100 uppercase"><i class="fa-solid fa-clock mr-1"></i> Publié {{ $reco['offre']->created_at ? $reco['offre']->created_at->diffForHumans() : 'récemment' }}</span>
                                </div>
                                <div class="flex space-x-3">
                                    <button class="h-10 w-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-colors">
                                        <i class="fa-solid fa-heart"></i>
                                    </button>
                                    <a href="{{ route('candidat.offres.show', $reco['offre']->id_offre) }}" class="px-6 py-2.5 bg-[#204263] text-white rounded-xl flex items-center justify-center text-xs font-black uppercase tracking-widest hover:bg-acpe-blue transition-colors shadow-lg shadow-blue-900/20">
                                        Voir l'offre <i class="fa-solid fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</x-candidat-layout>
