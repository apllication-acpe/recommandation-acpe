<x-admin-layout>
    @section('title', 'Détails du Pays')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config.nationalites') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">{{ $nationalite->libelle }}</h1>
                    <p class="text-gray-400 text-sm">Fiche d'identité du pays.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.nationalites.edit', $nationalite) }}" class="px-6 py-2.5 bg-purple-50 text-purple-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-purple-600 hover:text-white transition-all">
                <i class="fa-solid fa-pen mr-2"></i> Modifier
            </a>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-center space-x-12">
                <div class="text-center">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Code ISO</p>
                    <p class="text-4xl font-black text-purple-600 uppercase">{{ $nationalite->code_iso }}</p>
                </div>
                <div class="h-16 w-[1px] bg-gray-100"></div>
                <div class="text-center">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-2">Libellé</p>
                    <p class="text-xl font-black text-[#204263] uppercase tracking-tighter">{{ $nationalite->libelle }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
