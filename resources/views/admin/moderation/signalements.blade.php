<x-admin-layout>
    @section('title', 'Gestion des Signalements')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Modération & Signalements</h1>
                <p class="text-gray-400 text-sm">Gérez les alertes et les contenus signalés par la communauté.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-6 py-2.5 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-500/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-shield-halved mr-2"></i> Historique des sanctions
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <button class="px-4 py-2 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-xl">En attente (3)</button>
            <button class="px-4 py-2 bg-gray-50 text-gray-400 text-[10px] font-black uppercase rounded-xl">Traités</button>
            <button class="px-4 py-2 bg-gray-50 text-gray-400 text-[10px] font-black uppercase rounded-xl">Ignorés</button>
        </div>

        <!-- Signalements List -->
        <div class="space-y-4">
            <!-- Item 1 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:border-red-100 transition-all">
                <div class="flex items-center space-x-6">
                    <div class="h-12 w-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[8px] font-black uppercase rounded">Offre</span>
                            <h3 class="text-sm font-black text-[#204263] uppercase tracking-tight">Vendeur Polyvalent</h3>
                        </div>
                        <p class="text-[10px] text-gray-400">Signalé par <strong>User #56</strong> pour <span class="text-red-500 font-bold">Contenu inapproprié</span></p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="h-10 px-6 bg-white border border-gray-100 text-[#204263] text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 shadow-sm transition-all">Consulter</button>
                    <button class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    <button class="h-10 w-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                        <i class="fa-solid fa-ban"></i>
                    </button>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:border-red-100 transition-all opacity-80">
                <div class="flex items-center space-x-6">
                    <div class="h-12 w-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-xmark text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[8px] font-black uppercase rounded">Candidat</span>
                            <h3 class="text-sm font-black text-[#204263] uppercase tracking-tight">Koffi Kouamé</h3>
                        </div>
                        <p class="text-[10px] text-gray-400">Signalé par <strong>Recruteur #12</strong> pour <span class="text-red-500 font-bold">Faux profil / Spam</span></p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="h-10 px-6 bg-white border border-gray-100 text-[#204263] text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 shadow-sm transition-all">Consulter</button>
                    <button class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    <button class="h-10 w-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                        <i class="fa-solid fa-ban"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
