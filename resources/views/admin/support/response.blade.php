<x-admin-layout>
    @section('title', 'Répondre au Ticket')

    <div class="max-w-4xl mx-auto space-y-6 animate-slide-up">
        <!-- Breadcrumbs -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('admin.support') }}" class="text-gray-400 hover:text-acpe-blue transition-colors">Support</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-[#204263] font-bold">Répondre au ticket</span>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                <h1 class="text-xl font-bold text-[#204263]">Formulaire de réponse</h1>
                <p class="text-gray-400 text-xs mt-1">Envoyez une réponse officielle au demandeur.</p>
            </div>

            <form action="#" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="space-y-4">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Utiliser un modèle (Macro)</label>
                    <select class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                        <option value="">Aucun modèle</option>
                        <option value="welcome">Message de bienvenue</option>
                        <option value="maintenance">Maintenance en cours</option>
                        <option value="resolved">Problème résolu</option>
                    </select>
                </div>

                <div class="space-y-4">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Votre message</label>
                    <textarea rows="12" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-acpe-blue/20 placeholder:text-gray-300" placeholder="Rédigez votre réponse détaillée ici..."></textarea>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                    <div class="flex items-center space-x-4">
                        <button type="button" class="flex items-center space-x-2 text-gray-400 hover:text-acpe-blue transition-colors">
                            <i class="fa-solid fa-paperclip"></i>
                            <span class="text-xs font-bold">Joindre un fichier</span>
                        </button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button type="button" class="px-6 py-3 text-gray-400 hover:text-gray-600 text-sm font-bold transition-all">
                            Enregistrer en brouillon
                        </button>
                        <button type="submit" class="px-10 py-3 bg-acpe-blue text-white text-sm font-black uppercase tracking-widest rounded-xl hover:bg-acpe-dark-blue shadow-lg shadow-acpe-blue/10 transition-all">
                            Envoyer la réponse
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
