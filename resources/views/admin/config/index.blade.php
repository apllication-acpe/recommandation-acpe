<x-admin-layout>
    @section('title', 'Configuration Système')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Configuration</h1>
                <p class="text-gray-400 text-sm">Gérez les paramètres globaux et les référentiels de la plateforme.</p>
            </div>
        </div>

        <!-- Config Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Secteurs d'activité -->
            <a href="{{ route('admin.config.secteurs') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-blue-50 text-acpe-blue flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-layer-group text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Secteurs d'activité</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Gérez la liste des domaines professionnels disponibles pour les offres et les candidats.</p>
                <div class="flex items-center text-[10px] font-black text-acpe-blue uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Types de contrat -->
            <a href="{{ route('admin.config.types') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-orange-50 text-acpe-orange flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-signature text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Types de contrat</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Configurez les différents modèles de contrat (CDI, CDD, Stage, Alternance, etc.).</p>
                <div class="flex items-center text-[10px] font-black text-acpe-orange uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Compétences -->
            <a href="{{ route('admin.config.competences') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-lightbulb text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Compétences</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Administrez le dictionnaire des compétences techniques et comportementales.</p>
                <div class="flex items-center text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Nationalités -->
            <a href="{{ route('admin.config.nationalites') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-flag text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Nationalités</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Gérez la liste des pays et nationalités pour les profils candidats.</p>
                <div class="flex items-center text-[10px] font-black text-purple-600 uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Langues -->
            <a href="{{ route('admin.config.langues') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-language text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Langues</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Configurez les langues étrangères parlées par les candidats.</p>
                <div class="flex items-center text-[10px] font-black text-pink-600 uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Localisations -->
            <a href="{{ route('admin.config.localisations') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-map-location-dot text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Localisations</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Gérez les villes et zones géographiques pour les offres d'emploi.</p>
                <div class="flex items-center text-[10px] font-black text-teal-600 uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Qualifications -->
            <a href="{{ route('admin.config.qualifications') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Qualifications</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Définissez les niveaux de diplômes et de qualifications académiques.</p>
                <div class="flex items-center text-[10px] font-black text-yellow-600 uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Diplômes -->
            <a href="{{ route('admin.config.diplomes') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="h-14 w-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-scroll text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-[#204263] mb-2">Diplômes</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Gérez le catalogue complet des diplômes et spécialités reconnus.</p>
                <div class="flex items-center text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                    <span>Gérer le référentiel</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>
        </div>
    </div>
</x-admin-layout>