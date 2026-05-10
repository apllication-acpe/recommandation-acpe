<x-admin-layout>
    @section('title', 'Envoyer une Notification')

    <div class="max-w-4xl mx-auto space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.support') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-acpe-blue transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-[#204263] tracking-tight">Diffuser une Notification</h1>
                    <p class="text-gray-400 text-sm mt-1">Communiquez avec vos {{ $counts['all'] }} utilisateurs en temps réel.</p>
                </div>
            </div>
            <i class="fa-solid fa-bullhorn text-4xl text-gray-100 hidden md:block"></i>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative">
            <!-- Background Accent -->
            <div class="absolute top-0 right-0 h-40 w-40 bg-acpe-blue/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            
            <form action="{{ route('admin.support.notifier.send') }}" method="POST" class="p-10 space-y-10 relative">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Destinataires -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cible de diffusion</label>
                            <span class="text-[9px] font-black text-acpe-orange bg-orange-50 px-2 py-0.5 rounded-md uppercase">Important</span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <label class="flex items-center p-5 bg-gray-50/50 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20 has-[:checked]:bg-white group">
                                <input type="radio" name="target" value="all" class="sr-only" checked>
                                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center mr-5 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-[#204263]">Tous les utilisateurs</p>
                                    <p class="text-[10px] text-gray-400 font-bold">{{ $counts['all'] }} membres actifs</p>
                                </div>
                            </label>

                            <label class="flex items-center p-5 bg-gray-50/50 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20 has-[:checked]:bg-white group">
                                <input type="radio" name="target" value="candidats" class="sr-only">
                                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mr-5 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-[#204263]">Candidats uniquement</p>
                                    <p class="text-[10px] text-gray-400 font-bold">{{ $counts['candidats'] }} talents enregistrés</p>
                                </div>
                            </label>

                            <label class="flex items-center p-5 bg-gray-50/50 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20 has-[:checked]:bg-white group">
                                <input type="radio" name="target" value="recruteurs" class="sr-only">
                                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mr-5 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-[#204263]">Recruteurs uniquement</p>
                                    <p class="text-[10px] text-gray-400 font-bold">{{ $counts['recruteurs'] }} entreprises</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="space-y-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sujet de la notification</label>
                            <input type="text" name="title" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-[#204263] focus:ring-2 focus:ring-acpe-blue/20 placeholder:text-gray-300" placeholder="Ex: Maintenance du système le 15/05">
                        </div>
                        
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Contenu du message</label>
                            <textarea name="content" rows="6" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-medium text-gray-600 focus:ring-2 focus:ring-acpe-blue/20 placeholder:text-gray-300 leading-relaxed" placeholder="Détaillez ici l'objet de votre communication..."></textarea>
                        </div>

                        <div class="p-6 bg-blue-50/30 rounded-[1.5rem] border border-blue-50">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="send_email" class="h-5 w-5 rounded-md border-gray-200 text-acpe-blue focus:ring-acpe-blue/20">
                                </div>
                                <div>
                                    <span class="text-xs text-[#204263] font-black uppercase tracking-tighter">Doubler par un envoi Email</span>
                                    <p class="text-[9px] text-gray-400 font-bold">Un mail sera envoyé individuellement à chaque destinataire.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-10 border-t border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <p class="text-[10px] text-gray-400 font-bold italic max-w-xs leading-tight">
                        <i class="fa-solid fa-circle-info mr-1 text-acpe-blue opacity-50"></i>
                        Les notifications système sont instantanées et apparaîtront dans le centre de notifications de l'utilisateur.
                    </p>
                    <button type="submit" class="px-10 py-4 bg-acpe-blue text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-acpe-dark-blue shadow-2xl shadow-blue-900/20 hover:scale-105 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane mr-3 opacity-50"></i> Diffuser la notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
