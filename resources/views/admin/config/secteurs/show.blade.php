<x-admin-layout>
    @section('title', 'Détails du Secteur')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config.secteurs') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">{{ $secteur->libelle }}</h1>
                    <p class="text-gray-400 text-sm">Aperçu détaillé du secteur d'activité.</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.config.secteurs.edit', $secteur) }}" class="px-6 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all">
                    <i class="fa-solid fa-pen mr-2"></i> Modifier
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-4">Description / Détails techniques</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $secteur->code_secteur_description ?: 'Aucune description disponible pour ce secteur.' }}
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-6">Offres associées</h3>
                    @if($secteur->offres_count > 0)
                        <div class="space-y-4">
                            <!-- Ici on pourrait lister quelques offres si on avait la relation chargée -->
                            <p class="text-xs text-gray-500 italic">Ce secteur est utilisé par {{ $secteur->offres_count }} offres d'emploi actuellement.</p>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fa-solid fa-folder-open text-gray-200 text-3xl mb-3"></i>
                            <p class="text-xs text-gray-400">Aucune offre associée à ce secteur pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-6">Statistiques</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400 font-bold">Total Offres</span>
                            <span class="text-xs font-black text-[#204263]">{{ $secteur->offres_count }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400 font-bold">Statut</span>
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase rounded-lg">Actif</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400 font-bold">Créé le</span>
                            <span class="text-xs font-black text-[#204263]">{{ $secteur->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
