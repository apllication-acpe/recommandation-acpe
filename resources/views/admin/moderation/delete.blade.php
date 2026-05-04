<x-admin-layout>
    @section('title', 'Confirmer la suppression')

    <div class="max-w-2xl mx-auto py-12 animate-slide-up">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-12 text-center">
                <div class="h-24 w-24 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-8 animate-pulse">
                    <i class="fa-solid fa-trash-can text-4xl"></i>
                </div>
                
                <h1 class="text-3xl font-black text-[#204263] mb-4">Confirmer la suppression ?</h1>
                <p class="text-gray-400 text-lg mb-10">Cette action est <span class="text-red-500 font-bold">irréversible</span>. Toutes les données associées à cet élément seront définitivement effacées du système.</p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button onclick="window.history.back()" class="w-full sm:w-auto px-10 py-4 bg-gray-50 text-gray-500 text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-gray-100 transition-all">
                        Annuler l'action
                    </button>
                    <form action="#" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-red-500 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-red-600 shadow-lg shadow-red-200 transition-all">
                            Oui, supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="px-12 py-6 bg-red-50/50 border-t border-red-100 flex items-center justify-center space-x-3">
                <i class="fa-solid fa-shield-halved text-red-400"></i>
                <span class="text-[10px] font-black text-red-400 uppercase tracking-widest">Une entrée sera créée dans l'historique d'audit</span>
            </div>
        </div>
    </div>
</x-admin-layout>
