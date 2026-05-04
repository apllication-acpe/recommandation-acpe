<x-admin-layout>
    @section('title', 'Référentiel des Compétences')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">Compétences</h1>
                    <p class="text-gray-400 text-sm">Gérez le dictionnaire des aptitudes.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.competences.create') }}" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Nouvelle Compétence
            </a>

        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" placeholder="Chercher une compétence..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border-none rounded-xl text-xs focus:ring-acpe-orange shadow-inner">
            </div>
            <select class="px-4 py-2 bg-gray-50 border-none rounded-xl text-xs font-bold text-gray-500">
                <option>Toutes les catégories</option>
                <option>Hard Skill</option>
                <option>Soft Skill</option>
                <option>Langue</option>
            </select>
        </div>

        <!-- Competences List -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($competences as $competence)
                <div class="group relative p-4 bg-gray-50/50 border border-gray-100 rounded-2xl hover:bg-white hover:border-emerald-500/30 hover:shadow-lg transition-all cursor-default text-center">
                    <p class="text-[10px] font-black text-[#204263] uppercase tracking-tight">{{ $competence->libelle }}</p>
                    <p class="text-[8px] font-bold text-gray-400 mt-1 uppercase">{{ $competence->categorie ?? 'Hard Skill' }}</p>
                    
                    <!-- Hover Actions -->
                    <div class="absolute inset-0 bg-white/90 opacity-0 group-hover:opacity-100 flex items-center justify-center space-x-2 transition-opacity rounded-2xl">
                        <a href="{{ route('admin.config.competences.show', $competence) }}" class="h-8 w-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                            <i class="fa-solid fa-eye text-[10px]"></i>
                        </a>
                        <a href="{{ route('admin.config.competences.edit', $competence) }}" class="h-8 w-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </a>

                        <form action="{{ route('admin.config.competences.destroy', $competence) }}" method="POST" onsubmit="return confirm('Supprimer cette compétence ?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="h-8 w-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>


</x-admin-layout>
