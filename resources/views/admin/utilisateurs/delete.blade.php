<x-admin-layout>
    @section('title', 'Supprimer l\'utilisateur')

    <div class="max-w-2xl mx-auto py-12 animate-slide-up">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-12 text-center">
                <div class="h-24 w-24 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-8">
                    <i class="fa-solid fa-user-xmark text-4xl"></i>
                </div>
                
                <h1 class="text-3xl font-black text-[#204263] mb-4">Supprimer l'utilisateur ?</h1>
                <p class="text-gray-400 text-lg mb-10">Vous êtes sur le point de supprimer le compte de <span class="text-[#204263] font-bold">{{ $user->prenom }} {{ $user->nom }}</span>. Cette action supprimera également toutes ses données personnelles.</p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('admin.candidats') }}" class="w-full sm:w-auto px-10 py-4 bg-gray-50 text-gray-500 text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-gray-100 transition-all text-center">
                        Annuler
                    </a>
                    <form action="{{ route('admin.candidats.destroy', $user->demandeur ?? $user->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-red-500 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-red-600 shadow-lg shadow-red-200 transition-all">
                            Confirmer la suppression
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
