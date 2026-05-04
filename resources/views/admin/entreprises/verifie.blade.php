<x-admin-layout>
    @section('title', 'Détails de l\'entreprise')

    <div class="space-y-8 animate-slide-up pb-12">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.entreprises') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-[#204263]">Fiche Entreprise</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Company Info -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-32 bg-gradient-to-br from-acpe-blue to-blue-900 relative">
                        <div class="absolute -bottom-10 left-8">
                            <div class="h-20 w-20 rounded-2xl bg-white border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($entreprise->raison_sociale) }}&background=random&size=128" class="h-full w-full object-cover" alt="Logo">
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pt-14 pb-8">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-xl font-black text-[#204263]">{{ $entreprise->raison_sociale }}</h2>
                            @if($entreprise->verifiee)
                                <span class="h-6 w-6 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-[10px]" title="Entreprise vérifiée">
                                    <i class="fa-solid fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">{{ $entreprise->forme_juridique }}</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 text-xs">
                                <i class="fa-solid fa-envelope text-gray-300 w-4"></i>
                                <span class="text-[#204263] font-medium">{{ $entreprise->email_contact ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="flex items-center space-x-3 text-xs">
                                <i class="fa-solid fa-phone text-gray-300 w-4"></i>
                                <span class="text-[#204263] font-medium">{{ $entreprise->telephone ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="flex items-center space-x-3 text-xs">
                                <i class="fa-solid fa-location-dot text-gray-300 w-4"></i>
                                <span class="text-[#204263] font-medium">{{ $entreprise->adresse ?? 'Non renseigné' }}</span>
                            </div>
                            @if($entreprise->site_web)
                                <div class="flex items-center space-x-3 text-xs">
                                    <i class="fa-solid fa-globe text-gray-300 w-4"></i>
                                    <a href="{{ $entreprise->site_web_formate }}" target="_blank" class="text-acpe-blue font-bold hover:underline">{{ $entreprise->site_web }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-8 pt-8 border-t border-gray-50 flex items-center justify-between">
                            <div class="text-center">
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Taille</p>
                                <p class="text-xs font-bold text-[#204263]">{{ $entreprise->taille }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Inscrit le</p>
                                <p class="text-xs font-bold text-[#204263]">{{ $entreprise->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @if(!$entreprise->verifiee)
                        <div class="px-8 pb-8">
                            <form action="{{ route('admin.entreprises.verify', $entreprise) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-emerald-500 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all">
                                    Vérifier l'entreprise
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Team -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-8">
                    <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6">Recruteurs ({{ $entreprise->recruteurs->count() }})</h3>
                    <div class="space-y-6">
                        @foreach($entreprise->recruteurs as $recruteur)
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($recruteur->user->nom_complet) }}&background=random" alt="">
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-xs font-bold text-[#204263] truncate">{{ $recruteur->user->nom_complet }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $recruteur->fonction ?? 'Recruteur' }}</p>
                                </div>
                                <a href="{{ route('messagerie.create', ['receiver_id' => $recruteur->user->id]) }}" class="h-8 w-8 rounded-lg bg-blue-50 text-acpe-blue flex items-center justify-center text-xs hover:bg-acpe-blue hover:text-white transition-all">
                                    <i class="fa-solid fa-envelope"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Job Offers -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Offres d'emploi ({{ $entreprise->offres->count() }})</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($entreprise->offres as $offre)
                            <div class="px-8 py-6 flex items-center justify-between group hover:bg-gray-50 transition-colors">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <h4 class="text-sm font-bold text-[#204263]">{{ $offre->titre }}</h4>
                                        <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest {{ $offre->active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                            {{ $offre->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-4 text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                        <span><i class="fa-solid fa-file-contract mr-1"></i> {{ $offre->typeContrat->libelle ?? '—' }}</span>
                                        <span><i class="fa-solid fa-eye mr-1"></i> {{ $offre->nb_vues ?? 0 }} vues</span>
                                        <span><i class="fa-solid fa-calendar mr-1"></i> Expire le {{ $offre->date_expiration->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('admin.offres.toggle', $offre) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 text-gray-300 {{ $offre->active ? 'hover:text-orange-500 hover:bg-orange-50' : 'hover:text-emerald-500 hover:bg-emerald-50' }} transition-all rounded-lg">
                                            <i class="fa-solid {{ $offre->active ? 'fa-eye-slash' : 'fa-eye' }} text-xs"></i>
                                        </button>
                                    </form>
                                    <button class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all rounded-lg">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="px-8 py-20 text-center">
                                <i class="fa-solid fa-briefcase text-5xl text-gray-100 mb-4 block"></i>
                                <p class="text-xs font-bold text-gray-300 italic">Aucune offre publiée par cette entreprise</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Stats / Activity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6">Performance</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-400">Total Candidatures</span>
                                <span class="text-sm font-black text-[#204263]">{{ $entreprise->getTotalCandidaturesCount() }}</span>
                            </div>
                            <div class="h-1.5 bg-gray-50 rounded-full overflow-hidden">
                                <div class="bg-acpe-blue h-full" style="width: 75%"></div>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-xs font-bold text-gray-400">Taux de conversion</span>
                                <span class="text-sm font-black text-emerald-500">12.5%</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#204263] rounded-3xl shadow-xl border border-[#204263] p-8 text-white">
                        <h3 class="text-sm font-black uppercase tracking-widest mb-6 opacity-60">Récapitulatif</h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-2xl font-black">{{ $entreprise->getActiveOffersCount() }}</p>
                                <p class="text-[9px] font-bold uppercase tracking-widest opacity-60">Offres actives</p>
                            </div>
                            <div>
                                <p class="text-2xl font-black">{{ $entreprise->recruteurs->count() }}</p>
                                <p class="text-[9px] font-bold uppercase tracking-widest opacity-60">Collaborateurs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
