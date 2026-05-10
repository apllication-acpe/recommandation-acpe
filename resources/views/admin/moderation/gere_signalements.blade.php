<x-admin-layout>
    @section('title', 'Modération & Sécurité')

    <div class="space-y-8 animate-slide-up">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-[#204263] tracking-tight">Modération & Sécurité</h1>
                <p class="text-gray-400 text-sm mt-1">Surveillez l'intégrité de la plateforme et traitez les signalements prioritaires.</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-red-50 px-6 py-3 rounded-2xl border border-red-100 flex items-center shadow-sm">
                    <div class="h-2 w-2 bg-red-500 rounded-full animate-ping mr-3"></div>
                    <span class="text-[11px] font-black text-red-600 uppercase tracking-wider">{{ $stats['en_attente'] }} Urgences en attente</span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-orange-50/50 text-7xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Offres signalées</p>
                <div class="flex items-center space-x-3">
                    <h3 class="text-3xl font-black text-[#204263]">{{ $stats['offres'] }}</h3>
                    <span class="px-2 py-1 bg-orange-50 text-acpe-orange text-[9px] font-black rounded-lg uppercase">Priorité</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-blue-50/50 text-7xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Profils signalés</p>
                <div class="flex items-center space-x-3">
                    <h3 class="text-3xl font-black text-[#204263]">{{ $stats['profils'] }}</h3>
                    <span class="px-2 py-1 bg-blue-50 text-acpe-blue text-[9px] font-black rounded-lg uppercase">Audit</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-emerald-50/50 text-7xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Taux de résolution</p>
                <div class="flex items-center space-x-3">
                    <h3 class="text-3xl font-black text-[#204263]">{{ $stats['total'] > 0 ? round((($stats['total'] - $stats['en_attente']) / $stats['total']) * 100) : 100 }}%</h3>
                    <div class="h-1.5 w-16 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" style="width: {{ $stats['total'] > 0 ? (($stats['total'] - $stats['en_attente']) / $stats['total']) * 100 : 100 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 bg-gradient-to-br from-[#204263] to-[#1a3550] text-white">
                <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-3">Volume total</p>
                <h3 class="text-3xl font-black">{{ $stats['total'] }}</h3>
                <p class="text-[9px] font-bold text-white/40 mt-2 italic">Signalements cumulés depuis 30j</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                <h2 class="text-lg font-black text-[#204263]">File de modération</h2>
                <div class="flex space-x-2">
                    <button class="p-2 hover:bg-gray-50 rounded-lg text-gray-400 transition-colors"><i class="fa-solid fa-filter text-xs"></i></button>
                    <button class="p-2 hover:bg-gray-50 rounded-lg text-gray-400 transition-colors"><i class="fa-solid fa-rotate text-xs"></i></button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Cible du signalement</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Détails & Motif</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Auteur</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Gravité</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($signalements as $report)
                        @php
                            $gravProps = match($report->gravite) {
                                'haute' => ['bg' => 'red-50', 'text' => 'red-600', 'icon' => 'fa-triangle-exclamation'],
                                'moyenne' => ['bg' => 'orange-50', 'text' => 'orange-600', 'icon' => 'fa-circle-exclamation'],
                                'basse' => ['bg' => 'blue-50', 'text' => 'blue-600', 'icon' => 'fa-info-circle'],
                                default => ['bg' => 'gray-50', 'text' => 'gray-600', 'icon' => 'fa-circle-question']
                            };
                            $targetName = $report->signalable ? ($report->signalable->titre ?? $report->signalable->nom ?? $report->signalable->raison_sociale ?? 'Contenu ID: ' . $report->signalable_id) : 'Contenu supprimé';
                            $typeIcon = match(class_basename($report->signalable_type)) {
                                'Offre' => 'fa-briefcase',
                                'User', 'Demandeur' => 'fa-user',
                                'Entreprise' => 'fa-building',
                                'Message' => 'fa-envelope',
                                default => 'fa-file-alt'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-white group-hover:shadow-md transition-all">
                                        <i class="fa-solid {{ $typeIcon }} text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-[9px] font-black text-gray-300 uppercase block leading-none mb-1">{{ class_basename($report->signalable_type) }}</span>
                                        <p class="text-sm font-black text-[#204263]">{{ Str::limit($targetName, 30) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="max-w-xs">
                                    <p class="text-xs font-bold text-[#204263] mb-1">{{ $report->motif }}</p>
                                    @if($report->description)
                                        <p class="text-[10px] text-gray-400 italic line-clamp-1 group-hover:line-clamp-none transition-all">"{{ $report->description }}"</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border border-white shadow-sm">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($report->user->prenom . ' ' . $report->user->nom ?? 'A') }}&background=random" alt="">
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-[#204263] uppercase">{{ $report->user ? $report->user->prenom . ' ' . $report->user->nom : 'Anonyme' }}</p>
                                        <p class="text-[9px] text-gray-300 font-bold uppercase tracking-tighter">{{ $report->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black bg-{{ $gravProps['bg'] }} text-{{ $gravProps['text'] }} uppercase tracking-widest shadow-sm">
                                    <i class="fa-solid {{ $gravProps['icon'] }} mr-2"></i>
                                    {{ $report->gravite }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($report->statut === 'en_attente')
                                        <form action="{{ route('admin.moderation.signalements.ignorer', $report->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Ignorer le signalement" class="h-9 w-9 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.moderation.signalements.bannir', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Voulez-vous vraiment agir sur ce contenu ?');">
                                            @csrf
                                            <button type="submit" title="Bannir / Désactiver" class="h-9 w-9 bg-red-50 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                <i class="fa-solid fa-ban text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <div class="px-4 py-1.5 bg-gray-50 rounded-xl border border-gray-100">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center">
                                                <i class="fa-solid fa-circle-check mr-2 text-emerald-400"></i>
                                                {{ $report->statut }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="h-16 w-16 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-shield-heart text-3xl"></i>
                                </div>
                                <h4 class="text-sm font-black text-[#204263] uppercase tracking-widest">Tout est sous contrôle</h4>
                                <p class="text-xs text-gray-400 mt-1">Aucun nouveau signalement à traiter pour le moment.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($signalements->hasPages())
            <div class="p-8 border-t border-gray-50 bg-gray-50/20">
                {{ $signalements->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
