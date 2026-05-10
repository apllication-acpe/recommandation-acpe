<x-admin-layout>
    @section('title', 'Matrice des Accès')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.roles') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263] tracking-tight">Matrice des Accès</h1>
                    <p class="text-gray-400 text-sm">Visualisation croisée des privilèges par profil utilisateur.</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg border border-emerald-100">Synchronisé</span>
            </div>
        </div>

        <!-- Matrix Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="sticky left-0 bg-gray-50/50 px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest border-r border-gray-100">Permissions / Rôles</th>
                            @foreach($roles as $role)
                                <th class="px-8 py-6 text-center">
                                    <div class="flex flex-col items-center space-y-2">
                                        <div class="h-10 w-10 rounded-xl bg-[#204263] text-white flex items-center justify-center text-sm shadow-lg shadow-blue-900/20">
                                            <i class="fa-solid {{ match($role->name) { 'admin' => 'fa-user-shield', 'recruteur' => 'fa-briefcase', 'demandeur' => 'fa-user-graduate', default => 'fa-user' } }}"></i>
                                        </div>
                                        <span class="text-[11px] font-black text-[#204263] uppercase tracking-wider">{{ $role->name }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($permissions as $permission)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="sticky left-0 bg-white group-hover:bg-gray-50/30 px-8 py-5 border-r border-gray-50">
                                <div class="flex items-center space-x-3">
                                    <div class="h-2 w-2 bg-gray-200 rounded-full"></div>
                                    <span class="text-xs font-bold text-gray-500 group-hover:text-[#204263] transition-colors">{{ str_replace('_', ' ', $permission->name) }}</span>
                                </div>
                            </td>
                            @foreach($roles as $role)
                                <td class="px-8 py-5 text-center">
                                    @if($role->hasPermissionTo($permission->name))
                                        <div class="inline-flex h-8 w-8 bg-emerald-50 text-emerald-500 rounded-full items-center justify-center border border-emerald-100 shadow-sm animate-bounce-subtle">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </div>
                                    @else
                                        <div class="inline-flex h-8 w-8 bg-gray-50 text-gray-200 rounded-full items-center justify-center border border-gray-100 italic">
                                            <i class="fa-solid fa-minus text-[10px]"></i>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex items-center justify-center space-x-8 py-4 px-8 bg-gray-50/50 rounded-2xl border border-gray-100 inline-flex mx-auto">
            <div class="flex items-center space-x-3">
                <div class="h-4 w-4 bg-emerald-50 text-emerald-500 rounded flex items-center justify-center border border-emerald-100"><i class="fa-solid fa-check text-[8px]"></i></div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-nowrap">Droit Accordé</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="h-4 w-4 bg-gray-50 text-gray-200 rounded flex items-center justify-center border border-gray-100"><i class="fa-solid fa-minus text-[8px]"></i></div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-nowrap">Droit Refusé</span>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }
    </style>
</x-admin-layout>
