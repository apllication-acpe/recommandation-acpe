<x-candidat-layout>
    @section('title', 'Recommandation Professionnelle')

    <div class="max-w-5xl mx-auto space-y-10 animate-slide-up">
        
        <div class="text-center space-y-4">
            <div class="h-20 w-20 bg-blue-50 text-acpe-blue rounded-3xl flex items-center justify-center text-3xl mx-auto shadow-sm border border-blue-100 mb-6">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <h1 class="text-3xl font-black text-[#204263]">Recommandations Professionnelles</h1>
            <p class="text-sm text-gray-500 font-medium max-w-2xl mx-auto">
                Notre algorithme analyse vos expériences, compétences et diplômes pour vous proposer les opportunités qui correspondent le mieux à votre parcours.
            </p>
        </div>

        <!-- Analysis Status Card -->
        <div class="bg-[#204263] rounded-3xl p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-10">
                <i class="fa-solid fa-microchip text-8xl text-white"></i>
            </div>
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-10 items-center">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-white mb-2">Analyse de profil complète</h3>
                        <p class="text-sm text-white/60 leading-relaxed">Nous avons scanné votre CV et vos 5 compétences clés pour générer ces recommandations.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @forelse($demandeur->competences->take(5) as $comp)
                            <span class="px-4 py-2 bg-white/10 text-white text-[9px] font-black uppercase tracking-widest rounded-xl border border-white/10">{{ $comp->libelle }}</span>
                        @empty
                            <a href="{{ route('candidat.profil.edit') }}" class="px-4 py-2 bg-white/10 text-white text-[9px] font-black uppercase tracking-widest rounded-xl border border-white/20 hover:bg-white/20 transition-colors">Profil à compléter</a>
                        @endforelse
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <a href="{{ route('candidat.reco.professionnelle') }}" class="inline-block bg-acpe-orange text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-orange-500/40 hover:scale-105 transition-all">
                        Rafraîchir l'analyse
                    </a>
                </div>
            </div>
        </div>

        <!-- Recommended Jobs List -->
        <div class="space-y-6">
            <h2 class="text-sm font-black text-[#204263] uppercase tracking-widest px-2">Les meilleures opportunités pour vous</h2>
            
            <div class="space-y-4">
                @foreach($recommandations as $reco)
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-8 hover:shadow-xl hover:border-acpe-orange/20 transition-all group">
                        <div class="flex items-center space-x-6 min-w-0">
                            <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-200 border border-gray-100 group-hover:bg-acpe-orange group-hover:text-white transition-colors flex-shrink-0">
                                <i class="fa-solid fa-briefcase text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center space-x-3 mb-1">
                                    <h3 class="text-base font-black text-[#204263] truncate">{{ $reco['offre']->titre }}</h3>
                                    @if($reco['score_global'] >= 90)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase rounded">Top Match</span>
                                    @endif
                                </div>
                                <p class="text-xs font-bold text-acpe-light-blue uppercase tracking-widest mb-3">{{ $reco['offre']->entreprise->nom_entreprise ?? 'Entreprise' }}</p>
                                <div class="flex flex-col space-y-1">
                                    @foreach($reco['justifications'] as $just)
                                        <p class="text-[11px] text-gray-400 font-medium italic"><i class="fa-solid fa-sparkles text-acpe-orange mr-1"></i> {{ $just }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-8 flex-shrink-0">
                            <div class="text-center border-l border-gray-100 pl-8">
                                <p class="text-[9px] font-black text-gray-300 uppercase mb-1">Score</p>
                                <p class="text-2xl font-black text-emerald-500">{{ $reco['score_global'] }}%</p>
                            </div>
                            <a href="{{ route('candidat.offres.show', $reco['offre']->id_offre) }}" class="bg-[#204263] text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/10 hover:bg-acpe-dark-blue transition-all">
                                Voir l'offre
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-candidat-layout>
