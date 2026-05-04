<x-admin-layout>
    @section('title', 'Nouvel Utilisateur')

    <div class="max-w-4xl mx-auto space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('admin.utilisateurs') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Créer un utilisateur système</h1>
                <p class="text-gray-400 text-sm">Ajoutez manuellement un administrateur, un recruteur ou un demandeur.</p>
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
            <form action="{{ route('admin.utilisateurs.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Prénom</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Rôle</label>
                        <select name="role" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                            <option value="">-- Sélectionner un rôle --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Photo (Avatar)</label>
                        <input type="file" name="avatar" accept="image/*" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="space-y-2 md:col-span-2 mt-4 p-5 bg-blue-50 rounded-2xl border border-blue-100">
                        <h3 class="text-xs font-black text-blue-800 uppercase tracking-widest mb-4"><i class="fa-solid fa-key mr-2"></i>Mot de passe</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="password" name="password" required class="w-full px-4 py-3 bg-white border border-blue-100 rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20" placeholder="Mot de passe">
                            </div>
                            <div>
                                <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-white border border-blue-100 rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20" placeholder="Confirmer le mot de passe">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end border-t border-gray-50 space-x-3">
                    <a href="{{ route('admin.utilisateurs') }}" class="px-6 py-3 bg-gray-100 text-gray-500 text-sm font-black uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-all">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-3 bg-acpe-blue text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg shadow-acpe-blue/20 hover:scale-105 transition-all">
                        <i class="fa-solid fa-plus mr-2"></i> Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
