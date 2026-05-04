<x-admin-layout>
    @section('title', 'Détails de la Qualification')

    <div class="max-w-4xl mx-auto animate-slide-up">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.config.qualifications') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263]">{{ $qualification->intitule }}</h1>
                    <p class="text-gray-400 text-sm">Niveau de qualification académique.</p>
                </div>
            </div>
            <a href="{{ route('admin.config.qualifications.edit', $qualification) }}" class="px-6 py-2.5 bg-yellow-50 text-yellow-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-yellow-600 hover:text-white transition-all">
                <i class="fa-solid fa-pen mr-2"></i> Modifier
            </a>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-8">
                <div class="h-20 w-20 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i class="fa-solid fa-graduation-cap text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-[#204263] uppercase tracking-tight">{{ $qualification->intitule }}</h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Niveau : {{ $qualification->niveau ?: 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <form action="{{ route('admin.config.qualifications.destroy', $qualification) }}" method="POST" onsubmit="return confirm('Supprimer cette qualification ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2.5 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                    <i class="fa-solid fa-trash mr-2"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
