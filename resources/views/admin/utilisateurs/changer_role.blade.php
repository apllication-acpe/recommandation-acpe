<x-admin-layout>
    @section('title', 'Changer le rôle')

    <div class="max-w-2xl mx-auto py-12 animate-slide-up">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-12">
                <div class="text-center mb-10">
                    <div class="h-20 w-20 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-6 shadow-sm border-2 border-white">
                        <i class="fa-solid fa-user-shield text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-black text-[#204263]">Modifier le rôle utilisateur</h1>
                    <p class="text-gray-400 text-sm mt-2">Utilisateur : <span class="font-bold text-acpe-blue">{{ $user->prenom }} {{ $user->nom }}</span></p>
                </div>
                
                <form action="#" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-4">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Sélectionnez le nouveau rôle</label>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach(['admin' => 'Administrateur', 'recruteur' => 'Recruteur / Entreprise', 'candidat' => 'Candidat (Demandeur)'] as $key => $label)
                            <label class="relative flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20 has-[:checked]:bg-white">
                                <input type="radio" name="role" value="{{ $key }}" class="sr-only" {{ $user->hasRole($key) ? 'checked' : '' }}>
                                <div class="h-8 w-8 rounded-lg bg-white flex items-center justify-center mr-4 shadow-sm">
                                    <i class="fa-solid fa-{{ $key === 'admin' ? 'crown' : ($key === 'recruteur' ? 'building' : 'user') }} text-xs text-gray-400 group-has-[:checked]:text-acpe-blue"></i>
                                </div>
                                <span class="text-sm font-bold text-[#204263]">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col sm:flex-row items-center justify-center gap-4 border-t border-gray-50">
                        <a href="{{ route('admin.candidats') }}" class="w-full sm:w-auto px-8 py-3 text-gray-400 hover:text-gray-600 text-sm font-bold transition-all text-center">
                            Annuler
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-acpe-blue text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-acpe-dark-blue shadow-lg shadow-acpe-blue/10 transition-all">
                            Mettre à jour le rôle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
