<x-admin-layout>
    @section('title', 'Détails du Diplôme')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config.diplomes') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">{{ $diplome->libelle }}</h1>
                    <p class="text-gray-400 text-sm">Fiche technique du diplôme.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.diplomes.edit', $diplome) }}" class="px-6 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                <i class="fa-solid fa-pen mr-2"></i> Modifier
            </a>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="flex items-center space-x-6">
                    <div class="h-16 w-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-scroll text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase">Libellé</p>
                        <p class="text-lg font-black text-[#204263] uppercase">{{ $diplome->libelle }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="h-16 w-16 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase">Niveau</p>
                        <p class="text-lg font-black text-[#204263] uppercase">{{ $diplome->niveau }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="h-16 w-16 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center">
                        <i class="fa-solid fa-microscope text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase">Spécialité</p>
                        <p class="text-lg font-black text-[#204263] uppercase">{{ $diplome->specialite ?: 'Généraliste' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
