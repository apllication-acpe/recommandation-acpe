<x-admin-layout>
    @section('title', 'Détails de la Langue')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config.langues') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">{{ $langue->libelle }}</h1>
                    <p class="text-gray-400 text-sm">Informations linguistiques.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.langues.edit', $langue) }}" class="px-6 py-2.5 bg-pink-50 text-pink-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-pink-600 hover:text-white transition-all">
                <i class="fa-solid fa-pen mr-2"></i> Modifier
            </a>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-8">
                <div class="h-20 w-20 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center">
                    <i class="fa-solid fa-language text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-[#204263] uppercase tracking-tight">{{ $langue->libelle }}</h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Code ISO : {{ $langue->code_iso ?: 'Non défini' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
