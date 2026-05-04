@extends('layouts.admin')

@section('title', 'Modération')

@section('content')
<div x-data="moderationManager()">
    <h2 class="text-2xl font-bold mb-6">⚠️ Modération des contenus</h2>

    <!-- Tabs -->
    <div class="flex border-b mb-6">
        <button @click="activeTab = 'offres'" :class="{'border-primary-500 text-primary-600': activeTab === 'offres'}" class="px-4 py-2 border-b-2 font-medium">📋 Offres signalées</button>
        <button @click="activeTab = 'commentaires'" :class="{'border-primary-500 text-primary-600': activeTab === 'commentaires'}" class="px-4 py-2 border-b-2 font-medium">💬 Commentaires signalés</button>
        <button @click="activeTab = 'messages'" :class="{'border-primary-500 text-primary-600': activeTab === 'messages'}" class="px-4 py-2 border-b-2 font-medium">✉️ Messages signalés</button>
    </div>

    <!-- Offres signalées -->
    <div x-show="activeTab === 'offres'">
        <div class="space-y-4">
            @forelse($offresSignalees as $offre)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-lg">{{ $offre->titre }}</h3>
                            <span class="badge badge-danger">⚠️ {{ $offre->signalements_count }} signalement(s)</span>
                        </div>
                        <p class="text-gray-600 text-sm">{{ $offre->entreprise->raison_sociale }}</p>
                        <p class="text-gray-500 text-sm mt-2">{{ Str::limit($offre->description, 200) }}</p>
                        <div class="mt-3 p-3 bg-red-50 rounded-lg">
                            <p class="text-sm font-medium text-red-800">Motifs des signalements:</p>
                            <ul class="text-sm text-red-700 mt-1">
                                @foreach($offre->signalements as $signalement)
                                <li>• {{ $signalement->motif }} (par {{ $signalement->user->email }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button @click="approuverOffre({{ $offre->id_offre }})" class="btn-success">✓ Approuver</button>
                        <button @click="modifierOffre({{ $offre->id_offre }})" class="btn-primary">✏️ Modifier</button>
                        <button @click="rejeterOffre({{ $offre->id_offre }})" class="btn-danger">🗑️ Supprimer</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                ✅ Aucune offre signalée
            </div>
            @endforelse
        </div>
    </div>

    <!-- Commentaires signalés -->
    <div x-show="activeTab === 'commentaires'">
        <div class="space-y-4">
            @forelse($commentairesSignales as $commentaire)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-medium">{{ $commentaire->user->prenom }} {{ $commentaire->user->nom }}</span>
                            <span class="text-sm text-gray-500">sur {{ $commentaire->offre->titre ?? 'une offre' }}</span>
                            <span class="badge badge-danger">{{ $commentaire->signalements_count }} signalement(s)</span>
                        </div>
                        <p class="text-gray-700 italic">"{{ $commentaire->contenu }}"</p>
                        <div class="mt-2 text-sm text-gray-500">{{ $commentaire->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="approuverCommentaire({{ $commentaire->id }})" class="btn-success">✓ Approuver</button>
                        <button @click="supprimerCommentaire({{ $commentaire->id }})" class="btn-danger">🗑️ Supprimer</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                ✅ Aucun commentaire signalé
            </div>
            @endforelse
        </div>
    </div>

    <!-- Messages signalés -->
    <div x-show="activeTab === 'messages'">
        <div class="space-y-4">
            @forelse($messagesSignales as $message)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-medium">{{ $message->expediteur->prenom }} {{ $message->expediteur->nom }}</span>
                            <span>→</span>
                            <span class="font-medium">{{ $message->destinataire->prenom }} {{ $message->destinataire->nom }}</span>
                            <span class="badge badge-danger">{{ $message->signalements_count }} signalement(s)</span>
                        </div>
                        <p class="text-gray-700">{{ $message->contenu }}</p>
                        <div class="mt-2 text-sm text-gray-500">{{ $message->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="supprimerMessage({{ $message->id }})" class="btn-danger">🗑️ Supprimer</button>
                        <button @click="avertir({{ $message->expediteur->id }})" class="btn-warning">⚠️ Avertir</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                ✅ Aucun message signalé
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function moderationManager() {
    return {
        activeTab: 'offres',
        
        approuverOffre(id) {
            fetch(`/admin/moderation/offres/${id}/approuver`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => location.reload());
        },
        
        rejeterOffre(id) {
            if(confirm('Supprimer définitivement cette offre ?')) {
                fetch(`/admin/offres/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => location.reload());
            }
        },
        
        modifierOffre(id) {
            window.location.href = `/admin/offres/${id}/edit`;
        },
        
        approuverCommentaire(id) {
            fetch(`/admin/moderation/commentaires/${id}/approuver`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => location.reload());
        },
        
        supprimerCommentaire(id) {
            if(confirm('Supprimer ce commentaire ?')) {
                fetch(`/admin/moderation/commentaires/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => location.reload());
            }
        },
        
        supprimerMessage(id) {
            if(confirm('Supprimer ce message ?')) {
                fetch(`/admin/moderation/messages/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => location.reload());
            }
        },
        
        avertir(userId) {
            fetch(`/admin/utilisateurs/${userId}/avertir`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => alert('Avertissement envoyé'));
        }
    }
}
</script>
@endpush
@endsection