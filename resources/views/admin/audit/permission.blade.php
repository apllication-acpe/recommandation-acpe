<x-admin-layout>
    @section('title', 'Audit des Permissions')

    <div class="space-y-6 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Audit des Permissions</h1>
                <p class="text-gray-400 text-sm">Suivez les modifications de droits d'accès et les attributions de rôles.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.audit.permissions.export') }}" class="px-4 py-2 bg-white border border-gray-100 text-[#204263] text-sm font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    Exporter l'audit
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <i class="fa-solid fa-user-shield absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                        <option value="">Tous les rôles</option>
                        <option value="admin">Administrateur</option>
                        <option value="recruteur">Recruteur</option>
                        <option value="candidat">Candidat</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20">
                        <option value="7">7 derniers jours</option>
                        <option value="30">30 derniers jours</option>
                        <option value="90">3 derniers mois</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 bg-acpe-blue text-white text-sm font-bold rounded-xl hover:bg-acpe-dark-blue transition-all">
                        Filtrer les résultats
                    </button>
                    <button onclick="window.location.href=window.location.pathname" class="px-4 py-2 text-gray-400 text-sm hover:text-gray-600 font-bold transition-all">
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Audit Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date / Heure</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Administrateur</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Cible</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Détails</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php
                            $logs = [
                                ['date' => '02/05/2026 04:30', 'admin' => 'Admin Principal', 'action' => 'Attribution Rôle', 'target' => 'Marc Dupont', 'detail' => 'Ajout du rôle "Modérateur"'],
                                ['date' => '01/05/2026 18:15', 'admin' => 'Admin Principal', 'action' => 'Modification Permission', 'target' => 'Recruteurs', 'detail' => 'Retrait du droit "Suppression d\'offres"'],
                                ['date' => '01/05/2026 09:42', 'admin' => 'Système', 'action' => 'Auto-Update', 'target' => 'Permissions API', 'detail' => 'Mise à jour des tokens de sécurité'],
                            ];
                        @endphp

                        @foreach($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-medium text-gray-500">{{ $log['date'] }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center text-[8px] font-black">{{ substr($log['admin'], 0, 1) }}</div>
                                    <span class="text-xs font-bold text-[#204263]">{{ $log['admin'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black uppercase rounded">{{ $log['action'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-[#204263] font-medium">{{ $log['target'] }}</td>
                            <td class="px-6 py-4 text-[10px] text-gray-400 italic">{{ $log['detail'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
