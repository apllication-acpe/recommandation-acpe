<x-admin-layout>
    @section('title', 'Centre de Support')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Centre de Support</h1>
                <p class="text-gray-400 text-sm">Gérez les demandes d'assistance et communiquez avec les utilisateurs.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.support.notifier') }}" class="px-6 py-2 bg-acpe-orange text-white text-sm font-bold rounded-xl shadow-lg shadow-acpe-orange/10 hover:bg-acpe-orange/90 transition-all">
                    <i class="fa-solid fa-bullhorn mr-2"></i> Diffuser une notification
                </a>
            </div>
        </div>

        <!-- Support Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tickets Ouverts</p>
                <h3 class="text-3xl font-black text-[#204263]">{{ $stats['ouverts'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">En attente</p>
                <h3 class="text-3xl font-black text-amber-500">{{ $stats['en_attente'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Priorité Haute</p>
                <h3 class="text-3xl font-black text-red-500">{{ $stats['priorite_haute'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total</p>
                <h3 class="text-3xl font-black text-emerald-500">{{ $tickets->total() }}</h3>
            </div>
        </div>

        <!-- Ticket List -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Dernières demandes</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($tickets as $ticket)
                <div class="px-8 py-6 flex flex-col md:flex-row md:items-center justify-between group hover:bg-gray-50/50 transition-all">
                    <div class="flex items-center space-x-6">
                        <div class="h-12 w-12 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center text-lg border border-gray-100">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-3 mb-1">
                                <h4 class="text-sm font-bold text-[#204263]">#{{ $ticket->reference }} - {{ $ticket->sujet }}</h4>
                                @if($ticket->priorite === 'haute' || $ticket->priorite === 'urgente')
                                    <span class="px-2 py-0.5 bg-red-50 text-red-500 text-[8px] font-black uppercase rounded">Priorité {{ ucfirst($ticket->priorite) }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-500 text-[8px] font-black uppercase rounded">Priorité {{ ucfirst($ticket->priorite) }}</span>
                                @endif

                                @if($ticket->statut === 'resolu' || $ticket->statut === 'ferme')
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[8px] font-black uppercase rounded">{{ ucfirst($ticket->statut) }}</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400 italic line-clamp-1">"{{ $ticket->description }}"</p>
                            <div class="mt-2 flex items-center space-x-4 text-[9px] font-bold text-gray-300 uppercase tracking-widest">
                                <span>Par {{ $ticket->user ? $ticket->user->prenom . ' ' . $ticket->user->nom : 'Anonyme' }}</span>
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center space-x-3">
                        <a href="{{ route('admin.support.ticket', $ticket->id) }}" class="px-4 py-2 bg-acpe-blue text-white text-[10px] font-black uppercase rounded-lg hover:bg-acpe-dark-blue transition-all">
                            Voir / Répondre
                        </a>
                        @if($ticket->statut !== 'resolu' && $ticket->statut !== 'ferme')
                        <form action="{{ route('admin.support.ticket.close', $ticket->id) }}" method="POST" class="inline" onsubmit="return confirm('Clôturer ce ticket ?');">
                            @csrf
                            <button type="submit" class="p-2 text-gray-300 hover:text-red-500 transition-colors" title="Marquer comme résolu">
                                <i class="fa-solid fa-check-double"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-8 py-12 text-center text-gray-500">
                    Aucun ticket de support pour le moment.
                </div>
                @endforelse
            </div>
            @if($tickets->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $tickets->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>