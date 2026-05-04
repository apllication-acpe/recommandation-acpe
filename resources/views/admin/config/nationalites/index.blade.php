<x-admin-layout>
    @section('title', 'Référentiel des Nationalités')

    <div class="space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">Nationalités</h1>
                    <p class="text-gray-400 text-sm">Gérez la liste des pays et codes ISO.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.nationalites.create') }}" class="px-6 py-2.5 bg-purple-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-purple-500/20 hover:scale-105 transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter un pays
            </a>

        </div>

        <!-- List -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Pays / Nationalité</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Code ISO</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($nationalites as $nat)
                        <tr class="hover:bg-gray-50/30 transition-all">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($nat->libelle, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-black text-[#204263] uppercase tracking-tight">{{ $nat->libelle }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-black rounded-lg uppercase">{{ $nat->code_iso }}</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.config.nationalites.edit', $nat) }}" class="h-9 w-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.config.nationalites.destroy', $nat) }}" method="POST" onsubmit="return confirm('Supprimer ce pays ?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="h-9 w-9 bg-red-50 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-12 text-center text-gray-400 italic">Aucune donnée disponible.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


</x-admin-layout>
