<x-admin-layout>
    @section('title', 'Modifier le Candidat')

    <div class="max-w-4xl mx-auto space-y-6 animate-slide-up">
        <!-- Breadcrumbs / Back Link -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('admin.candidats') }}" class="text-gray-400 hover:text-acpe-blue transition-colors">Candidats</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-[#204263] font-bold">Modifier #{{ $demandeur->id }}</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-[#204263]">Modifier le profil candidat</h1>
                    <p class="text-gray-400 text-xs mt-1">Mettez à jour les informations de {{ $user->prenom }} {{ $user->nom }}.</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center font-black text-sm border-2 border-white shadow-sm">
                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                </div>
            </div>

            <form action="{{ route('admin.candidats.update', $demandeur) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Prénom -->
                    <div class="space-y-2">
                        <label for="prenom" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Prénom</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $user->prenom) }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 @error('prenom') ring-2 ring-red-500/20 @enderror"
                                   placeholder="Ex: Jean">
                        </div>
                        @error('prenom') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nom -->
                    <div class="space-y-2">
                        <label for="nom" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Nom</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $user->nom) }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 @error('nom') ring-2 ring-red-500/20 @enderror"
                                   placeholder="Ex: DUPONT">
                        </div>
                        @error('nom') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="email" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Adresse Email</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 @error('email') ring-2 ring-red-500/20 @enderror"
                                   placeholder="exemple@email.com">
                        </div>
                        @error('email') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 pt-4">
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Sécurité</p>
                            <p class="text-xs text-amber-800">Laissez les champs ci-dessous vides si vous ne souhaitez pas modifier le mot de passe.</p>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Nouveau mot de passe</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="password" name="password" id="password"
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 @error('password') ring-2 ring-red-500/20 @enderror"
                                   placeholder="••••••••">
                        </div>
                        @error('password') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Confirmer le mot de passe</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock-open absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-50">
                    <a href="{{ route('admin.candidats') }}" class="px-6 py-3 text-gray-400 hover:text-gray-600 text-sm font-bold transition-all">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#204263] hover:bg-acpe-dark-blue text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-acpe-blue/10">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
