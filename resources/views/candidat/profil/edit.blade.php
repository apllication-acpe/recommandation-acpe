<x-candidat-layout>
    @section('title', 'Mon Profil & CV - Demandeur')

    <div x-data="{ 
            section: 'exp',
            showExpModal: false,
            newExps: [],
            isDebutant: {{ ($demandeur->annees_experience == 0 && $demandeur->annees_experience !== null) ? 'true' : 'false' }},
            tmpPoste: '', tmpEntreprise: '', tmpDebut: '', tmpFin: '', tmpDesc: '',
            addExp() {
                if(this.tmpPoste && this.tmpEntreprise) {
                    this.newExps.push({ poste: this.tmpPoste, entreprise: this.tmpEntreprise, debut: this.tmpDebut, fin: this.tmpFin, desc: this.tmpDesc });
                    this.showExpModal = false;
                    this.tmpPoste = ''; this.tmpEntreprise = ''; this.tmpDebut = ''; this.tmpFin = ''; this.tmpDesc = '';
                } else {
                    alert('Veuillez remplir le poste et l\'entreprise');
                }
            }
        }">
    <form action="{{ route('candidat.profil.update') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto space-y-8 animate-slide-up">
        @csrf
        @method('PUT')
        
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-black text-[#204263]">Mon Profil & CV - Demandeur</h1>
            <div x-show="showExpModal" class="bg-red-500 text-white px-4 py-1 rounded text-[10px] font-bold animate-pulse">DEBUG: MODAL ACTIF</div>
            <div class="flex space-x-3">
                <a href="{{ route('candidat.dashboard') }}" class="px-6 py-2.5 bg-white border border-gray-100 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm flex items-center">Annuler</a>
                <button type="submit" class="px-6 py-2.5 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">Enregistrer les modifications</button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-xs font-bold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Personal Info & CV -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Avatar & Basic Info -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                    <div class="relative inline-block group mb-6">
                        <div class="h-28 w-28 rounded-full bg-gray-100 overflow-hidden border-4 border-white shadow-xl mx-auto">
                            @if($demandeur->photo_path)
                                <img id="preview-image" src="{{ asset('storage/' . $demandeur->photo_path) }}" class="h-full w-full object-cover" alt="">
                            @else
                                <img id="preview-image" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom . ' ' . Auth::user()->nom) }}&background=random" alt="">
                            @endif
                        </div>
                        <label for="photo" class="absolute bottom-0 right-0 h-8 w-8 bg-acpe-blue text-white rounded-full flex items-center justify-center border-4 border-white shadow-lg hover:scale-110 transition-transform cursor-pointer">
                            <i class="fa-solid fa-camera text-[10px]"></i>
                            <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewFile()">
                        </label>
                    </div>
                    <h2 class="text-lg font-black text-[#204263]">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h2>
                    <p class="text-[10px] font-black text-acpe-light-blue uppercase tracking-[0.2em] mt-1">Demandeur Vérifié</p>
                </div>

                <!-- CV Upload Box -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Mon CV (PDF)</h3>
                    <div class="border-2 border-dashed border-gray-100 rounded-2xl p-8 text-center group hover:border-acpe-orange transition-colors cursor-pointer relative">
                        <input type="file" name="cv" class="absolute inset-0 opacity-0 cursor-pointer" accept=".pdf">
                        <div class="h-12 w-12 bg-orange-50 text-acpe-orange rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-file-pdf text-xl"></i>
                        </div>
                        <p class="text-xs font-bold text-[#204263]">Cliquez pour importer</p>
                        <p class="text-[10px] text-gray-400 mt-1 font-medium">Ou glissez-déposez ici</p>
                    </div>
                    @if($demandeur->cv_path)
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl flex items-center justify-between">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                <i class="fa-solid fa-check-circle text-emerald-500"></i>
                                <span class="text-[10px] font-bold text-[#204263] truncate">Mon_CV_actuel.pdf</span>
                            </div>
                            <a href="{{ asset('storage/' . $demandeur->cv_path) }}" target="_blank" class="text-gray-300 hover:text-acpe-blue">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Form Sections -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations personnelles -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="h-8 w-8 bg-blue-50 text-acpe-blue rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-user-tag text-xs"></i>
                        </div>
                        <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Informations personnelles</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date de naissance</label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance', $demandeur->date_naissance) }}" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sexe</label>
                            <select name="sexe" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner appearance-none">
                                <option value="M" {{ old('sexe', $demandeur->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe', $demandeur->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nationalité</label>
                            <select name="id_nationalite" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner appearance-none">
                                <option value="">Choisir une nationalité</option>
                                @foreach($nationalites as $nat)
                                    <option value="{{ $nat->id_nationalite }}" {{ old('id_nationalite', $demandeur->id_nationalite) == $nat->id_nationalite ? 'selected' : '' }}>
                                        {{ $nat->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Adresse complète</label>
                            <input type="text" name="adresse" value="{{ old('adresse', $demandeur->adresse) }}" placeholder="Ex: 123 Rue de la Liberté, Dakar" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Téléphone</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $demandeur->user->telephone) }}" placeholder="Ex: +221 77 000 00 00" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-between">
                                Années d'expérience
                                <label class="flex items-center space-x-2 cursor-pointer text-acpe-orange">
                                    <input type="checkbox" x-model="isDebutant" class="rounded border-gray-300 text-acpe-orange focus:ring-acpe-orange h-3 w-3">
                                    <span class="text-[9px] font-bold">Je suis débutant (0 exp)</span>
                                </label>
                            </label>
                            <input type="number" name="annees_experience" x-bind:readonly="isDebutant" x-bind:value="isDebutant ? 0 : '{{ old('annees_experience', $demandeur->annees_experience) }}'" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner" :class="isDebutant ? 'opacity-50 cursor-not-allowed' : ''">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Description du profil</label>
                            <textarea name="description_profil" rows="4" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">{{ old('description_profil', $demandeur->description_profil) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Contraintes & Logistique -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-10 rounded-3xl shadow-sm border border-emerald-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="h-8 w-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <i class="fa-solid fa-truck-fast text-xs"></i>
                        </div>
                        <h3 class="text-sm font-black text-emerald-900 uppercase tracking-widest">Contraintes & Logistique</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Disponibilité</label>
                            <select name="disponibilite" class="w-full bg-white border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-emerald-500 shadow-sm appearance-none">
                                <option value="">Non précisée</option>
                                <option value="immediatement" {{ old('disponibilite', $demandeur->disponibilite) == 'immediatement' ? 'selected' : '' }}>Immédiate</option>
                                <option value="1_mois" {{ old('disponibilite', $demandeur->disponibilite) == '1_mois' ? 'selected' : '' }}>Sous 1 mois</option>
                                <option value="non_disponible" {{ old('disponibilite', $demandeur->disponibilite) == 'non_disponible' ? 'selected' : '' }}>En poste</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Mobilité (Rayon en km)</label>
                            <input type="number" name="mobilite_rayon_km" value="{{ old('mobilite_rayon_km', $demandeur->mobilite_rayon_km) }}" min="0" placeholder="Ex: 50" class="w-full bg-white border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-emerald-500 shadow-sm">
                        </div>

                        <!-- Checkboxes Logistiques -->
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <label class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-emerald-100/50 cursor-pointer group hover:border-emerald-300 transition-colors">
                                <input type="checkbox" name="permis_b" value="1" {{ old('permis_b', $demandeur->permis_b) ? 'checked' : '' }} class="rounded border-emerald-300 text-emerald-500 focus:ring-emerald-500 h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Permis B</span>
                                    <span class="text-[10px] text-gray-400">J'ai mon permis de conduire</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-emerald-100/50 cursor-pointer group hover:border-emerald-300 transition-colors">
                                <input type="checkbox" name="vehicule_personnel" value="1" {{ old('vehicule_personnel', $demandeur->vehicule_personnel) ? 'checked' : '' }} class="rounded border-emerald-300 text-emerald-500 focus:ring-emerald-500 h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Véhicule</span>
                                    <span class="text-[10px] text-gray-400">J'ai un véhicule personnel</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-emerald-100/50 cursor-pointer group hover:border-emerald-300 transition-colors">
                                <input type="checkbox" name="travail_nuit" value="1" {{ old('travail_nuit', $demandeur->travail_nuit) ? 'checked' : '' }} class="rounded border-emerald-300 text-emerald-500 focus:ring-emerald-500 h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Travail de nuit</span>
                                    <span class="text-[10px] text-gray-400">Je peux travailler de nuit</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-emerald-100/50 cursor-pointer group hover:border-emerald-300 transition-colors">
                                <input type="checkbox" name="travail_weekend" value="1" {{ old('travail_weekend', $demandeur->travail_weekend) ? 'checked' : '' }} class="rounded border-emerald-300 text-emerald-500 focus:ring-emerald-500 h-5 w-5 mr-4">
                                <div>
                                    <span class="text-xs font-black text-[#204263] block">Travail le week-end</span>
                                    <span class="text-[10px] text-gray-400">Je suis dispo les week-ends</span>
                                </div>
                            </label>
                        </div>

                        <!-- Types de Contrat Souhaités -->
                        <div class="mt-8 pt-8 border-t border-emerald-100/50">
                            <label class="text-[10px] font-black text-emerald-700 uppercase tracking-widest block mb-4">Types de contrat souhaités (Plusieurs choix possibles)</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($allTypeContrats as $type)
                                    <label class="flex items-center px-4 py-2 bg-white rounded-xl border border-emerald-100 cursor-pointer hover:border-emerald-500 transition-all group">
                                        <input type="checkbox" name="types_contrat_preferes[]" value="{{ $type->id_type_cont }}" 
                                            {{ $demandeur->typesContratPreferes->contains($type->id_type_cont) ? 'checked' : '' }}
                                            class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                                        <span class="text-[10px] font-black text-[#204263] uppercase group-hover:text-emerald-700 transition-colors">{{ $type->libelle }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Secteurs d'intérêt -->
                        <div class="mt-8 pt-8 border-t border-emerald-100/50">
                            <label class="text-[10px] font-black text-emerald-700 uppercase tracking-widest block mb-4">Secteurs d'intérêt (Optimise vos recommandations)</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($allSecteurs as $secteur)
                                    <label class="flex items-center px-4 py-2 bg-white rounded-xl border border-emerald-100 cursor-pointer hover:border-emerald-500 transition-all group">
                                        <input type="checkbox" name="secteurs_preferes[]" value="{{ $secteur->id_sect_act }}" 
                                            {{ $demandeur->secteursActivitePreferes->contains($secteur->id_sect_act) ? 'checked' : '' }}
                                            class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                                        <span class="text-[10px] font-black text-[#204263] uppercase group-hover:text-emerald-700 transition-colors">{{ $secteur->libelle }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expériences & Diplômes (Tabs style) -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="flex border-b border-gray-50 bg-gray-50/30">
                        <button type="button" @click="section = 'exp'" :class="section === 'exp' ? 'border-acpe-orange text-acpe-orange bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-8 py-4 text-[10px] font-black border-b-2 transition-all uppercase tracking-widest">Expériences</button>
                        <button type="button" @click="section = 'edu'" :class="section === 'edu' ? 'border-acpe-orange text-acpe-orange bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-8 py-4 text-[10px] font-black border-b-2 transition-all uppercase tracking-widest">Diplômes</button>
                        <button type="button" @click="section = 'qual'" :class="section === 'qual' ? 'border-acpe-orange text-acpe-orange bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-8 py-4 text-[10px] font-black border-b-2 transition-all uppercase tracking-widest">Qualifications</button>
                        <button type="button" @click="section = 'skill'" :class="section === 'skill' ? 'border-acpe-orange text-acpe-orange bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-8 py-4 text-[10px] font-black border-b-2 transition-all uppercase tracking-widest">Compétences</button>
                        <button type="button" @click="section = 'lang'" :class="section === 'lang' ? 'border-acpe-orange text-acpe-orange bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-8 py-4 text-[10px] font-black border-b-2 transition-all uppercase tracking-widest">Langues</button>
                    </div>

                        <div x-show="section === 'exp'" class="space-y-6">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-[10px] text-gray-400 font-bold italic">Racontez votre parcours professionnel</p>
                                <button type="button" onclick="ouvrirModalExp()" class="h-10 w-10 bg-acpe-orange text-white rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg shadow-orange-500/20">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </button>
                            </div>
                            
                            <!-- Conteneur pour les nouvelles expériences (Vanilla JS) -->
                            <div id="listeNouvellesExps" class="space-y-4"></div>

                            @forelse($demandeur->experiences as $experience)
                                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-start space-x-6">
                                    <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center text-[#204263] border border-gray-100 shadow-sm">
                                        <i class="fa-solid fa-briefcase text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-xs font-black text-[#204263] uppercase">{{ $experience->poste_occupe }}</h4>
                                        <p class="text-[10px] font-bold text-acpe-orange mt-1">{{ $experience->entreprise }}</p>
                                        <p class="text-[10px] text-gray-400 mt-2 font-medium leading-relaxed">{{ Str::limit($experience->description, 100) }}</p>
                                    </div>
                                </div>
                            @empty
                                <div x-show="newExps.length === 0">
                                    <p class="text-center text-[10px] font-bold text-gray-300 py-8">Aucune expérience renseignée</p>
                                </div>
                            @endforelse
                        </div>

                        <div x-show="section === 'edu'" x-cloak class="space-y-6">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-[10px] text-gray-400 font-bold italic">Sélectionnez vos diplômes d'état</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($allDiplomes as $diplome)
                                    <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer hover:border-acpe-orange transition-colors">
                                        <input type="checkbox" name="diplomes[]" value="{{ $diplome->id_diplome }}" 
                                            {{ $demandeur->diplomes->contains($diplome->id_diplome) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-acpe-orange focus:ring-acpe-orange h-4 w-4 mr-3">
                                        <div class="min-w-0">
                                            <span class="text-[10px] font-black text-[#204263] uppercase block truncate">{{ $diplome->libelle }}</span>
                                            <span class="text-[9px] text-gray-400">{{ $diplome->niveau }} {{ $diplome->specialite }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="section === 'qual'" x-cloak class="space-y-6">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-[10px] text-gray-400 font-bold italic">Sélectionnez vos qualifications et certifications</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($allQualifications as $qual)
                                    <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer hover:border-acpe-orange transition-colors">
                                        <input type="checkbox" name="qualifications[]" value="{{ $qual->id_qualification }}" 
                                            {{ $demandeur->qualifications->contains($qual->id_qualification) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-acpe-orange focus:ring-acpe-orange h-4 w-4 mr-3">
                                        <div class="min-w-0">
                                            <span class="text-[10px] font-black text-[#204263] uppercase block truncate">{{ $qual->intitule ?? $qual->libelle ?? $qual->designation }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="section === 'skill'" x-cloak class="space-y-6">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-[10px] text-gray-400 font-bold italic">Cochez vos compétences techniques</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($allCompetences as $comp)
                                    <label class="flex items-center p-3 bg-blue-50/50 rounded-xl border border-blue-100 cursor-pointer hover:border-acpe-blue transition-colors">
                                        <input type="checkbox" name="competences[]" value="{{ $comp->id_competence }}" 
                                            {{ $demandeur->competences->contains($comp->id_competence) ? 'checked' : '' }}
                                            class="rounded border-blue-300 text-acpe-blue focus:ring-acpe-blue h-4 w-4 mr-2">
                                        <span class="text-[10px] font-black text-acpe-blue uppercase truncate">{{ $comp->libelle }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="section === 'lang'" x-cloak class="space-y-6">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-[10px] text-gray-400 font-bold italic">Langues maîtrisées</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($allLangues as $lang)
                                    <label class="flex items-center p-3 bg-purple-50/50 rounded-xl border border-purple-100 cursor-pointer hover:border-purple-400 transition-colors">
                                        <input type="checkbox" name="langues[]" value="{{ $lang->id_langue }}" 
                                            {{ $demandeur->langues->contains($lang->id_langue) ? 'checked' : '' }}
                                            class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 h-4 w-4 mr-2">
                                        <span class="text-[10px] font-black text-purple-900 uppercase truncate">{{ $lang->libelle }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Ajouter Expérience (Vanilla JS pour forcer l'affichage) -->
        <div id="modalExperience" 
                class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-[#204263]/90">
            <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl space-y-6 relative border-4 border-acpe-orange">
                <h4 class="text-sm font-black text-[#204263] uppercase tracking-widest">Ajouter une expérience</h4>
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase">Poste occupé</label>
                        <input type="text" x-model="tmpPoste" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold px-4 py-3 focus:ring-acpe-orange">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase">Entreprise</label>
                        <input type="text" x-model="tmpEntreprise" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold px-4 py-3 focus:ring-acpe-orange">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase">Début</label>
                            <input type="date" x-model="tmpDebut" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold px-4 py-3 focus:ring-acpe-orange">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase">Fin</label>
                            <input type="date" x-model="tmpFin" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold px-4 py-3 focus:ring-acpe-orange">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase">Description</label>
                        <textarea x-model="tmpDesc" rows="3" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold px-4 py-3 focus:ring-acpe-orange"></textarea>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="fermerModalExp()" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase">Annuler</button>
                    <button type="button" onclick="validerAjoutExp()" class="flex-1 py-3 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase">Ajouter</button>
                </div>
            </div>
        </div>
    </form>
    </div>
    <script>
        let expCount = 0;

        function ouvrirModalExp() {
            document.getElementById('modalExperience').style.display = 'flex';
        }
        function fermerModalExp() {
            document.getElementById('modalExperience').style.display = 'none';
        }
        function validerAjoutExp() {
            const poste = document.querySelector('[x-model="tmpPoste"]').value;
            const entreprise = document.querySelector('[x-model="tmpEntreprise"]').value;
            const debut = document.querySelector('[x-model="tmpDebut"]').value;
            const fin = document.querySelector('[x-model="tmpFin"]').value;
            const desc = document.querySelector('[x-model="tmpDesc"]').value;

            if(!poste || !entreprise) {
                alert('Veuillez remplir le poste et l\'entreprise');
                return;
            }

            const container = document.getElementById('listeNouvellesExps');
            const html = `
                <div class="p-6 bg-emerald-50/30 rounded-2xl border border-emerald-100 flex items-start space-x-6 relative animate-slide-up">
                    <input type="hidden" name="new_experiences[${expCount}][poste]" value="${poste}">
                    <input type="hidden" name="new_experiences[${expCount}][entreprise]" value="${entreprise}">
                    <input type="hidden" name="new_experiences[${expCount}][date_debut]" value="${debut}">
                    <input type="hidden" name="new_experiences[${expCount}][date_fin]" value="${fin}">
                    <input type="hidden" name="new_experiences[${expCount}][description]" value="${desc}">
                    
                    <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center text-emerald-500 border border-emerald-100 shadow-sm">
                        <i class="fa-solid fa-briefcase text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h4 class="text-xs font-black text-[#204263] uppercase">${poste}</h4>
                            <button type="button" onclick="this.closest('.relative').remove()" class="text-red-300 hover:text-red-500">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                        </div>
                        <p class="text-[10px] font-bold text-acpe-orange mt-1">${entreprise}</p>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium leading-relaxed">${desc}</p>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            expCount++;
            fermerModalExp();
            
            // Reset fields
            document.querySelector('[x-model="tmpPoste"]').value = '';
            document.querySelector('[x-model="tmpEntreprise"]').value = '';
            document.querySelector('[x-model="tmpDebut"]').value = '';
            document.querySelector('[x-model="tmpFin"]').value = '';
            document.querySelector('[x-model="tmpDesc"]').value = '';
        }
        function previewFile() {
            const preview = document.getElementById('preview-image');
            const file = document.getElementById('photo').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-candidat-layout>
