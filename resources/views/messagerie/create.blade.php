<x-dynamic-component :component="Auth::user()->hasRole('demandeur') ? 'candidat-layout' : 'admin-layout'">
    @section('title', 'Nouveau message')

    <div class="max-w-3xl mx-auto animate-slide-up">
        <div class="mb-8">
            <a href="{{ route('messagerie.index') }}" class="flex items-center space-x-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-acpe-blue transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Retour à la messagerie</span>
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex items-center space-x-3">
                <div class="h-8 w-8 bg-orange-50 text-acpe-orange rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-pen-nib text-xs"></i>
                </div>
                <h1 class="text-sm font-black text-[#204263] uppercase tracking-widest">Nouveau message</h1>
            </div>

            <form action="{{ route('messagerie.store') }}" method="POST" class="p-10 space-y-8">
                @csrf

                @if($receiver)
                    <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">
                    <div class="p-4 bg-gray-50 rounded-2xl flex items-center space-x-4 border border-gray-100">
                        <div class="h-10 w-10 rounded-full bg-white overflow-hidden border border-gray-100 shadow-sm">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($receiver->nom_complet) }}&background=random" alt="">
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Destinataire</p>
                            <h4 class="text-xs font-black text-[#204263]">{{ $receiver->nom_complet }}</h4>
                        </div>
                    </div>
                @else
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Destinataire (ID Utilisateur)</label>
                        <input type="number" name="receiver_id" placeholder="Entrez l'ID du destinataire" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner" required>
                    </div>
                @endif

                @if($offreId)
                    <input type="hidden" name="id_offre" value="{{ $offreId }}">
                @endif

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Objet du message</label>
                    <input type="text" name="objet" placeholder="L'objet de votre message" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Votre message</label>
                    <textarea name="contenu" rows="8" placeholder="Écrivez votre message ici..." class="w-full bg-gray-50 border-none rounded-2xl text-xs font-bold text-[#204263] p-6 focus:ring-acpe-orange shadow-inner" required></textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-10 py-4 bg-acpe-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all">
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Envoyer le message
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dynamic-component>
