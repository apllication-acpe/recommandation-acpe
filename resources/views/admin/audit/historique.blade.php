<x-admin-layout>
    @section('title', 'Historique d\'Audit')

    <div class="space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Historique d'Audit</h1>
                <p class="text-gray-400 text-sm">Traçabilité complète des actions effectuées par les administrateurs.</p>
            </div>
            <button class="px-6 py-2 bg-white border border-gray-100 text-[#204263] text-sm font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-file-export mr-2"></i> Exporter l'historique
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                        <option value="">Tous les administrateurs</option>
                        <option value="1">Admin Principal</option>
                        <option value="2">Modérateur #1</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                        <option value="">Toutes les actions</option>
                        <option value="create">Création</option>
                        <option value="update">Modification</option>
                        <option value="delete">Suppression</option>
                        <option value="auth">Connexion</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="date" class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 bg-acpe-blue text-white text-sm font-bold rounded-xl hover:bg-acpe-dark-blue transition-all">
                        Rechercher
                    </button>
                    <button onclick="window.location.href=window.location.pathname" class="px-4 py-2 text-gray-400 text-sm hover:text-gray-600 font-bold transition-all">
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-10">
                <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:ml-[8.75rem] md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-100 before:to-transparent">
                    
                    <!-- Audit Item -->
                    @php
                        $audits = [
                            ['user' => 'Admin Principal', 'action' => 'Update', 'target' => 'Entreprise "Global Tech"', 'time' => 'Il y a 10 min', 'color' => 'blue'],
                            ['user' => 'Admin Principal', 'action' => 'Delete', 'target' => 'Offre #882', 'time' => 'Il y a 45 min', 'color' => 'red'],
                            ['user' => 'Modérateur #1', 'action' => 'Create', 'target' => 'Nouveau Candidat', 'time' => 'Il y a 2h', 'color' => 'emerald'],
                            ['user' => 'Admin Principal', 'action' => 'Auth', 'target' => 'Connexion réussie', 'time' => 'Il y a 5h', 'color' => 'purple'],
                            ['user' => 'Modérateur #1', 'action' => 'Update', 'target' => 'Paramètres SEO', 'time' => 'Hier à 18:30', 'color' => 'blue'],
                        ];
                    @endphp

                    @foreach($audits as $audit)
                    <div class="relative">
                        <div class="md:flex items-center md:space-x-4 mb-3">
                            <div class="flex items-center space-x-4 md:w-32">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white border-4 border-gray-50 shadow-sm z-10">
                                    <div class="w-2 h-2 rounded-full bg-{{ $audit['color'] }}-500"></div>
                                </div>
                                <div class="text-[10px] font-black text-gray-300 uppercase tracking-widest md:hidden">{{ $audit['time'] }}</div>
                            </div>
                            <div class="text-[10px] font-black text-gray-300 uppercase tracking-widest hidden md:block w-24">{{ $audit['time'] }}</div>
                            <div class="flex-1 bg-gray-50/50 p-4 rounded-2xl border border-gray-50 group hover:border-{{ $audit['color'] }}-200 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-{{ $audit['color'] }}-50 text-{{ $audit['color'] }}-600 flex items-center justify-center font-bold text-[10px]">
                                            {{ substr($audit['user'], 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-[#204263]">
                                                <span class="text-{{ $audit['color'] }}-600 uppercase text-[9px] tracking-widest mr-2">{{ $audit['action'] }}</span>
                                                {{ $audit['user'] }}
                                            </p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Sur : <span class="font-bold">{{ $audit['target'] }}</span></p>
                                        </div>
                                    </div>
                                    <button class="p-2 text-gray-300 hover:text-gray-500 opacity-0 group-hover:opacity-100 transition-all">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
            
            <div class="px-8 py-4 bg-gray-50/30 text-center">
                <button class="text-[10px] font-black text-[#7a9bb8] uppercase tracking-widest hover:underline">Charger plus d'activité</button>
            </div>
        </div>
    </div>
</x-admin-layout>
