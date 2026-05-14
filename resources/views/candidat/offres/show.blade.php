<x-candidat-layout>
    <div class="space-y-8 animate-slide-up">
        
        <!-- Job Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="h-32 bg-[#204263] relative">
                <div class="absolute -bottom-12 left-8 h-24 w-24 rounded-3xl bg-white shadow-xl border-4 border-white flex items-center justify-center text-[#204263]">
                    <i class="fa-solid fa-building text-4xl"></i>
                </div>
            </div>
            <div class="pt-16 pb-8 px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-black text-[#204263]">{{ $offre->titre }}</h1>
                        <div class="mt-2 flex flex-wrap gap-4 items-center">
                            <p class="text-sm font-bold text-gray-500 flex items-center">
                                <i class="fa-solid fa-building mr-2 text-acpe-orange"></i>
                                {{ $offre->entreprise->raison_sociale }}
                            </p>
                            <p class="text-sm font-bold text-gray-500 flex items-center">
                                <i class="fa-solid fa-location-dot mr-2 text-acpe-orange"></i>
                                {{ $offre->localisations->first()->ville ?? ($offre->departement ?? 'Congo') }}
                            </p>
                            @if($offre->source === 'acpe_scraping')
                                <div class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg border border-blue-100 flex items-center gap-2">
                                    <i class="fa-solid fa-spider text-xs"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Source: ACPE.CG</span>
                                </div>
                            @endif
                            <p class="text-sm font-bold text-gray-500 flex items-center">
                                <i class="fa-solid fa-calendar-day mr-2 text-acpe-orange"></i>
                                Publié le {{ $offre->date_publication->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row items-center gap-4">
                        <button onclick="toggleFavori({{ $offre->id_offre }}, this)" 
                            class="h-14 w-14 rounded-2xl flex items-center justify-center transition-all border-2 
                            {{ $isFavori ? 'bg-red-50 border-red-100 text-red-400' : 'bg-gray-50 border-gray-100 text-gray-300 hover:text-red-400 hover:bg-red-50' }}">
                            <i class="fa-solid fa-heart text-xl"></i>
                        </button>

                        @if($dejaPostule)
                            <div class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-sm cursor-not-allowed flex items-center">
                                <i class="fa-solid fa-check-circle mr-2 text-lg"></i>
                                Déjà postulé
                            </div>
                        @elseif($offre->source === 'acpe_scraping')
                            <a href="{{ $offre->url_source }}" target="_blank" class="px-12 py-4 bg-blue-600 text-white rounded-2xl font-black text-sm shadow-lg shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all flex items-center">
                                <i class="fa-solid fa-arrow-up-right-from-square mr-2 text-lg"></i>
                                Postuler sur ACPE.CG
                            </a>
                        @else
                            <form action="{{ route('candidat.offres.postuler', $offre->id_offre) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-12 py-4 bg-acpe-orange text-white rounded-2xl font-black text-sm shadow-lg shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all flex items-center">
                                    <i class="fa-solid fa-paper-plane mr-2 text-lg"></i>
                                    Postuler maintenant
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            function toggleFavori(offreId, btn) {
                fetch(`/candidat/favoris/${offreId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.isFavori) {
                            btn.classList.remove('bg-gray-50', 'border-gray-100', 'text-gray-300');
                            btn.classList.add('bg-red-50', 'border-red-100', 'text-red-400');
                        } else {
                            btn.classList.add('bg-gray-50', 'border-gray-100', 'text-gray-300');
                            btn.classList.remove('bg-red-50', 'border-red-100', 'text-red-400');
                        }

                        // Notification Toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oups...',
                        text: 'Une erreur est survenue lors de la mise à jour des favoris.'
                    });
                });
            }
        </script>
        @endpush

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Job Details -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-black text-[#204263] uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-1 bg-acpe-orange rounded-full mr-3"></span>
                        Description du poste
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-600 font-medium leading-relaxed">
                        {!! nl2br(e($offre->description)) !!}
                    </div>
                </div>

                @if($offre->mission)
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-black text-[#204263] uppercase tracking-widest mb-6 flex items-center">
                            <span class="w-8 h-1 bg-acpe-orange rounded-full mr-3"></span>
                            Missions
                        </h2>
                        <div class="prose prose-sm max-w-none text-gray-600 font-medium leading-relaxed">
                            {!! nl2br(e($offre->mission)) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="bg-[#204263] p-8 rounded-3xl shadow-xl text-white">
                    <h2 class="text-sm font-black uppercase tracking-widest mb-6 opacity-60">Informations clés</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-40">Type de contrat</p>
                                <p class="text-sm font-bold">{{ $offre->typeContrat->libelle ?? 'Non spécifié' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-40">Salaire</p>
                                <p class="text-sm font-bold">{{ $offre->salaire_formate }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-40">Date d'expiration</p>
                                <p class="text-sm font-bold">{{ $offre->date_expiration->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logistique check -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6">Pré-requis logistiques</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500">Débutant accepté</span>
                            <i class="fa-solid {{ $offre->debutant_accepte ? 'fa-check-circle text-emerald-500' : 'fa-times-circle text-red-400' }}"></i>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500">Permis B requis</span>
                            <i class="fa-solid {{ $offre->permis_b_requis ? 'fa-check-circle text-emerald-500' : 'fa-times-circle text-red-400' }}"></i>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500">Véhicule requis</span>
                            <i class="fa-solid {{ $offre->vehicule_requis ? 'fa-check-circle text-emerald-500' : 'fa-times-circle text-red-400' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-candidat-layout>
