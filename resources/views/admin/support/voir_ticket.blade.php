<x-admin-layout>
    @section('title', 'Détail du Ticket #' . $ticket->reference)

    <div class="max-w-5xl mx-auto space-y-6 animate-slide-up">
        <!-- Header / Navigation -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('admin.support') }}" class="text-gray-400 hover:text-acpe-blue transition-colors">Support</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                <span class="text-[#204263] font-bold">Ticket #{{ $ticket->reference }}</span>
            </div>
            <div class="flex items-center space-x-3">
                @if($ticket->statut === 'ouvert')
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase rounded-lg border border-blue-100">Ouvert</span>
                @elseif($ticket->statut === 'en_attente')
                    <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase rounded-lg border border-amber-100">En cours</span>
                @else
                    <span class="px-3 py-1 bg-gray-50 text-gray-600 text-[10px] font-black uppercase rounded-lg border border-gray-200">{{ ucfirst($ticket->statut) }}</span>
                @endif
                
                @if($ticket->statut !== 'resolu' && $ticket->statut !== 'ferme')
                <form action="{{ route('admin.support.ticket.close', $ticket->id) }}" method="POST" onsubmit="return confirm('Clôturer ce ticket ?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 text-xs font-bold rounded-xl shadow-sm hover:bg-emerald-100 transition-all">
                        Marquer comme résolu
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Conversation -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Ticket Message -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="h-10 w-10 rounded-full bg-acpe-blue text-white flex items-center justify-center font-bold text-xs shadow-lg">
                                {{ $ticket->user ? substr($ticket->user->prenom, 0, 1) . substr($ticket->user->nom, 0, 1) : 'AN' }}
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-[#204263]">{{ $ticket->user ? $ticket->user->prenom . ' ' . $ticket->user->nom : 'Anonyme' }}</h2>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Posté {{ $ticket->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($ticket->priorite === 'haute' || $ticket->priorite === 'urgente')
                            <span class="px-3 py-1 bg-red-50 text-red-500 text-[9px] font-black uppercase rounded">Priorité {{ ucfirst($ticket->priorite) }}</span>
                        @else
                            <span class="px-3 py-1 bg-blue-50 text-blue-500 text-[9px] font-black uppercase rounded">Priorité {{ ucfirst($ticket->priorite) }}</span>
                        @endif
                    </div>
                    <div class="p-8">
                        <h3 class="text-lg font-bold text-[#204263] mb-4">{{ $ticket->sujet }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $ticket->description }}</p>
                    </div>
                </div>

                <!-- Responses -->
                <div class="space-y-6 relative before:absolute before:left-5 before:top-0 before:bottom-0 before:w-0.5 before:bg-gray-100">
                    @foreach($ticket->messages as $msg)
                    <div class="relative pl-12">
                        <div class="absolute left-3 top-0 h-4 w-4 rounded-full {{ $msg->user && $msg->user->hasRole('admin') ? 'bg-acpe-blue' : 'bg-gray-400' }} border-4 border-white shadow-sm"></div>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs font-bold {{ $msg->user && $msg->user->hasRole('admin') ? 'text-acpe-blue' : 'text-gray-600' }}">
                                        {{ $msg->user ? ($msg->user->hasRole('admin') ? 'Support ACPE' : $msg->user->prenom . ' ' . $msg->user->nom) : 'Anonyme' }}
                                    </span>
                                    <span class="text-[9px] text-gray-300 uppercase font-black">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                @if($msg->user && $msg->user->hasRole('admin'))
                                    <i class="fa-solid fa-reply text-gray-200"></i>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 italic whitespace-pre-line">"{{ $msg->message }}"</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                @if($ticket->statut !== 'resolu' && $ticket->statut !== 'ferme')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="mb-4 flex items-center space-x-3">
                        <i class="fa-solid fa-paper-plane text-acpe-blue"></i>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Votre réponse</h3>
                    </div>
                    <form action="{{ route('admin.support.ticket.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <textarea name="message" rows="5" class="w-full p-4 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-acpe-blue/20 placeholder:text-gray-300" placeholder="Rédigez votre réponse ici..." required></textarea>
                        <div class="mt-4 flex items-center justify-end">
                            <button type="submit" class="px-8 py-3 bg-acpe-blue text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-acpe-dark-blue shadow-lg shadow-acpe-blue/10 transition-all">
                                Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="bg-gray-50 rounded-2xl p-6 text-center">
                    <p class="text-sm font-bold text-gray-400">Ce ticket est clôturé. Aucune réponse supplémentaire n'est possible.</p>
                </div>
                @endif
            </div>

            <!-- Right: Sidebar Info -->
            <div class="space-y-6">
                @if($ticket->user)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-black text-gray-300 uppercase tracking-widest mb-6">Informations Client</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center overflow-hidden">
                                @if($ticket->user->avatar)
                                    <img src="{{ asset('storage/' . $ticket->user->avatar) }}" class="h-full w-full object-cover">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#204263]">{{ $ticket->user->prenom }} {{ $ticket->user->nom }}</p>
                                <p class="text-[10px] text-gray-400">{{ $ticket->user->email }}</p>
                            </div>
                        </div>
                        <div class="pt-4 space-y-3">
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-gray-400 uppercase">Inscrit le</span>
                                <span class="text-[#204263]">{{ $ticket->user->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-gray-400 uppercase">Rôle</span>
                                <span class="text-acpe-orange">{{ ucfirst($ticket->user->getRoleNames()->first() ?? 'N/A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-black text-gray-300 uppercase tracking-widest mb-6">Actions rapides</h3>
                    <div class="space-y-3">
                        <button class="w-full py-3 text-left px-4 bg-gray-50 text-[#204263] text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all">Assigner à un agent</button>
                        <button class="w-full py-3 text-left px-4 bg-gray-50 text-[#204263] text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all">Transférer le ticket</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
