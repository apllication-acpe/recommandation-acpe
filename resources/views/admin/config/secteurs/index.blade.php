<x-admin-layout>
    @section('title', 'Référentiel des Secteurs d\'activité')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">Secteurs d'activité</h1>
                    <p class="text-gray-400 text-sm">Gérez les domaines professionnels disponibles.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.secteurs.create') }}" class="px-6 py-2.5 bg-acpe-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter un secteur
            </a>

        </div>

        <!-- Secteurs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($secteurs as $secteur)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 group hover:border-acpe-blue/30 transition-all">
                <div class="flex items-start justify-between mb-6">
                    <div class="h-12 w-12 rounded-2xl bg-blue-50 text-acpe-blue flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-briefcase text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1">
                        <a href="{{ route('admin.config.secteurs.show', $secteur) }}" class="p-2 text-gray-300 hover:text-acpe-blue transition-colors" title="Voir"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route('admin.config.secteurs.edit', $secteur) }}" class="p-2 text-gray-300 hover:text-blue-500 transition-colors" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a>

                        <form action="{{ route('admin.config.secteurs.destroy', $secteur) }}" method="POST" onsubmit="return confirm('Supprimer ce secteur ?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-300 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                </div>
                <h3 class="text-sm font-black text-[#204263] uppercase tracking-tight mb-1">{{ $secteur->libelle }}</h3>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400">{{ $secteur->offres_count }} offres associées</span>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase rounded-lg">Actif</span>
                </div>
            </div>
            @endforeach
        </div>


</x-admin-layout>
