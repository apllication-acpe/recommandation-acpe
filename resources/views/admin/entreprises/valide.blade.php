<x-admin-layout>
    @section('title', 'Valider l\'Entreprise')

    <div class="max-w-2xl mx-auto py-12 animate-slide-up">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-12 text-center">
                <div class="h-24 w-24 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-8">
                    <i class="fa-solid fa-check-double text-4xl"></i>
                </div>
                
                <h1 class="text-3xl font-black text-[#204263] mb-4">Valider l'entreprise ?</h1>
                <p class="text-gray-400 text-lg mb-10">En validant <span class="text-[#204263] font-bold">{{ $entreprise->raison_sociale }}</span>, vous autorisez la publication de ses offres sur la plateforme.</p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('admin.entreprises') }}" class="w-full sm:w-auto px-10 py-4 bg-gray-50 text-gray-500 text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-gray-100 transition-all text-center">
                        Retour
                    </a>
                    <form action="{{ route('admin.entreprises.verify', $entreprise) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-emerald-500 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all">
                            Confirmer la validation
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="px-12 py-6 bg-emerald-50/50 border-t border-emerald-100 flex items-center justify-center space-x-3 text-emerald-600">
                <i class="fa-solid fa-shield-check"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">L'entreprise sera marquée comme vérifiée</span>
            </div>
        </div>
    </div>
</x-admin-layout>
