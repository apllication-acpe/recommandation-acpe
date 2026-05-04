<x-admin-layout>
    @section('title', 'Détails du Type de Contrat')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config.types') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">{{ $type->libelle }}</h1>
                    <p class="text-gray-400 text-sm">Configuration REST du type de contrat.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.types.edit', $type) }}" class="px-6 py-2.5 bg-orange-50 text-acpe-orange rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-acpe-orange hover:text-white transition-all">
                <i class="fa-solid fa-pen mr-2"></i> Modifier
            </a>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-gray-50 rounded-2xl">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Libellé</p>
                    <p class="text-lg font-black text-[#204263]">{{ $type->libelle }}</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Code</p>
                    <p class="text-lg font-black text-acpe-orange">{{ $type->code }}</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Durée indicative</p>
                    <p class="text-lg font-black text-[#204263]">{{ $type->duree ?: 'Indéterminée' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
