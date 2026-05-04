<x-admin-layout>
    @section('title', 'Gestion des Signalements')

    <div class="space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Gestion des Signalements</h1>
                <p class="text-gray-400 text-sm">Modérez les contenus signalés par les utilisateurs (offres, profils, messages).</p>
            </div>
            <div class="flex items-center bg-white border border-gray-100 px-4 py-2 rounded-xl shadow-sm">
                <span class="text-xs font-bold text-red-500 mr-2">12 Signalements en attente</span>
                <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Offres signalées</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl font-black text-[#204263]">{{ $stats['offres'] }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Profils signalés</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl font-black text-[#204263]">{{ $stats['profils'] }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">En attente de résolution</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl font-black text-[#204263]">{{ $stats['en_attente'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Signalements Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type / Cible</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Motif du signalement</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Signalé par</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Gravité</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($signalements as $report)
                        @php
                            $color = match($report->gravite) {
                                'haute' => 'red',
                                'moyenne' => 'orange',
                                'basse' => 'blue',
                                default => 'gray'
                            };
                            $targetName = $report->signalable ? ($report->signalable->titre ?? $report->signalable->nom ?? $report->signalable->raison_sociale ?? 'Inconnu') : 'Supprimé';
                            $typeName = class_basename($report->signalable_type);
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black text-gray-300 uppercase block mb-0.5">{{ $typeName }}</span>
                                <p class="text-sm font-bold text-[#204263]">{{ $targetName }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-[#204263]">{{ $report->motif }}</p>
                                @if($report->description)
                                    <p class="text-[10px] text-gray-500 mt-1 line-clamp-1">{{ $report->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-medium text-gray-500">{{ $report->user ? $report->user->prenom . ' ' . $report->user->nom : 'Anonyme' }}</p>
                                <p class="text-[9px] text-gray-300 tracking-tighter uppercase font-bold">{{ $report->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-{{ $color }}-50 text-{{ $color }}-600 uppercase tracking-widest">
                                    {{ ucfirst($report->gravite) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($report->statut === 'en_attente')
                                        <form action="{{ route('moderation.signalements.ignorer', $report->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg hover:bg-emerald-100 transition-all">Ignorer</button>
                                        </form>
                                        <form action="{{ route('moderation.signalements.bannir', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir bannir/désactiver cette cible ?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-lg hover:bg-red-100 transition-all">Bannir</button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">{{ ucfirst($report->statut) }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-gray-500 font-bold">Aucun signalement</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($signalements->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $signalements->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
