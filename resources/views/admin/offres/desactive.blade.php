<x-admin-layout>
    @section('title', 'Désactiver l\'Offre')

    <div class="max-w-2xl mx-auto py-12 animate-slide-up">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-12 text-center">
                <div class="h-24 w-24 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-8">
                    <i class="fa-solid fa-eye-slash text-4xl"></i>
                </div>
                
                <h1 class="text-3xl font-black text-[#204263] mb-4">Désactiver l'offre ?</h1>
                <p class="text-gray-400 text-lg mb-10">L'offre <span class="text-[#204263] font-bold">{{ $offre->titre }}</span> ne sera plus visible par les candidats mais restera dans la base de données.</p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('admin.offres') }}" class="w-full sm:w-auto px-10 py-4 bg-gray-50 text-gray-500 text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-gray-100 transition-all text-center">
                        Annuler
                    </a>
                    <form action="{{ route('admin.offres.toggle', $offre) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-amber-500 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all">
                            Désactiver l'offre
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
