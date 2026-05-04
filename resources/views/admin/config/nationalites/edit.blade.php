<x-admin-layout>
    @section('title', 'Modifier le Pays')

    <div class="max-w-2xl mx-auto animate-slide-up">
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('admin.config.nationalites') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Modifier : {{ $nationalite->libelle }}</h1>
                <p class="text-gray-400 text-sm">Mettez à jour les codes du pays.</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.config.nationalites.update', $nationalite) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Libellé</label>
                        <input type="text" name="libelle" value="{{ $nationalite->libelle }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-purple-500/20">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Code ISO</label>
                        <input type="text" name="code_iso" value="{{ $nationalite->code_iso }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-purple-500/20">
                    </div>
                </div>

                <div class="mt-8 flex space-x-3">
                    <a href="{{ route('admin.config.nationalites') }}" class="flex-1 px-6 py-3 bg-gray-50 text-gray-400 text-center text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all">Annuler</a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg shadow-purple-500/20 hover:scale-105 transition-all">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
