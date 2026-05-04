<x-admin-layout>
    @section('title', 'Modération des Offres')

    <div class="space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Modération des Offres</h1>
                <p class="text-gray-400 text-sm">Vérifiez et approuvez les offres d'emploi avant leur publication officielle.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-xs font-bold text-[#7a9bb8]">Filtre :</span>
                <button class="px-4 py-1.5 bg-acpe-blue text-white text-[10px] font-black uppercase rounded-lg shadow-sm">En attente (15)</button>
                <button class="px-4 py-1.5 bg-white text-gray-400 text-[10px] font-black uppercase rounded-lg hover:bg-gray-50 transition-all border border-gray-100">Signalées (3)</button>
            </div>
        </div>

        <!-- Moderation Queue -->
        <div class="grid grid-cols-1 gap-6">
            @for($i=1; $i<=3; $i++)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:border-acpe-orange/30 transition-all">
                <div class="md:flex">
                    <!-- Left Info -->
                    <div class="p-8 flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[9px] font-black uppercase rounded">En attente de validation</span>
                            <span class="text-[9px] text-gray-300 font-bold uppercase tracking-widest">Soumis il y a 2h</span>
                        </div>
                        <h2 class="text-xl font-bold text-[#204263] mb-2">Ingénieur DevOps Cloud (H/F)</h2>
                        <div class="flex items-center space-x-4 text-xs text-gray-500 mb-6">
                            <span class="flex items-center"><i class="fa-solid fa-building mr-2 text-gray-300"></i> Tech Nova Solutions</span>
                            <span class="flex items-center"><i class="fa-solid fa-location-dot mr-2 text-gray-300"></i> Lyon, France</span>
                            <span class="flex items-center"><i class="fa-solid fa-file-contract mr-2 text-gray-300"></i> CDI</span>
                        </div>
                        <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-50 relative">
                            <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">Nous recherchons un profil passionné par l'automatisation et les architectures cloud pour rejoindre notre équipe grandissante. Vous serez responsable de la CI/CD...</p>
                            <button class="absolute bottom-2 right-4 text-[9px] font-black text-acpe-blue uppercase hover:underline">Lire la suite</button>
                        </div>
                    </div>
                    <!-- Right Actions -->
                    <div class="bg-gray-50/30 w-full md:w-64 p-8 border-l border-gray-50 flex flex-col justify-center space-y-3">
                        <button class="w-full py-3 bg-emerald-500 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-emerald-600 shadow-lg shadow-emerald-100 transition-all flex items-center justify-center">
                            <i class="fa-solid fa-check-double mr-2"></i> Approuver
                        </button>
                        <button class="w-full py-3 bg-white text-[#204263] text-xs font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 border border-gray-100 transition-all flex items-center justify-center">
                            <i class="fa-solid fa-pen-to-square mr-2"></i> Modifier
                        </button>
                        <button class="w-full py-3 bg-red-50 text-red-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all flex items-center justify-center">
                            <i class="fa-solid fa-ban mr-2"></i> Rejeter
                        </button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        
        <div class="flex justify-center py-8">
            <button class="px-8 py-3 bg-white text-acpe-blue border border-gray-100 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm">
                Voir plus d'offres en attente
            </button>
        </div>
    </div>
</x-admin-layout>
