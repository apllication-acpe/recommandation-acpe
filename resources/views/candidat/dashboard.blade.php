<x-candidat-layout>
    <div class="space-y-8 animate-slide-up">
        
        <!-- Welcome Header & Profile Completion -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <div class="lg:col-span-2">
                <h1 class="text-3xl font-black text-[#204263]">Bonjour, {{ Auth::user()->prenom }} !</h1>
                <p class="text-gray-500 mt-2 font-medium">Prêt pour votre prochaine étape professionnelle ?</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-center">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Complétion du profil</p>
                    <span class="text-xs font-black text-acpe-orange">{{ $completion }}%</span>
                </div>
                <div class="h-2 w-full bg-gray-50 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-acpe-orange rounded-full transition-all duration-1000 shadow-lg shadow-orange-500/20" style="width: {{ $completion }}%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-3 font-bold italic text-center">Plus votre profil est complet, meilleures sont les recommandations !</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex items-center space-x-4 hover:shadow-md transition-all">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Postulées</p>
                    <p class="text-2xl font-black text-[#204263]">{{ $candidatures->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex items-center space-x-4 hover:shadow-md transition-all">
                <div class="h-12 w-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Favoris</p>
                    <p class="text-2xl font-black text-[#204263]">12</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex items-center space-x-4 hover:shadow-md transition-all">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alertes</p>
                    <p class="text-2xl font-black text-[#204263]">3</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex items-center space-x-4 hover:shadow-md transition-all">
                <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Vues Profil</p>
                    <p class="text-2xl font-black text-[#204263]">45</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recommended Offers -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-black text-[#204263] uppercase tracking-widest">Recommandées pour vous</h2>
                    <a href="#" class="text-[10px] font-black text-acpe-orange uppercase hover:underline">Voir tout</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($recommandations as $reco)
                        @php
                            $score = $reco->score_final ?? 85;
                            $colorClass = $score >= 80 ? 'text-emerald-500 bg-emerald-50 border-emerald-100' : ($score >= 50 ? 'text-orange-500 bg-orange-50 border-orange-100' : 'text-gray-500 bg-gray-50 border-gray-100');
                            $gradientClass = $score >= 80 ? 'from-emerald-500 to-teal-400' : ($score >= 50 ? 'from-orange-500 to-amber-400' : 'from-gray-400 to-gray-300');
                        @endphp
                        <div class="relative bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-white/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group overflow-hidden flex flex-col justify-between">
                            <div class="absolute top-0 left-0 h-1 bg-gradient-to-r {{ $gradientClass }} transition-all duration-1000" style="width: {{ $score }}%"></div>
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center border border-gray-200 group-hover:border-acpe-blue transition-colors shadow-inner text-gray-300 group-hover:text-acpe-blue">
                                        <i class="fa-solid fa-building text-2xl"></i>
                                    </div>
                                    <div class="flex flex-col items-end shrink-0">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Match</span>
                                        <div class="px-3 py-1 rounded-xl border font-black text-sm shadow-sm {{ $colorClass }}">
                                            {{ $score }}%
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-sm font-black text-[#204263] mb-1 group-hover:text-acpe-blue transition-colors">{{ $reco->offre->titre }}</h3>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $reco->offre->entreprise->raison_sociale ?? 'Entreprise Anonyme' }}</p>
                                
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100">{{ $reco->offre->typeContrat->libelle ?? 'CDI' }}</span>
                                    <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100">{{ $reco->offre->lieu ?? 'Dakar' }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex items-center justify-between border-t border-gray-50 pt-4">
                                <span class="text-[9px] font-black bg-gray-50 text-gray-400 px-2 py-1.5 rounded-lg border border-gray-100 uppercase"><i class="fa-solid fa-clock mr-1"></i>{{ $reco->offre->created_at ? $reco->offre->created_at->diffForHumans() : 'Récent' }}</span>
                                <a href="{{ route('candidat.offres.show', $reco->offre->id_offre) }}" class="px-4 py-2 bg-[#204263] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-acpe-blue shadow-lg shadow-blue-900/10 transition-all">Voir l'offre</a>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 bg-gray-50/50 rounded-3xl p-12 text-center border-2 border-dashed border-gray-100">
                            <i class="fa-solid fa-magnifying-glass text-4xl text-gray-200 mb-4"></i>
                            <p class="text-sm font-bold text-gray-400">Complétez votre profil pour recevoir des recommandations personnalisées.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Side Activity -->
            <div class="space-y-8">
                <!-- Recent Applications -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                    <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-8">Suivi de mes Candidatures</h3>
                    <div class="space-y-6">
                        @forelse($candidatures as $cand)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center space-x-4 min-w-0">
                                    <div class="h-1.5 w-1.5 rounded-full shrink-0 @if($cand->statut == 'acceptee') bg-emerald-400 @elseif($cand->statut == 'refusee') bg-red-400 @else bg-orange-400 @endif"></div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-[#204263] truncate">{{ $cand->offre->titre }}</h4>
                                        <p class="text-[10px] font-bold text-gray-300 uppercase mt-0.5">{{ $cand->statut }}</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-black text-gray-200 uppercase whitespace-nowrap ml-4">{{ $cand->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-300 italic text-center">Aucune candidature récente.</p>
                        @endforelse
                    </div>
                    <button class="w-full mt-10 py-3 bg-gray-50 rounded-xl text-[10px] font-black text-acpe-light-blue uppercase tracking-widest hover:bg-gray-100 transition-colors">Voir tout l'historique</button>
                </div>

                <!-- Profile Action Card -->
                <div class="bg-[#204263] p-8 rounded-3xl shadow-2xl relative overflow-hidden group">
                    <i class="fa-solid fa-id-card absolute -right-4 -bottom-4 text-7xl text-white/5 transform -rotate-12 group-hover:scale-110 transition-transform"></i>
                    <h3 class="text-sm font-bold text-white mb-2 relative z-10">Mettre à jour mon CV</h3>
                    <p class="text-xs text-white/60 mb-6 relative z-10 leading-relaxed">Augmentez vos chances d'être repéré par notre algorithme de recommandation.</p>
                    <a href="{{ route('candidat.profil.edit') }}" class="inline-block px-6 py-2.5 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all relative z-10">
                        Modifier le profil
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-candidat-layout>
