<x-admin-layout>
    @section('title', 'Créer une Offre d\'Emploi')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <!-- Header -->
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('admin.offres') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Nouvelle Offre d'Emploi</h1>
                <p class="text-gray-400 text-sm">Publiez une offre d'emploi au nom d'une entreprise.</p>
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

        <form action="{{ route('admin.offres.store') }}" method="POST">
            @csrf

            <!-- Informations principales -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-6">
                <h2 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6 pb-4 border-b border-gray-100">
                    <i class="fa-solid fa-briefcase text-acpe-blue mr-2"></i> Informations de l'offre
                </h2>

                <div class="space-y-5">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Titre du poste *</label>
                        <input type="text" name="titre" value="{{ old('titre') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20"
                               placeholder="Ex: Développeur Web Full Stack">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Entreprise *</label>
                            <select name="id_entreprise" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                                <option value="">— Sélectionner une entreprise —</option>
                                @foreach($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id_entreprise }}" {{ old('id_entreprise') == $entreprise->id_entreprise ? 'selected' : '' }}>
                                        {{ $entreprise->raison_sociale }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Type de contrat *</label>
                            <select name="id_type_cont" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                                <option value="">— Sélectionner un type —</option>
                                @foreach($typesContrat as $type)
                                    <option value="{{ $type->id_type_cont }}" {{ old('id_type_cont') == $type->id_type_cont ? 'selected' : '' }}>
                                        {{ $type->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Localisation *</label>
                            <select name="id_localisation" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                                <option value="">— Sélectionner une ville —</option>
                                @foreach($localisations as $loc)
                                    <option value="{{ $loc->id_localisation }}" {{ old('id_localisation') == $loc->id_localisation ? 'selected' : '' }}>
                                        {{ $loc->ville }} ({{ $loc->pays }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Secteur d'activité *</label>
                        <select name="id_sect_act" required class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                            <option value="">— Sélectionner un secteur —</option>
                            @foreach($secteurs as $secteur)
                                <option value="{{ $secteur->id_sect_act }}" {{ old('id_sect_act') == $secteur->id_sect_act ? 'selected' : '' }}>
                                    {{ $secteur->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Description *</label>
                        <textarea name="description" rows="5" required
                                  class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20"
                                  placeholder="Décrivez le poste, le contexte, l'environnement de travail...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Missions principales</label>
                        <textarea name="mission" rows="4"
                                  class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20"
                                  placeholder="Listez les missions et responsabilités du poste...">{{ old('mission') }}</textarea>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Profil recherché</label>
                        <textarea name="profil_recherche" rows="4"
                                  class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20"
                                  placeholder="Formation, expérience, compétences requises...">{{ old('profil_recherche') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Rémunération & Dates -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-6">
                <h2 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6 pb-4 border-b border-gray-100">
                    <i class="fa-solid fa-coins text-acpe-orange mr-2"></i> Rémunération & Planning
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Salaire min (FCFA)</label>
                        <input type="number" name="salaire_min" value="{{ old('salaire_min') }}" min="0"
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-orange/20"
                               placeholder="150000">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Salaire max (FCFA)</label>
                        <input type="number" name="salaire_max" value="{{ old('salaire_max') }}" min="0"
                               class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-orange/20"
                               placeholder="300000">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Statut salaire</label>
                        <select name="statut_salaire" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm">
                            <option value="net" {{ old('statut_salaire') == 'net' ? 'selected' : '' }}>Net</option>
                            <option value="brut" {{ old('statut_salaire') == 'brut' ? 'selected' : '' }}>Brut</option>
                            <option value="negociable" {{ old('statut_salaire') == 'negociable' ? 'selected' : '' }}>Négociable</option>
                        </select>
                    </div>
                </div>
                <div class="mt-5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Date d'expiration</label>
                    <input type="date" name="date_expiration" value="{{ old('date_expiration') }}"
                           class="w-full md:w-1/2 px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                </div>
            </div>

            <!-- Options -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-6">
                <h2 class="text-sm font-black text-[#204263] uppercase tracking-widest mb-6 pb-4 border-b border-gray-100">
                    <i class="fa-solid fa-sliders text-emerald-600 mr-2"></i> Options & Conditions
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        ['name' => 'active',           'label' => 'Offre active',       'icon' => 'fa-check-circle',    'color' => 'emerald', 'default' => true],
                        ['name' => 'debutant_accepte', 'label' => 'Débutant accepté',   'icon' => 'fa-user-graduate',   'color' => 'blue'],
                        ['name' => 'permis_b_requis',  'label' => 'Permis B requis',    'icon' => 'fa-car',             'color' => 'orange'],
                        ['name' => 'vehicule_requis',  'label' => 'Véhicule requis',    'icon' => 'fa-truck',           'color' => 'orange'],
                        ['name' => 'travail_nuit',     'label' => 'Travail de nuit',    'icon' => 'fa-moon',            'color' => 'indigo'],
                        ['name' => 'travail_weekend',  'label' => 'Travail le weekend', 'icon' => 'fa-calendar-week',   'color' => 'pink'],
                    ] as $opt)
                    <label class="flex items-center space-x-3 p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all">
                        <input type="checkbox" name="{{ $opt['name'] }}" value="1"
                               {{ old($opt['name'], $opt['default'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 rounded text-acpe-blue">
                        <span class="text-xs font-bold text-gray-500">
                            <i class="fa-solid {{ $opt['icon'] }} text-{{ $opt['color'] }}-500 mr-1"></i>
                            {{ $opt['label'] }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-4">
                <a href="{{ route('admin.offres') }}" class="flex-1 px-6 py-3.5 bg-gray-50 text-gray-400 text-center text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all">
                    Annuler
                </a>
                <button type="submit" class="flex-1 px-6 py-3.5 bg-acpe-blue text-white text-[10px] font-black uppercase rounded-xl shadow-lg shadow-blue-500/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Publier l'offre
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
