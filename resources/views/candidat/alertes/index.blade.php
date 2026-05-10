<x-candidat-layout>
    @section('title', 'Alertes Emploi')

    <div class="max-w-4xl mx-auto space-y-8 animate-slide-up" x-data="{ showModal: false }">
        
        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 text-xs font-bold mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Alertes Emploi</h1>
                <p class="text-xs font-medium text-gray-400 mt-1 uppercase tracking-widest">Soyez le premier informé des nouvelles opportunités.</p>
            </div>
            <button @click="showModal = true" class="bg-acpe-orange text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">
                + Nouvelle alerte
            </button>
        </div>

        <div class="space-y-4">
            @forelse($alertes as $alerte)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between group" id="alerte-{{ $alerte->id }}">
                    <div class="flex items-center space-x-6">
                        <div class="h-12 w-12 rounded-xl transition-colors {{ $alerte->active ? 'bg-orange-50 text-acpe-orange' : 'bg-gray-50 text-gray-300' }} flex items-center justify-center text-xl alerte-icon">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-[#204263]">{{ $alerte->titre }}</h3>
                            <div class="flex items-center space-x-3 mt-1 flex-wrap gap-y-2">
                                @if($alerte->lieu)
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded">{{ $alerte->lieu }}</span>
                                @endif
                                @if($alerte->secteur)
                                    <span class="text-[9px] font-bold text-acpe-light-blue uppercase tracking-widest bg-blue-50 px-2 py-1 rounded">{{ $alerte->secteur->libelle }}</span>
                                @endif
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Fréquence : {{ ucfirst($alerte->frequence) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div @click="toggleAlerte({{ $alerte->id }}, $el)" 
                             class="h-6 w-11 transition-colors {{ $alerte->active ? 'bg-emerald-400' : 'bg-gray-200' }} rounded-full relative cursor-pointer shadow-inner alerte-toggle">
                            <div class="absolute {{ $alerte->active ? 'right-0.5' : 'left-0.5' }} top-0.5 h-5 w-5 bg-white rounded-full shadow-sm transition-all alerte-dot"></div>
                        </div>
                        <form action="{{ route('candidat.alertes.destroy', $alerte->id) }}" method="POST" onsubmit="return confirm('Supprimer cette alerte ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-400 transition-colors">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-gray-200">
                    <div class="h-16 w-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-bell-slash text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-black text-[#204263]">Aucune alerte active</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase">Créez votre première alerte pour ne rien rater.</p>
                </div>
            @endforelse
        </div>

        <!-- Modal Nouvelle Alerte -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#204263]/60 backdrop-blur-sm" x-cloak>
            <div @click.away="showModal = false" class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-slide-up">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h2 class="text-lg font-black text-[#204263]">Créer une alerte emploi</h2>
                    <button @click="showModal = false" class="text-gray-400 hover:text-acpe-blue">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('candidat.alertes.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nom de l'alerte</label>
                        <input type="text" name="titre" required placeholder="Ex: Développeur PHP Dakar" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mots-clés</label>
                            <input type="text" name="mots_cles" placeholder="Ex: Laravel, VueJS" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Lieu</label>
                            <input type="text" name="lieu" placeholder="Ex: Dakar, Télétravail" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Secteur d'activité</label>
                        <select name="id_sect_act" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner appearance-none">
                            <option value="">Tous les secteurs</option>
                            @foreach($secteurs as $secteur)
                                <option value="{{ $secteur->id_sect_act }}">{{ $secteur->libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type de contrat</label>
                            <select name="id_type_cont" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner appearance-none">
                                <option value="">Tous les contrats</option>
                                @foreach($typeContrats as $tc)
                                    <option value="{{ $tc->id_type_cont }}">{{ $tc->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fréquence</label>
                            <select name="frequence" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner appearance-none">
                                <option value="immediate">Immédiate</option>
                                <option value="quotidienne" selected>Quotidienne</option>
                                <option value="hebdomadaire">Hebdomadaire</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 flex space-x-3">
                        <button type="button" @click="showModal = false" class="flex-1 px-6 py-3 bg-gray-100 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">Créer l'alerte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleAlerte(id, el) {
            fetch(`/candidat/alertes/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const dot = el.querySelector('.alerte-dot');
                    const card = document.getElementById(`alerte-${id}`);
                    const icon = card.querySelector('.alerte-icon');

                    if (data.active) {
                        el.classList.remove('bg-gray-200');
                        el.classList.add('bg-emerald-400');
                        dot.classList.remove('left-0.5');
                        dot.classList.add('right-0.5');
                        icon.classList.remove('bg-gray-50', 'text-gray-300');
                        icon.classList.add('bg-orange-50', 'text-acpe-orange');
                    } else {
                        el.classList.add('bg-gray-200');
                        el.classList.remove('bg-emerald-400');
                        dot.classList.add('left-0.5');
                        dot.classList.remove('right-0.5');
                        icon.classList.add('bg-gray-50', 'text-gray-300');
                        icon.classList.remove('bg-orange-50', 'text-acpe-orange');
                    }

                    // Toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
        }
    </script>
    @endpush
</x-candidat-layout>
