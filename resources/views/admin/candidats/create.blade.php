<x-admin-layout>
    @section('title', 'Nouveau Demandeur')

    <div class="max-w-4xl mx-auto space-y-6 animate-slide-up">
        <!-- Breadcrumbs / Back Link -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('admin.candidats') }}" class="text-gray-400 hover:text-acpe-blue transition-colors">Demandeurs</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-[#204263] font-bold">Nouveau Demandeur</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                <h1 class="text-xl font-bold text-[#204263]">Ajouter un nouveau demandeur</h1>
                <p class="text-gray-400 text-xs mt-1">Créez un compte utilisateur et un profil de demandeur d'emploi.</p>
            </div>

            <form action="{{ route('admin.candidats.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Prénom -->
                    <div class="space-y-2">
                        <label for="prenom" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Prénom</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
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
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
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
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20 @error('email') ring-2 ring-red-500/20 @enderror"
                                   placeholder="exemple@email.com">
                        </div>
                        @error('email') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Mot de passe</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="password" name="password" id="password" required
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
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-50">
                    <a href="{{ route('admin.candidats') }}" class="px-6 py-3 text-gray-400 hover:text-gray-600 text-sm font-bold transition-all">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-3 bg-acpe-blue hover:bg-acpe-dark-blue text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-acpe-blue/10">
                        Créer le demandeur
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
