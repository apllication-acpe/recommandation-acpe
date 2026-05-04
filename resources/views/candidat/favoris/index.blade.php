<x-candidat-layout>
    @section('title', 'Mes Favoris')

    <div class="space-y-6 animate-slide-up">
        <h1 class="text-2xl font-black text-[#204263]">Mes Favoris</h1>
        <p class="text-xs font-medium text-gray-400 mt-1 uppercase tracking-widest">Retrouvez ici toutes les offres que vous avez mises de côté.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            @php
                $favs = [
                    ['title' => 'Product Designer', 'company' => 'Creative Agency', 'loc' => 'Dakar', 'type' => 'CDI'],
                    ['title' => 'Marketing Manager', 'company' => 'Startup Hub', 'loc' => 'Thiès', 'type' => 'Freelance'],
                ];
            @endphp

            @foreach($favs as $fav)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all group relative">
                    <button class="absolute top-6 right-6 text-acpe-orange">
                        <i class="fa-solid fa-heart text-lg"></i>
                    </button>
                    <div class="h-12 w-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 border border-gray-100 mb-6">
                        <i class="fa-solid fa-building text-xl"></i>
                    </div>
                    <h3 class="text-sm font-black text-[#204263] mb-1">{{ $fav['title'] }}</h3>
                    <p class="text-[10px] font-bold text-acpe-light-blue uppercase tracking-widest mb-4">{{ $fav['company'] }}</p>
                    
                    <div class="flex items-center space-x-3 mb-6">
                        <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100">{{ $fav['type'] }}</span>
                        <span class="px-2 py-1 bg-gray-50 text-gray-400 text-[9px] font-black rounded-lg uppercase border border-gray-100">{{ $fav['loc'] }}</span>
                    </div>

                    <button class="w-full py-3 bg-[#204263] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-acpe-dark-blue transition-all">
                        Postuler maintenant
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</x-candidat-layout>
