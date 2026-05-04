<x-admin-layout>
    @section('title', 'Détails de la Compétence')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config.competences') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">{{ $competence->libelle }}</h1>
                    <p class="text-gray-400 text-sm">Analyse de la compétence dans le référentiel.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.competences.edit', $competence) }}" class="px-6 py-2.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all">
                <i class="fa-solid fa-pen mr-2"></i> Modifier
            </a>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="h-24 w-24 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-lightbulb text-4xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-lg font-black text-[#204263] mb-1">{{ $competence->libelle }}</h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $competence->categorie }}</p>
                </div>
                <div class="px-8 py-4 bg-gray-50 rounded-2xl text-center">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Utilisation IA</p>
                    <p class="text-xl font-black text-emerald-600">Active</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
