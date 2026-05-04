<x-admin-layout>
    @section('title', 'Détail de la Candidature')

    <div class="max-w-5xl mx-auto space-y-6 animate-slide-up">
        <!-- Breadcrumbs -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('admin.candidatures') }}" class="text-gray-400 hover:text-acpe-blue transition-colors">Candidatures</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-[#204263] font-bold">Candidature #{{ $candidature->id }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Candidature Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 md:flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="h-20 w-20 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center font-black text-2xl border-2 border-white shadow-xl">
                                {{ substr($candidature->demandeur->user->prenom, 0, 1) }}{{ substr($candidature->demandeur->user->nom, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-[#204263]">{{ $candidature->demandeur->user->prenom }} {{ $candidature->demandeur->user->nom }}</h1>
                                <p class="text-sm text-gray-400 mt-1">Candidature pour : <span class="font-bold text-acpe-blue">{{ $candidature->offre->titre }}</span></p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                            <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest 
                                {{ $candidature->statut === 'acceptee' ? 'bg-emerald-50 text-emerald-600' : ($candidature->statut === 'refusee' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                                {{ ucfirst($candidature->statut) }}
                            </span>
                            <p class="text-[10px] text-gray-300 font-bold mt-2 uppercase">Postulé le {{ $candidature->date_candidature->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Motivation / Documents -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xs font-black text-gray-300 uppercase tracking-widest mb-6">Message de motivation</h3>
                    <div class="bg-gray-50 rounded-2xl p-6 text-sm text-gray-600 leading-relaxed italic">
                        "{{ $candidature->lettre_motivation ?? 'Aucun message de motivation fourni.' }}"
                    </div>
                    
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border border-gray-100 rounded-2xl flex items-center justify-between group hover:bg-gray-50 transition-all cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-file-pdf text-red-400 text-xl"></i>
                                <div>
                                    <p class="text-xs font-bold text-[#204263]">Curriculum Vitae</p>
                                    <p class="text-[10px] text-gray-400">PDF • 1.2 MB</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-download text-gray-300 group-hover:text-acpe-blue"></i>
                        </div>
                        <div class="p-4 border border-gray-100 rounded-2xl flex items-center justify-between group hover:bg-gray-50 transition-all cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-file-lines text-blue-400 text-xl"></i>
                                <div>
                                    <p class="text-xs font-bold text-[#204263]">Lettre de Recommandation</p>
                                    <p class="text-[10px] text-gray-400">DOCX • 0.8 MB</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-download text-gray-300 group-hover:text-acpe-blue"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Context Info -->
            <div class="space-y-6">
                <!-- Entreprise Info -->
                <div class="bg-[#204263] text-white rounded-3xl p-8 shadow-xl shadow-acpe-blue/20">
                    <h3 class="text-[10px] font-black opacity-50 uppercase tracking-widest mb-6">L'Entreprise</h3>
                    <div class="flex items-center space-x-4 mb-6">
                        @if($candidature->offre->entreprise->logo_path)
                            <img src="{{ $candidature->offre->entreprise->logo_url }}" class="h-12 w-12 rounded-xl bg-white p-1">
                        @else
                            <div class="h-12 w-12 rounded-xl bg-white/10 flex items-center justify-center font-bold text-acpe-orange">
                                {{ substr($candidature->offre->entreprise->raison_sociale, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-bold">{{ $candidature->offre->entreprise->raison_sociale }}</p>
                            <p class="text-[10px] opacity-60">{{ $candidature->offre->entreprise->secteurActivite->nom }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.entreprises.show', $candidature->offre->entreprise) }}" class="block w-full py-3 bg-white/10 hover:bg-white/20 text-center text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        Voir le profil entreprise
                    </a>
                </div>

                <!-- Admin Actions -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xs font-black text-gray-300 uppercase tracking-widest mb-6">Actions Administrateur</h3>
                    <div class="space-y-3">
                        <button class="w-full py-3 bg-gray-50 text-gray-500 text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all">Annuler la candidature</button>
                        <button class="w-full py-3 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-xl hover:bg-red-100 transition-all">Signaler pour fraude</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
