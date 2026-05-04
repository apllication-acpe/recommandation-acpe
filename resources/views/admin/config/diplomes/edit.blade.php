<x-admin-layout>
    @section('title', 'Modifier le Diplôme')

    <div class="max-w-2xl mx-auto animate-slide-up">
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('admin.config.diplomes') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Modifier : {{ $diplome->libelle }}</h1>
                <p class="text-gray-400 text-sm">Mettez à jour les caractéristiques du diplôme.</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.config.diplomes.update', $diplome) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Libellé</label>
                        <input type="text" name="libelle" value="{{ $diplome->libelle }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Niveau</label>
                            <input type="text" name="niveau" value="{{ $diplome->niveau }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Spécialité</label>
                            <input type="text" name="specialite" value="{{ $diplome->specialite }}" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex space-x-3">
                    <a href="{{ route('admin.config.diplomes') }}" class="flex-1 px-6 py-3 bg-gray-50 text-gray-400 text-center text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all">Annuler</a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
