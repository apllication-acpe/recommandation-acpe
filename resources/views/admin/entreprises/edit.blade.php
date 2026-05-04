<x-admin-layout>
    @section('title', 'Modifier l\'Entreprise')

    <div class="max-w-4xl mx-auto space-y-6 animate-slide-up">
        <!-- Breadcrumbs -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('admin.entreprises') }}" class="text-gray-400 hover:text-acpe-blue transition-colors">Entreprises</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-[#204263] font-bold">Modifier l'entreprise</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-[#204263]">Informations de l'entreprise</h1>
                    <p class="text-gray-400 text-xs mt-1">Gérez les détails officiels et les paramètres de visibilité.</p>
                </div>
                @if($entreprise->logo_path)
                    <img src="{{ $entreprise->logo_url }}" class="h-12 w-12 rounded-xl object-cover shadow-sm border border-gray-100">
                @else
                    <div class="h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center text-[#204263] font-black text-sm">
                        {{ substr($entreprise->raison_sociale, 0, 2) }}
                    </div>
                @endif
            </div>

            <form action="{{ route('admin.entreprises.show', $entreprise) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Raison Sociale -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Raison Sociale</label>
                        <input type="text" name="raison_sociale" value="{{ old('raison_sociale', $entreprise->raison_sociale) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>

                    <!-- Email Contact -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Email de contact</label>
                        <input type="email" name="email_contact" value="{{ old('email_contact', $entreprise->email_contact) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>

                    <!-- Téléphone -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $entreprise->telephone) }}"
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>

                    <!-- Secteur d'activité -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Secteur d'activité</label>
                        <select name="secteur_id" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                            @foreach(\App\Models\SecteurActivite::all() as $secteur)
                                <option value="{{ $secteur->id }}" {{ $entreprise->secteur_id == $secteur->id ? 'selected' : '' }}>
                                    {{ $secteur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Taille -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Taille de l'entreprise</label>
                        <select name="taille" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                            <option value="TPE" {{ $entreprise->taille == 'TPE' ? 'selected' : '' }}>TPE (1-10)</option>
                            <option value="PME" {{ $entreprise->taille == 'PME' ? 'selected' : '' }}>PME (11-250)</option>
                            <option value="GE" {{ $entreprise->taille == 'GE' ? 'selected' : '' }}>GE (+250)</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">{{ old('description', $entreprise->description) }}</textarea>
                    </div>

                    <!-- Statut de vérification -->
                    <div class="md:col-span-2 p-4 bg-blue-50 rounded-2xl border border-blue-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-[#204263]">Statut de vérification</p>
                            <p class="text-[10px] text-gray-500">L'entreprise a accès à toutes les fonctionnalités si elle est vérifiée.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="verifiee" value="1" class="sr-only peer" {{ $entreprise->verifiee ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-acpe-blue"></div>
                        </label>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-50">
                    <a href="{{ route('admin.entreprises') }}" class="px-6 py-3 text-gray-400 hover:text-gray-600 text-sm font-bold transition-all">
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
