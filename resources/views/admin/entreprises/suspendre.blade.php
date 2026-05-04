<x-admin-layout>
    @section('title', 'Suspendre l\'Entreprise')

    <div class="max-w-2xl mx-auto py-12 animate-slide-up">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-12 text-center">
                <div class="h-24 w-24 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-8">
                    <i class="fa-solid fa-pause text-4xl"></i>
                </div>
                
                <h1 class="text-3xl font-black text-[#204263] mb-4">Suspendre l'entreprise ?</h1>
                <p class="text-gray-400 text-lg mb-10">La suspension de <span class="text-[#204263] font-bold">{{ $entreprise->raison_sociale }}</span> rendra ses offres invisibles et bloquera l'accès à ses recruteurs.</p>
                
                <form action="{{ route('admin.entreprises.show', $entreprise) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="text-left space-y-2 max-w-sm mx-auto">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Motif de la suspension</label>
                        <select name="raison" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                            <option value="fraude">Suspicion de fraude</option>
                            <option value="non_respect_charte">Non-respect de la charte</option>
                            <option value="paiement_echoue">Défaut de paiement</option>
                            <option value="autre">Autre raison...</option>
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <a href="{{ route('admin.entreprises') }}" class="w-full sm:w-auto px-10 py-4 bg-gray-50 text-gray-500 text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-gray-100 transition-all text-center">
                            Annuler
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-amber-500 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all">
                            Suspendre l'accès
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
