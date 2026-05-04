<x-dynamic-component :component="Auth::user()->hasRole('demandeur') ? 'candidat-layout' : 'admin-layout'">
    @section('title', 'Lecture du message')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('messagerie.index') }}" class="flex items-center space-x-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-acpe-blue transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Retour à la liste</span>
            </a>
            @if($message->offre)
                <div class="px-4 py-1.5 bg-blue-50 text-acpe-blue rounded-full text-[8px] font-black uppercase tracking-widest border border-blue-100">
                    Offre: {{ $message->offre->titre }}
                </div>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-gray-100 overflow-hidden border-2 border-white shadow-sm">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->nom_complet) }}&background=random" alt="">
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-[#204263] uppercase tracking-widest">{{ $message->sender->nom_complet }}</h2>
                        <p class="text-[10px] font-bold text-gray-400 mt-1">{{ $message->created_at->format('d M Y à H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <h1 class="text-lg font-black text-[#204263]">{{ $message->objet }}</h1>
                </div>
            </div>

            <div class="p-10 text-xs font-medium text-[#204263] leading-relaxed whitespace-pre-wrap">
                {{ $message->contenu }}
            </div>

            <div class="p-8 bg-gray-50/50 border-t border-gray-50">
                <h3 class="text-[10px] font-black text-[#204263] uppercase tracking-widest mb-6">Répondre</h3>
                <form action="{{ route('messagerie.reply', $message) }}" method="POST">
                    @csrf
                    <div class="relative">
                        <textarea name="contenu" rows="4" placeholder="Écrivez votre réponse ici..." class="w-full bg-white border-gray-100 rounded-2xl text-xs font-bold text-[#204263] p-6 focus:ring-acpe-orange shadow-sm mb-4" required></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-acpe-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all">
                                <i class="fa-solid fa-paper-plane mr-2"></i>
                                Envoyer la réponse
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dynamic-component>
