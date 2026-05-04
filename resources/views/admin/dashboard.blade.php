<x-admin-layout>
    @section('title', 'Tableau de bord')

    <div class="space-y-8 animate-slide-up">
        
        <!-- Header Dashboard -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Bonjour, {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h1>
                <p class="text-gray-400 text-sm">Voici un aperçu général de la plateforme ACPE.</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center bg-white border border-gray-100 px-4 py-2 rounded-xl shadow-sm">
                    <span class="text-xs font-bold text-gray-500 mr-2">{{ now()->startOfMonth()->format('d/m/Y') }} - {{ now()->format('d/m/Y') }}</span>
                    <i class="fa-regular fa-calendar text-gray-400"></i>
                </div>
                <button class="bg-white border border-gray-100 text-[#204263] px-6 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-gray-50 transition-all">
                    Exporter le rapport
                </button>
            </div>
        </div>

        <!-- Top Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Utilisateurs actifs -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-400">Utilisateurs actifs</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-extrabold text-[#204263]">{{ number_format($usersCount) }}</h3>
                    <div class="flex items-center text-green-500 text-[10px] font-bold">
                        <i class="fa-solid fa-arrow-up mr-1"></i> +12.5% <span class="ml-1 text-gray-300 font-normal">vs mois dernier</span>
                    </div>
                </div>
            </div>

            <!-- Offres publiées -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-400">Offres publiées</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-extrabold text-[#204263]">{{ number_format($offresCount) }}</h3>
                    <div class="flex items-center text-green-500 text-[10px] font-bold">
                        <i class="fa-solid fa-arrow-up mr-1"></i> +8.3% <span class="ml-1 text-gray-300 font-normal">vs mois dernier</span>
                    </div>
                </div>
            </div>

            <!-- Candidatures -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-10 w-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-400">Candidatures</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-extrabold text-[#204263]">{{ number_format(\App\Models\Candidature::count()) }}</h3>
                    <div class="flex items-center text-green-500 text-[10px] font-bold">
                        <i class="fa-solid fa-arrow-up mr-1"></i> +15.7% <span class="ml-1 text-gray-300 font-normal">vs mois dernier</span>
                    </div>
                </div>
            </div>

            <!-- Embauches -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-10 w-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-400">Embauches</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-extrabold text-[#204263]">{{ number_format(\App\Models\Candidature::where('statut', 'acceptee')->count()) }}</h3>
                    <div class="flex items-center text-green-500 text-[10px] font-bold">
                        <i class="fa-solid fa-arrow-up mr-1"></i> +11.4% <span class="ml-1 text-gray-300 font-normal">vs mois dernier</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Chart Line: Offres -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-[#204263]">Offres publiées</h3>
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Par jour</p>
                </div>
                <div class="h-48 flex items-end space-x-1.5 pt-4">
                    @foreach($chartData as $count)
                        @php 
                            $h = ($count / $maxChart) * 100;
                            if($h == 0) $h = 2; // minimum height
                        @endphp
                        <div class="flex-1 bg-blue-50 rounded-t-sm hover:bg-acpe-orange transition-all relative group" style="height: {{ $h }}%">
                            <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-[#204263] text-white text-[8px] px-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-4 text-[9px] font-bold text-gray-300 uppercase tracking-widest">
                    <span>1</span>
                    <span>5</span>
                    <span>10</span>
                    <span>15</span>
                    <span>20</span>
                    <span>25</span>
                    <span>30</span>
                </div>
            </div>

            <!-- Chart Donut: Répartition -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-[#204263]">Répartition des offres par statut</h3>
                </div>
                <div class="flex items-center justify-between">
                    <div class="relative h-32 w-32">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="16" fill="none" stroke="#f1f5f9" stroke-width="3"></circle>
                            <circle cx="18" cy="18" r="16" fill="none" stroke="#4ade80" stroke-width="3" stroke-dasharray="71.8 100"></circle>
                            <circle cx="18" cy="18" r="16" fill="none" stroke="#facc15" stroke-width="3" stroke-dasharray="17.2 100" stroke-dashoffset="-71.8"></circle>
                            <circle cx="18" cy="18" r="16" fill="none" stroke="#f87171" stroke-width="3" stroke-dasharray="11 100" stroke-dashoffset="-89"></circle>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center text-[10px] font-bold text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-[#4ade80] mr-2"></span> Publiées <span class="ml-auto text-[#204263] pl-4">{{ $offresActives }}</span>
                        </div>
                        <div class="flex items-center text-[10px] font-bold text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-[#facc15] mr-2"></span> Inactives <span class="ml-auto text-[#204263] pl-4">{{ $offresCount - $offresActives }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion Chart -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
                <div class="mb-4 self-start text-left">
                    <h3 class="text-sm font-bold text-[#204263]">Taux de conversion</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Candidatures → Embauches</p>
                </div>
                <div class="relative h-40 w-40 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#f1f5f9" stroke-width="2.5"></circle>
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-dasharray="{{ $tauxConversion }} 100"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-[#204263]">{{ $tauxConversion }}%</span>
                    </div>
                </div>
                <div class="mt-4 flex items-center space-x-2">
                    <span class="text-[10px] font-bold text-gray-400">vs mois dernier</span>
                    <span class="text-[10px] font-bold text-green-500">+1.2%</span>
                </div>
            </div>
        </div>

        <!-- Bottom Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-12">
            <!-- Alertes & Notifications -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50">
                    <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Alertes & Notifications</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    <div class="px-8 py-5 flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <i class="fa-solid fa-triangle-exclamation text-orange-400 mt-1"></i>
                            <div>
                                <p class="text-xs font-bold text-[#204263]">{{ $offresExpirees->count() }} offres expirées</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Ces offres sont expirées et n'apparaissent plus aux candidats.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.offres') }}" class="text-[9px] font-black text-[#204263] border border-gray-100 px-3 py-1.5 rounded-lg hover:bg-gray-50 uppercase tracking-tighter">Voir les offres</a>
                    </div>
                    <div class="px-8 py-5 flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <i class="fa-solid fa-triangle-exclamation text-orange-400 mt-1"></i>
                            <div>
                                <p class="text-xs font-bold text-[#204263]">{{ $entreprisesNonVerifiees->count() }} entreprises non vérifiées</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Ces entreprises en attente de vérification.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.entreprises') }}" class="text-[9px] font-black text-[#204263] border border-gray-100 px-3 py-1.5 rounded-lg hover:bg-gray-50 uppercase tracking-tighter">Voir les entreprises</a>
                    </div>
                    <div class="px-8 py-3 bg-gray-50/30 text-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase">Aperçu en temps réel</span>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50">
                    <h3 class="text-sm font-black text-[#204263] uppercase tracking-widest">Dernières inscriptions</h3>
                </div>
                <div class="px-8 py-6 space-y-8 relative">
                    <div class="absolute left-[45px] top-8 bottom-8 w-[1px] bg-gray-100"></div>
                    
                    @foreach($dernieresInscriptions as $user)
                        <div class="relative flex items-center space-x-4">
                            <div class="h-8 w-8 rounded-full bg-blue-50 text-blue-500 border-2 border-white shadow-sm flex items-center justify-center text-[10px] font-bold z-10">
                                {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-baseline">
                                    <p class="text-[11px] font-bold text-[#204263]">{{ $user->nom_complet }}</p>
                                    <span class="text-[9px] font-bold text-gray-300">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[10px] text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    @endforeach

                    @if($dernieresInscriptions->isEmpty())
                        <p class="text-xs font-bold text-gray-300 italic text-center py-4">Aucune inscription récente</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>

