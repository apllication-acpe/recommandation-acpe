<x-candidat-layout>
    @section('title', 'Recherche d\'offres')

    <div class="space-y-8 animate-slide-up">
        
        <!-- Search Header -->
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h1 class="text-3xl font-black text-[#204263] mb-8 text-center">Trouvez votre prochain défi</h1>
            
            <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-4 p-2 bg-gray-50 rounded-[2rem] border border-gray-100 shadow-inner">
                <div class="flex-1 flex items-center px-6 border-r border-gray-200 md:border-r">
                    <i class="fa-solid fa-briefcase text-acpe-light-blue mr-4"></i>
                    <input type="text" placeholder="Intitulé du poste, mots-clés..." class="w-full bg-transparent border-none text-sm font-bold text-[#204263] focus:ring-0 placeholder:text-gray-300 py-4">
                </div>
                <div class="flex-1 flex items-center px-6">
                    <i class="fa-solid fa-location-dot text-acpe-light-blue mr-4"></i>
                    <input type="text" placeholder="Localisation (Ex: Dakar)" class="w-full bg-transparent border-none text-sm font-bold text-[#204263] focus:ring-0 placeholder:text-gray-300 py-4">
                </div>
                <button class="bg-[#204263] text-white px-10 py-4 rounded-[1.5rem] text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-900/20 hover:bg-acpe-dark-blue transition-all">
                    Rechercher
                </button>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="flex flex-wrap items-center gap-4 justify-center md:justify-start">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mr-2">Filtrer par :</span>
            <div class="relative group">
                <button class="bg-white px-6 py-2.5 rounded-xl border border-gray-100 text-[10px] font-black text-[#204263] uppercase tracking-widest flex items-center hover:border-acpe-orange transition-colors">
                    Type de contrat <i class="fa-solid fa-chevron-down ml-3 text-[8px] text-gray-300"></i>
                </button>
            </div>
            <div class="relative group">
                <button class="bg-white px-6 py-2.5 rounded-xl border border-gray-100 text-[10px] font-black text-[#204263] uppercase tracking-widest flex items-center hover:border-acpe-orange transition-colors">
                    Secteur <i class="fa-solid fa-chevron-down ml-3 text-[8px] text-gray-300"></i>
                </button>
            </div>
            <div class="relative group">
                <button class="bg-white px-6 py-2.5 rounded-xl border border-gray-100 text-[10px] font-black text-[#204263] uppercase tracking-widest flex items-center hover:border-acpe-orange transition-colors">
                    Salaire <i class="fa-solid fa-chevron-down ml-3 text-[8px] text-gray-300"></i>
                </button>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($offres as $offre)
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="flex justify-between items-start mb-8">
                        <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-200 border border-gray-100 group-hover:bg-acpe-orange group-hover:text-white transition-colors">
                            <i class="fa-solid fa-building text-2xl"></i>
                        </div>
                        <button class="h-10 w-10 bg-gray-50 text-gray-300 rounded-xl flex items-center justify-center hover:text-red-400 hover:bg-red-50 transition-colors">
                            <i class="fa-solid fa-heart text-xs"></i>
                        </button>
                    </div>
                    
                    <h3 class="text-base font-black text-[#204263] mb-2 group-hover:text-acpe-orange transition-colors leading-tight">{{ $offre->titre }}</h3>
                    <p class="text-xs font-bold text-acpe-light-blue uppercase tracking-widest mb-6">{{ $offre->entreprise->nom_entreprise ?? 'Entreprise' }}</p>
                    
                    <div class="flex flex-wrap gap-2 mb-8">
                        <span class="px-3 py-1.5 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100 flex items-center">
                            <i class="fa-solid fa-location-dot mr-1.5 text-acpe-orange/50"></i> {{ $offre->lieu }}
                        </span>
                        <span class="px-3 py-1.5 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100">
                            {{ $offre->typeContrat->libelle ?? 'CDI' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-8 border-t border-gray-50">
                        <span class="text-[10px] font-black text-gray-300 uppercase italic">il y a {{ $loop->index + 1 }} j</span>
                        <a href="{{ route('candidat.offres.show', $offre) }}" class="bg-[#204263] text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/10 hover:bg-acpe-dark-blue transition-all">
                            Voir plus
                        </a>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 py-20 text-center space-y-4">
                    <i class="fa-solid fa-magnifying-glass text-5xl text-gray-100"></i>
                    <p class="text-sm font-bold text-gray-400">Aucune offre ne correspond à votre recherche pour le moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $offres->links() }}
        </div>

    </div>
</x-candidat-layout>
