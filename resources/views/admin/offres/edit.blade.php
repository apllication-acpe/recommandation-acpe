<x-admin-layout>
    @section('title', 'Modifier l\'Offre')

    <div class="max-w-4xl mx-auto space-y-6 animate-slide-up">
        <!-- Breadcrumbs -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('admin.offres') }}" class="text-gray-400 hover:text-acpe-blue transition-colors">Offres</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-[#204263] font-bold">Modifier l'offre</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                <h1 class="text-xl font-bold text-[#204263]">Modifier les détails de l'offre</h1>
                <p class="text-gray-400 text-xs mt-1">Éditez les informations de l'offre postée par {{ $offre->entreprise->raison_sociale }}.</p>
            </div>

            <form action="{{ route('admin.offres.update', $offre) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Titre de l'offre</label>
                        <input type="text" name="titre" value="{{ old('titre', $offre->titre) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Type de contrat</label>
                        <select name="id_type_cont" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                            @foreach(\App\Models\TypeContrat::all() as $type)
                                <option value="{{ $type->id_type_cont }}" {{ $offre->id_type_cont == $type->id_type_cont ? 'selected' : '' }}>{{ $type->libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Localisation -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Localisation</label>
                        <input type="text" name="lieu" value="{{ old('lieu', $offre->lieu) }}"
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>

                    <!-- Salaire -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Salaire annuel (approx.)</label>
                        <input type="text" name="salaire" value="{{ old('salaire', $offre->salaire) }}"
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>

                    <!-- Date d'expiration -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Date d'expiration</label>
                        <input type="date" name="date_expiration" value="{{ $offre->date_expiration ? $offre->date_expiration->format('Y-m-d') : '' }}"
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Description du poste</label>
                        <textarea name="description" rows="10" 
                                  class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">{{ old('description', $offre->description) }}</textarea>
                    </div>

                    <!-- Critères & Logistique -->
                    <div class="md:col-span-2 space-y-4 border-t border-gray-100 pt-6 mt-2">
                        <h3 class="text-sm font-black text-[#204263]">Critères & Logistique de l'offre</h3>
                        <p class="text-xs text-gray-400 mb-4">Ces critères sont utilisés par le radar à emploi pour recommander cette offre aux candidats les plus pertinents.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer group hover:border-acpe-blue transition-colors">
                                <input type="checkbox" name="debutant_accepte" value="1" {{ old('debutant_accepte', $offre->debutant_accepte) ? 'checked' : '' }} class="rounded border-gray-300 text-acpe-blue focus:ring-acpe-blue h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Débutant accepté</span>
                                    <span class="text-[10px] text-gray-400">Aucune expérience n'est requise</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer group hover:border-acpe-blue transition-colors">
                                <input type="checkbox" name="permis_b_requis" value="1" {{ old('permis_b_requis', $offre->permis_b_requis) ? 'checked' : '' }} class="rounded border-gray-300 text-acpe-blue focus:ring-acpe-blue h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Permis B requis</span>
                                    <span class="text-[10px] text-gray-400">Le permis de conduire est obligatoire</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer group hover:border-acpe-blue transition-colors">
                                <input type="checkbox" name="vehicule_requis" value="1" {{ old('vehicule_requis', $offre->vehicule_requis) ? 'checked' : '' }} class="rounded border-gray-300 text-acpe-blue focus:ring-acpe-blue h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Véhicule requis</span>
                                    <span class="text-[10px] text-gray-400">Le candidat doit être véhiculé</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer group hover:border-acpe-blue transition-colors">
                                <input type="checkbox" name="travail_nuit" value="1" {{ old('travail_nuit', $offre->travail_nuit) ? 'checked' : '' }} class="rounded border-gray-300 text-acpe-blue focus:ring-acpe-blue h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Travail de nuit</span>
                                    <span class="text-[10px] text-gray-400">Horaires décalés ou de nuit</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer group hover:border-acpe-blue transition-colors">
                                <input type="checkbox" name="travail_weekend" value="1" {{ old('travail_weekend', $offre->travail_weekend) ? 'checked' : '' }} class="rounded border-gray-300 text-acpe-blue focus:ring-acpe-blue h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Travail le week-end</span>
                                    <span class="text-[10px] text-gray-400">Implication durant les jours de repos</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-50">
                    <a href="{{ route('admin.offres') }}" class="px-6 py-3 text-gray-400 hover:text-gray-600 text-sm font-bold transition-all">
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
