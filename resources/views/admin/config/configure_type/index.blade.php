<x-admin-layout>
    @section('title', 'Configuration des Types de Contrat')

    <div class="max-w-4xl mx-auto space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Types de Contrat</h1>
                <p class="text-gray-400 text-sm">Gérez les différents types de contrats de travail disponibles sur la plateforme.</p>
            </div>
            <button class="px-6 py-2 bg-acpe-orange text-white text-sm font-bold rounded-xl shadow-lg shadow-acpe-orange/10 hover:bg-acpe-orange/90 transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter un type
            </button>
        </div>

        <!-- Contract Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($types as $type)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-start justify-between group hover:border-blue-200 transition-all">
                <div class="flex items-start space-x-4">
                    <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center font-black text-xs shadow-sm">
                        {{ substr($type->libelle, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#204263]">{{ $type->libelle }}</h3>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $type->description ?? 'Pas de description' }}</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest"><i class="fa-solid fa-briefcase mr-1.5"></i> {{ $type->offres_count }} Offres</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-all">
                    <button class="p-2 text-gray-300 hover:text-acpe-blue transition-colors"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="p-2 text-gray-300 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-10 text-gray-400">Aucun type de contrat défini</div>
            @endforelse
        </div>

        <!-- Information Box -->
        <div class="p-6 bg-[#204263] rounded-3xl text-white relative overflow-hidden shadow-2xl">
            <div class="relative z-10">
                <h3 class="text-lg font-bold mb-2">Impact sur le matching</h3>
                <p class="text-xs opacity-70 leading-relaxed max-w-xl">
                    Les types de contrat sont utilisés par l'algorithme pour filtrer les recommandations. Un changement de nom ou une suppression peut affecter les offres en cours.
                </p>
            </div>
            <i class="fa-solid fa-circle-info absolute -right-4 -bottom-4 text-8xl opacity-10 rotate-12"></i>
        </div>
    </div>
</x-admin-layout>
