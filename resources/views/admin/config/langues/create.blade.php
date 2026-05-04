<x-admin-layout>
    @section('title', 'Ajouter une Langue')

    <div class="max-w-2xl mx-auto animate-slide-up">
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('admin.config.langues') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Nouvelle Langue</h1>
                <p class="text-gray-400 text-sm">Gérez le dictionnaire des langues.</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.config.langues.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Nom de la langue</label>
                        <input type="text" name="libelle" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-pink-500/20" placeholder="Ex: Chinois (Mandarin)">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Code ISO (facultatif)</label>
                        <input type="text" name="code_iso" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-pink-500/20" placeholder="ZH">
                    </div>
                </div>

                <div class="mt-8 flex space-x-3">
                    <a href="{{ route('admin.config.langues') }}" class="flex-1 px-6 py-3 bg-gray-50 text-gray-400 text-center text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all">Annuler</a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-pink-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg shadow-pink-500/20 hover:scale-105 transition-all">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
