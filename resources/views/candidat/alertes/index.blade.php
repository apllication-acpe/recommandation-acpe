<x-candidat-layout>
    @section('title', 'Alertes Emploi')

    <div class="max-w-4xl mx-auto space-y-8 animate-slide-up">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-[#204263]">Alertes Emploi</h1>
                <p class="text-xs font-medium text-gray-400 mt-1 uppercase tracking-widest">Soyez le premier informé des nouvelles opportunités.</p>
            </div>
            <button class="bg-acpe-orange text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">
                + Nouvelle alerte
            </button>
        </div>

        <div class="space-y-4">
            @php
                $alertes = [
                    ['title' => 'Développeur PHP / Laravel', 'loc' => 'Dakar', 'freq' => 'Quotidien', 'active' => true],
                    ['title' => 'UI/UX Designer', 'loc' => 'Sénégal (Télétravail)', 'freq' => 'Hebdomadaire', 'active' => false],
                ];
            @endphp

            @foreach($alertes as $alerte)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between group">
                    <div class="flex items-center space-x-6">
                        <div class="h-12 w-12 rounded-xl @if($alerte['active']) bg-orange-50 text-acpe-orange @else bg-gray-50 text-gray-300 @endif flex items-center justify-center text-xl">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-[#204263]">{{ $alerte['title'] }}</h3>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $alerte['loc'] }}</span>
                                <span class="h-1 w-1 bg-gray-300 rounded-full"></span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Fréquence : {{ $alerte['freq'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="h-6 w-11 @if($alerte['active']) bg-emerald-400 @else bg-gray-200 @endif rounded-full relative cursor-pointer transition-colors shadow-inner">
                            <div class="absolute @if($alerte['active']) right-0.5 @else left-0.5 @endif top-0.5 h-5 w-5 bg-white rounded-full shadow-sm transition-all"></div>
                        </div>
                        <button class="text-gray-300 hover:text-red-400 transition-colors">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Notification Settings -->
        <div class="bg-[#204263] p-8 rounded-3xl shadow-xl text-white mt-12 relative overflow-hidden">
            <i class="fa-solid fa-envelope-open-text absolute -right-4 -bottom-4 text-7xl text-white/5 transform -rotate-12"></i>
            <h3 class="text-sm font-black uppercase tracking-widest mb-4">Préférences de notification</h3>
            <div class="space-y-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <div class="h-5 w-5 bg-white/10 rounded flex items-center justify-center border border-white/20">
                        <i class="fa-solid fa-check text-[10px] text-white"></i>
                    </div>
                    <span class="text-xs font-medium text-white/80">Recevoir les recommandations par email</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <div class="h-5 w-5 bg-white/10 rounded flex items-center justify-center border border-white/20">
                    </div>
                    <span class="text-xs font-medium text-white/80">Notifications push sur navigateur</span>
                </label>
            </div>
        </div>
    </div>
</x-candidat-layout>
