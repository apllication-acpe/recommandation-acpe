<x-admin-layout>
    @section('title', 'Modifier l\'Utilisateur')

    <div class="max-w-4xl mx-auto space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('admin.utilisateurs') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Modifier l'utilisateur</h1>
                <p class="text-gray-400 text-sm">Modifiez le profil, les droits et la photo de l'utilisateur.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-[#204263]">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h2>
                    <p class="text-gray-400 text-xs mt-1">UID: #{{ $utilisateur->id }}</p>
                </div>
                <div class="h-12 w-12 rounded-full overflow-hidden border border-gray-100 shadow-sm flex items-center justify-center bg-gray-100 text-gray-400 font-bold text-sm">
                    @if($utilisateur->avatar)
                        <img src="{{ asset('storage/' . $utilisateur->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                    @else
                        {{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.utilisateurs.update', $utilisateur) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Prénom</label>
                        <input type="text" name="prenom" value="{{ old('prenom', $utilisateur->prenom) }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom', $utilisateur->nom) }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $utilisateur->email) }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Rôle</label>
                        <select name="role" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $utilisateur->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Photo (Avatar)</label>
                        <input type="file" name="avatar" accept="image/*" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="space-y-2 md:col-span-2 mt-4 p-5 bg-orange-50 rounded-2xl border border-orange-100">
                        <h3 class="text-xs font-black text-orange-800 uppercase tracking-widest mb-4"><i class="fa-solid fa-lock mr-2"></i>Changement de mot de passe</h3>
                        <p class="text-xs text-orange-600 mb-4">Laissez ces champs vides si vous ne souhaitez pas modifier le mot de passe actuel.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="password" name="password" class="w-full px-4 py-3 bg-white border border-orange-100 rounded-xl text-sm focus:ring-2 focus:ring-acpe-orange/20" placeholder="Nouveau mot de passe">
                            </div>
                            <div>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-white border border-orange-100 rounded-xl text-sm focus:ring-2 focus:ring-acpe-orange/20" placeholder="Confirmer le mot de passe">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end border-t border-gray-50 space-x-3">
                    <a href="{{ route('admin.utilisateurs') }}" class="px-6 py-3 bg-gray-100 text-gray-500 text-sm font-black uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-all">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-3 bg-acpe-blue text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg shadow-acpe-blue/20 hover:scale-105 transition-all">
                        <i class="fa-solid fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
