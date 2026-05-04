<x-candidat-layout>
    @section('title', 'Messagerie')

    <div class="h-[calc(100vh-140px)] flex bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
        
        <!-- Left: Conversations List -->
        <div class="w-80 border-r border-gray-100 flex flex-col">
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-xl font-bold text-[#204263] mb-4">Messages</h1>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                    <input type="text" placeholder="Chercher un contact..." class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-xs focus:ring-acpe-orange shadow-inner placeholder:text-gray-300">
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @forelse($chats as $userId => $chat)
                    <a href="{{ route('candidat.messagerie', ['user_id' => $userId]) }}" 
                       class="px-6 py-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 transition-all {{ $activeUserId == $userId ? 'bg-orange-50/50 border-r-4 border-acpe-orange' : '' }}">
                        <div class="relative">
                            <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-[#204263] font-black text-xs border border-gray-100 shadow-sm overflow-hidden">
                                @if($chat['user']->avatar)
                                    <img src="{{ asset('storage/' . $chat['user']->avatar) }}" class="h-full w-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($chat['user']->nom_complet) }}&background=random" class="h-full w-full">
                                @endif
                            </div>
                            @if($chat['unread_count'] > 0)
                                <div class="absolute -top-1 -right-1 h-3 w-3 bg-acpe-orange rounded-full border-2 border-white"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h3 class="text-xs font-bold text-[#204263] truncate">{{ $chat['user']->nom_complet }}</h3>
                                <span class="text-[9px] font-bold text-gray-300 uppercase">{{ $chat['last_message']->created_at->diffForHumans(null, true) }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400 truncate">{{ $chat['last_message']->contenu }}</p>
                        </div>
                    </a>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-xs font-bold text-gray-300 italic">Aucune conversation</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Chat Window -->
        <div class="flex-1 flex flex-col bg-gray-50/30">
            @if($activeChat)
                <!-- Header -->
                <div class="px-8 py-4 bg-white border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center text-[#204263] font-black text-sm border border-gray-100 overflow-hidden">
                            @if($activeChat['user']->avatar)
                                <img src="{{ asset('storage/' . $activeChat['user']->avatar) }}" class="h-full w-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($activeChat['user']->nom_complet) }}&background=random" class="h-full w-full">
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#204263]">{{ $activeChat['user']->nom_complet }}</h3>
                            <p class="text-[10px] font-bold text-acpe-light-blue uppercase">Contact Direct</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="p-2 text-gray-300 hover:text-acpe-blue transition-colors">
                            <i class="fa-solid fa-phone-flip text-xs"></i>
                        </button>
                        <button class="p-2 text-gray-300 hover:text-gray-500 transition-colors">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 p-8 overflow-y-auto space-y-6 custom-scrollbar" id="messages-container">
                    @foreach($activeMessages as $message)
                        @if($message->sender_id === Auth::id())
                            <!-- Me -->
                            <div class="flex flex-col items-end max-w-[80%] ml-auto">
                                <div class="bg-[#204263] p-4 rounded-2xl rounded-tr-none shadow-lg shadow-blue-900/10">
                                    <p class="text-xs text-white leading-relaxed">
                                        {{ $message->contenu }}
                                    </p>
                                    @if($message->piece_jointe_path)
                                        <div class="mt-3 p-3 bg-white/10 rounded-xl border border-white/10 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <i class="fa-solid fa-file-arrow-down text-white/60 text-lg"></i>
                                                <span class="text-[10px] font-bold text-white/80">Pièce jointe</span>
                                            </div>
                                            <a href="{{ $message->piece_jointe_url }}" target="_blank" class="h-8 w-8 bg-white/20 rounded-lg flex items-center justify-center text-white hover:bg-white/30 transition-colors">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                        </div>
                                    @endif
                                    <p class="text-[9px] font-bold text-white/40 mt-2 text-right">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <!-- Other -->
                            <div class="flex flex-col items-start max-w-[80%]">
                                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                                    <p class="text-xs text-[#204263] leading-relaxed">
                                        {{ $message->contenu }}
                                    </p>
                                    @if($message->piece_jointe_path)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                                                <span class="text-[10px] font-bold text-[#204263]">Pièce jointe</span>
                                            </div>
                                            <a href="{{ $message->piece_jointe_url }}" target="_blank" class="h-8 w-8 bg-white rounded-lg flex items-center justify-center text-gray-400 hover:text-acpe-blue shadow-sm transition-colors">
                                                <i class="fa-solid fa-download text-xs"></i>
                                            </a>
                                        </div>
                                    @endif
                                    <p class="text-[9px] font-bold text-gray-300 mt-2 text-right">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="p-6 bg-white border-t border-gray-100" x-data="{ fileName: '' }">
                    @if(session('success'))
                        <div class="mb-4 text-[10px] font-bold text-emerald-500 text-center">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('messagerie.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col space-y-4">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeUserId }}">
                        <input type="hidden" name="objet" value="Message depuis le chat">
                        
                        <div x-show="fileName" class="px-4 py-2 bg-blue-50 text-acpe-blue rounded-xl text-[10px] font-bold flex items-center justify-between animate-fade-in" x-cloak>
                            <span x-text="fileName"></span>
                            <button type="button" @click="fileName = ''; $refs.fileInput.value = ''" class="text-gray-400 hover:text-red-500">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="p-3 text-gray-300 hover:text-acpe-blue transition-colors cursor-pointer relative">
                                <i class="fa-solid fa-paperclip text-lg"></i>
                                <input type="file" name="piece_jointe" x-ref="fileInput" @change="fileName = $event.target.files[0].name" class="hidden">
                            </label>
                            <div class="flex-1 relative">
                                <input type="text" name="contenu" required placeholder="Tapez votre message..." class="w-full pl-4 pr-12 py-3 bg-gray-50 border-none rounded-2xl text-xs focus:ring-acpe-orange shadow-inner">
                                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 h-8 w-8 bg-acpe-orange text-white rounded-xl flex items-center justify-center hover:bg-orange-500 transition-colors shadow-lg shadow-orange-500/20">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center p-10">
                    <div class="h-24 w-24 bg-gray-50 rounded-3xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-comments text-4xl text-gray-200"></i>
                    </div>
                    <h2 class="text-lg font-bold text-[#204263]">Sélectionnez une conversation</h2>
                    <p class="text-xs text-gray-400 mt-2 max-w-xs mx-auto">Choisissez un contact dans la liste à gauche pour commencer à discuter.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Scroll to bottom of messages
        const container = document.getElementById('messages-container');
        if(container) {
            container.scrollTop = container.scrollHeight;
        }
    </script>
</x-candidat-layout>
