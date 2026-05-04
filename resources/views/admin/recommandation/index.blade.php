@extends('layouts.admin')

@section('title', 'Performance des recommandations')

@section('content')
<div x-data="recommandationsManager()">
    <h2 class="text-2xl font-bold mb-6">🎯 Performance de l'algorithme de recommandation</h2>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
            <div class="text-sm opacity-90">Score moyen</div>
            <div class="text-3xl font-bold">{{ $performance['score_moyen'] ?? 0 }}/100</div>
            <div class="text-xs mt-2">sur toutes les recommandations</div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="text-sm opacity-90">Précision @1</div>
            <div class="text-3xl font-bold">{{ $performance['precision_@1'] ?? 0 }}%</div>
            <div class="text-xs mt-2">Top 1 recommandation</div>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
            <div class="text-sm opacity-90">Taux de clics (CTR)</div>
            <div class="text-3xl font-bold">{{ $stats['taux_clics'] ?? 0 }}%</div>
            <div class="text-xs mt-2">Clics / Recommandations</div>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
            <div class="text-sm opacity-90">Taux conversion</div>
            <div class="text-3xl font-bold">{{ $stats['taux_conversion'] ?? 0 }}%</div>
            <div class="text-xs mt-2">Postulations / Recommandations</div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">📈 Évolution du score moyen</h3>
            <canvas id="scoreEvolutionChart" height="250"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">📊 Distribution des scores</h3>
            <canvas id="scoreDistributionChart" height="250"></canvas>
        </div>
    </div>

    <!-- Ajustement des poids -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="font-semibold mb-4">⚙️ Configuration des poids (algorithme)</h3>
        <form action="{{ route('admin.recommandations.poids') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Compétences techniques</label>
                    <input type="range" name="poids_competences" min="0" max="100" value="{{ $poids['competences'] ?? 35 }}" class="w-full" x-model="poids.competences">
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>0%</span>
                        <span x-text="poids.competences + '%'"></span>
                        <span>100%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Diplômes</label>
                    <input type="range" name="poids_diplomes" min="0" max="100" value="{{ $poids['diplomes'] ?? 25 }}" class="w-full" x-model="poids.diplomes">
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>0%</span>
                        <span x-text="poids.diplomes + '%'"></span>
                        <span>100%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Expérience</label>
                    <input type="range" name="poids_experience" min="0" max="100" value="{{ $poids['experience'] ?? 20 }}" class="w-full" x-model="poids.experience">
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>0%</span>
                        <span x-text="poids.experience + '%'"></span>
                        <span>100%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Langues</label>
                    <input type="range" name="poids_langues" min="0" max="100" value="{{ $poids['langues'] ?? 10 }}" class="w-full" x-model="poids.langues">
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>0%</span>
                        <span x-text="poids.langues + '%'"></span>
                        <span>100%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Localisation</label>
                    <input type="range" name="poids_localisation" min="0" max="100" value="{{ $poids['localisation'] ?? 10 }}" class="w-full" x-model="poids.localisation">
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>0%</span>
                        <span x-text="poids.localisation + '%'"></span>
                        <span>100%</span>
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-yellow-800">⚠️ La somme des poids devrait être de 100%</p>
                    <p class="text-sm font-bold mt-1">Total actuel: <span x-text="totalPoids"></span>%</p>
                </div>
                <button type="submit" class="btn-primary">💾 Enregistrer les modifications</button>
            </div>
        </form>
    </div>

    <!-- Top recommandations avec feedback -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold">🏆 Top recommandations (avec feedback)</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($topRecommandations as $reco)
            <div class="px-6 py-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium">{{ $reco->offre->titre }}</p>
                        <p class="text-sm text-gray-600">Pour: {{ $reco->demandeur->user->prenom }} {{ $reco->demandeur->user->nom }}</p>
                        <div class="flex items-center gap-4 mt-2 text-sm">
                            <span>Score: <strong>{{ $reco->score_final }}%</strong></span>
                            <span>Rang: #{{ $reco->rang }}</span>
                            <span>Statut: {{ $reco->statut }}</span>
                            <span>Date: {{ $reco->date_recommandation->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if($reco->a_ete_clique)
                        <span class="badge badge-info">👁️ Cliqué</span>
                        @endif
                        @if($reco->a_ete_postule)
                        <span class="badge badge-success">📝 Postulé</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">Aucune recommandation</div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function recommandationsManager() {
    return {
        poids: {
            competences: {{ $poids['competences'] ?? 35 }},
            diplomes: {{ $poids['diplomes'] ?? 25 }},
            experience: {{ $poids['experience'] ?? 20 }},
            langues: {{ $poids['langues'] ?? 10 }},
            localisation: {{ $poids['localisation'] ?? 10 }}
        },
        
        get totalPoids() {
            return this.poids.competences + this.poids.diplomes + this.poids.experience + this.poids.langues + this.poids.localisation;
        },
        
        init() {
            // Graphique évolution score
            const ctx1 = document.getElementById('scoreEvolutionChart')?.getContext('2d');
            if(ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: @json($evolution['labels'] ?? []),
                        datasets: [{
                            label: 'Score moyen',
                            data: @json($evolution['data'] ?? []),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: true }
                });
            }
            
            // Graphique distribution scores
            const ctx2 = document.getElementById('scoreDistributionChart')?.getContext('2d');
            if(ctx2) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['0-20', '20-40', '40-60', '60-80', '80-100'],
                        datasets: [{
                            label: 'Nombre de recommandations',
                            data: @json($distribution ?? []),
                            backgroundColor: '#10B981',
                            borderRadius: 5
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: true }
                });
            }
        }
    }
}
</script>
@endpush
@endsection