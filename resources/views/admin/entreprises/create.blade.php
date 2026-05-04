@extends('layouts.admin')

@section('title', 'Nouvelle Entreprise')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-slide-up">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-[#204263]">Ajouter une entreprise</h1>
            <p class="text-gray-400 text-xs mt-1 font-medium">Enregistrez une nouvelle entreprise partenaire.</p>
        </div>
        <a href="{{ route('admin.entreprises') }}" class="px-6 py-2.5 bg-white border border-gray-100 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm">
            Annuler
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.entreprises.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Raison Sociale</label>
                    <input type="text" name="raison_sociale" placeholder="Ex: Tech Solutions S.A." class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email de contact</label>
                    <input type="email" name="email_contact" placeholder="contact@entreprise.com" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Téléphone</label>
                    <input type="text" name="telephone" placeholder="Ex: +242 06 000 0000" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Secteur principal</label>
                    <input type="text" name="secteur_activite" placeholder="Ex: Technologie, BTP..." class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold text-[#204263] px-4 py-3 focus:ring-acpe-orange shadow-inner">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Description de l'entreprise</label>
                <textarea name="description" rows="5" placeholder="Brève présentation de l'entreprise..." class="w-full bg-gray-50 border-none rounded-2xl text-sm font-medium text-[#204263] p-6 focus:ring-acpe-orange shadow-inner"></textarea>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-10 py-4 bg-[#204263] text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 hover:scale-105 transition-all">
                    Enregistrer l'entreprise
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
