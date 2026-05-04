<x-dynamic-component :component="Auth::user()->hasRole('demandeur') ? 'candidat-layout' : 'admin-layout'">
    @section('title', 'Messagerie')

    <div class="max-w-6xl mx-auto animate-slide-up" x-data="{ tab: 'inbox' }">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-black text-[#204263]">Messagerie</h1>
            <a href="{{ route('messagerie.create') }}" class="px-6 py-2.5 bg-acpe-orange text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">
                Nouveau message
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[600px] flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div class="w-full md:w-64 border-r border-gray-50 bg-gray-50/30 p-6 space-y-2">
                <button @click="tab = 'inbox'" :class="tab === 'inbox' ? 'bg-white text-acpe-blue shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    <i class="fa-solid fa-inbox text-sm"></i>
                    <span>Boîte de réception</span>
                    @php $unread = Auth::user()->unreadMessagesCount(); @endphp
                    @if($unread > 0)
                        <span class="ml-auto bg-acpe-orange text-white h-5 w-5 flex items-center justify-center rounded-full text-[8px]">{{ $unread }}</span>
                    @endif
                </button>
                <button @click="tab = 'sent'" :class="tab === 'sent' ? 'bg-white text-acpe-blue shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                    <span>Messages envoyés</span>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 p-8">
                <!-- Inbox -->
                <div x-show="tab === 'inbox'" class="space-y-4">
                    <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Messages reçus</h2>
                    @forelse($receivedMessages as $message)
                        <a href="{{ route('messagerie.show', $message) }}" class="flex items-center p-4 rounded-2xl border {{ $message->lu_at ? 'bg-white border-gray-50' : 'bg-blue-50/30 border-blue-100 shadow-sm' }} hover:scale-[1.01] transition-all group">
                            <div class="h-10 w-10 rounded-full bg-gray-100 overflow-hidden flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->nom_complet) }}&background=random" alt="">
                            </div>
                            <div class="ml-4 flex-1 overflow-hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="text-xs font-black text-[#204263] uppercase truncate">{{ $message->sender->nom_complet }}</h4>
                                    <span class="text-[8px] font-bold text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[10px] font-bold text-acpe-blue truncate">{{ $message->objet }}</p>
                                <p class="text-[10px] text-gray-400 truncate mt-1">{{ Str::limit($message->contenu, 80) }}</p>
                            </div>
                            <div class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-chevron-right text-gray-300 text-[10px]"></i>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-20">
                            <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-comment-slash text-2xl text-gray-200"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-300 italic">Aucun message pour le moment</p>
                        </div>
                    @endforelse
                    <div class="mt-6">
                        {{ $receivedMessages->links() }}
                    </div>
                </div>

                <!-- Sent -->
                <div x-show="tab === 'sent'" x-cloak class="space-y-4">
                    <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Messages envoyés</h2>
                    @forelse($sentMessages as $message)
                        <a href="{{ route('messagerie.show', $message) }}" class="flex items-center p-4 rounded-2xl border bg-white border-gray-50 hover:scale-[1.01] transition-all group">
                            <div class="h-10 w-10 rounded-full bg-gray-100 overflow-hidden flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($message->receiver->nom_complet) }}&background=random" alt="">
                            </div>
                            <div class="ml-4 flex-1 overflow-hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="text-xs font-black text-gray-400 uppercase truncate">À: {{ $message->receiver->nom_complet }}</h4>
                                    <span class="text-[8px] font-bold text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[10px] font-bold text-[#204263] truncate">{{ $message->objet }}</p>
                                <p class="text-[10px] text-gray-400 truncate mt-1">{{ Str::limit($message->contenu, 80) }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-20">
                            <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-paper-plane text-2xl text-gray-200"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-300 italic">Vous n'avez pas encore envoyé de message</p>
                        </div>
                    @endforelse
                    <div class="mt-6">
                        {{ $sentMessages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
