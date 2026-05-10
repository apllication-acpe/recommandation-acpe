<x-candidat-layout>
    @section('title', 'Mes Favoris')

    <div class="space-y-6 animate-slide-up">
        <h1 class="text-2xl font-black text-[#204263]">Mes Favoris</h1>
        <p class="text-xs font-medium text-gray-400 mt-1 uppercase tracking-widest">Retrouvez ici toutes les offres que vous avez mises de côté.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            @forelse($favoris as $offre)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all group relative" id="offre-{{ $offre->id_offre }}">
                    <button onclick="toggleFavori({{ $offre->id_offre }})" class="absolute top-6 right-6 text-acpe-orange transition-transform active:scale-125">
                        <i class="fa-solid fa-heart text-lg"></i>
                    </button>
                    
                    <div class="h-12 w-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 border border-gray-100 mb-6">
                        @if($offre->entreprise && $offre->entreprise->logo)
                            <img src="{{ asset('storage/' . $offre->entreprise->logo) }}" alt="Logo" class="h-full w-full object-contain rounded-2xl">
                        @else
                            <i class="fa-solid fa-building text-xl"></i>
                        @endif
                    </div>
                    
                    <h3 class="text-sm font-black text-[#204263] mb-1">{{ $offre->titre }}</h3>
                    <p class="text-[10px] font-bold text-acpe-light-blue uppercase tracking-widest mb-4">
                        {{ $offre->entreprise->raison_sociale ?? 'Entreprise' }}
                    </p>
                    
                    <div class="flex items-center space-x-3 mb-6">
                        @if($offre->typeContrat)
                            <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100">{{ $offre->typeContrat->libelle }}</span>
                        @endif
                        @if($offre->localisations->count() > 0)
                            <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100">{{ $offre->localisations->first()->ville }}</span>
                        @endif
                    </div>

                    <a href="{{ route('candidat.offres.show', $offre->id_offre) }}" class="block w-full py-3 bg-[#204263] text-white text-center rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-acpe-dark-blue transition-all">
                        Postuler maintenant
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                        <i class="fa-solid fa-heart-crack text-3xl"></i>
                    </div>
                    <p class="text-gray-400 text-xs font-black uppercase">Vous n'avez aucune offre en favori pour le moment.</p>
                    <a href="{{ route('candidat.offres.index') }}" class="inline-block mt-6 text-[10px] font-black text-acpe-orange uppercase border-b-2 border-acpe-orange pb-1">Découvrir des offres</a>
                </div>
            @endforelse
        </div>

        @push('scripts')
        <script>
            function toggleFavori(offreId) {
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
                        // Notification Toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });

                        if (!data.isFavori) {
                            const card = document.getElementById(`offre-${offreId}`);
                            if (card) {
                                card.style.opacity = '0';
                                card.style.transform = 'scale(0.9)';
                                setTimeout(() => {
                                    card.remove();
                                    if (document.querySelectorAll('[id^="offre-"]').length === 0) {
                                        window.location.reload();
                                    }
                                }, 300);
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oups...',
                        text: 'Une erreur est survenue.'
                    });
                });
            }
        </script>
        @endpush
    </div>
</x-candidat-layout>
