<x-admin-layout>
    @section('title', 'Envoyer une Notification')

    <div class="max-w-4xl mx-auto space-y-8 animate-slide-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#204263]">Envoyer une Notification</h1>
                <p class="text-gray-400 text-sm">Communiquez avec vos utilisateurs par notifications système ou email.</p>
            </div>
            <i class="fa-solid fa-bullhorn text-4xl text-gray-100 hidden md:block"></i>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="#" method="POST" class="p-8 space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Destinataires -->
                    <div class="space-y-4">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Destinataires</label>
                        <div class="grid grid-cols-1 gap-3">
                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20">
                                <input type="radio" name="target" value="all" class="sr-only" checked>
                                <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#204263]">Tous les utilisateurs</p>
                                    <p class="text-[10px] text-gray-400">Candidats + Recruteurs</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20">
                                <input type="radio" name="target" value="candidats" class="sr-only">
                                <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#204263]">Candidats uniquement</p>
                                    <p class="text-[10px] text-gray-400">Demandeurs d'emploi actifs</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all border-2 border-transparent has-[:checked]:border-acpe-blue/20">
                                <input type="radio" name="target" value="recruteurs" class="sr-only">
                                <div class="h-10 w-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#204263]">Recruteurs uniquement</p>
                                    <p class="text-[10px] text-gray-400">Entreprises et agents</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Titre de la notification</label>
                            <input type="text" name="title" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20" placeholder="Ex: Maintenance programmée">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Message</label>
                            <textarea name="content" rows="6" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-acpe-blue/20" placeholder="Décrivez votre message ici..."></textarea>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="checkbox" name="send_email" class="rounded text-acpe-blue focus:ring-acpe-blue/20">
                                <span class="text-xs text-gray-500 font-bold group-hover:text-[#204263] transition-colors">Envoyer aussi par email</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-50 flex justify-end">
                    <button type="submit" class="px-12 py-4 bg-acpe-blue text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-acpe-dark-blue shadow-xl shadow-acpe-blue/10 transition-all flex items-center">
                        <i class="fa-solid fa-paper-plane mr-3 opacity-50"></i> Diffuser la notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
