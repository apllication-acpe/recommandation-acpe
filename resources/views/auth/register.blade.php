<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - ACPE Reco</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 antialiased h-screen overflow-hidden flex flex-col font-sans justify-center items-center px-4 sm:px-6 lg:px-8">

    <div class="max-w-xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Top Header -->
        <div class="bg-[#7a9bb8] border-b-[4px] border-[#e3af8b] px-6 py-6 text-center relative">
            <div class="flex items-center justify-center space-x-2 text-white mb-1">
                <i class="fa-solid fa-user-plus text-xl"></i>
                <h2 class="text-xl font-bold tracking-wide">Créer un compte</h2>
            </div>
            <p class="text-white text-xs font-semibold tracking-wide">Rejoignez l'Agence Congolaise Pour l'Emploi</p>
        </div>

        <!-- Formulaire -->
        <div class="px-8 py-6">
            <form method="POST" action="{{ route('register') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                
                <!-- Gestion des erreurs -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-xs px-3 py-2 rounded-lg mb-4 font-medium">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Role Candidat (Hidden) -->
                <input type="hidden" name="role" value="candidat">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-xs font-bold text-[#3b5266] mb-1">Nom</label>
                        <div class="flex rounded-md border border-[#c7d2db] shadow-sm focus-within:border-[#7a9bb8] focus-within:ring-1 focus-within:ring-[#7a9bb8] transition-all bg-white">
                            <span class="inline-flex items-center px-3 rounded-l-md border-r border-[#c7d2db] text-[#8299b1] bg-[#f8fafc]">
                                <i class="fa-solid fa-id-card text-sm"></i>
                            </span>
                            <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required autofocus
                                class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-0 focus:ring-0 text-[#3b5266] font-medium px-3 py-2 placeholder-[#a1b4c6]" placeholder="Votre nom">
                        </div>
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="prenom" class="block text-xs font-bold text-[#3b5266] mb-1">Prénom</label>
                        <div class="flex rounded-md border border-[#c7d2db] shadow-sm focus-within:border-[#7a9bb8] focus-within:ring-1 focus-within:ring-[#7a9bb8] transition-all bg-white">
                            <span class="inline-flex items-center px-3 rounded-l-md border-r border-[#c7d2db] text-[#8299b1] bg-[#f8fafc]">
                                <i class="fa-regular fa-user text-sm"></i>
                            </span>
                            <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required
                                class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-0 focus:ring-0 text-[#3b5266] font-medium px-3 py-2 placeholder-[#a1b4c6]" placeholder="Votre prénom">
                        </div>
                    </div>
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-[#3b5266] mb-1">Adresse e-mail</label>
                    <div class="flex rounded-md border border-[#c7d2db] shadow-sm focus-within:border-[#7a9bb8] focus-within:ring-1 focus-within:ring-[#7a9bb8] transition-all bg-white">
                        <span class="inline-flex items-center px-3 rounded-l-md border-r border-[#c7d2db] text-[#8299b1] bg-[#f8fafc]">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-0 focus:ring-0 text-[#3b5266] font-medium px-3 py-2 placeholder-[#a1b4c6]" placeholder="yohann.mboussa@demo.cg">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-[#3b5266] mb-1">Mot de passe</label>
                        <div class="flex rounded-md border border-[#c7d2db] shadow-sm focus-within:border-[#7a9bb8] focus-within:ring-1 focus-within:ring-[#7a9bb8] transition-all bg-white">
                            <span class="inline-flex items-center px-3 rounded-l-md border-r border-[#c7d2db] text-[#8299b1] bg-[#f8fafc]">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-0 focus:ring-0 text-[#3b5266] font-medium px-3 py-2 placeholder-[#a1b4c6]" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-[#3b5266] mb-1">Confirmer le mot de passe</label>
                        <div class="flex rounded-md border border-[#c7d2db] shadow-sm focus-within:border-[#7a9bb8] focus-within:ring-1 focus-within:ring-[#7a9bb8] transition-all bg-white">
                            <span class="inline-flex items-center px-3 rounded-l-md border-r border-[#c7d2db] text-[#8299b1] bg-[#f8fafc]">
                                <i class="fa-solid fa-check-double text-sm"></i>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-0 focus:ring-0 text-[#3b5266] font-medium px-3 py-2 placeholder-[#a1b4c6]" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Avatar Upload -->
                <div>
                    <label for="avatar" class="block text-xs font-bold text-[#3b5266] mb-1">Photo de profil (Optionnel)</label>
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                            <i class="fa-solid fa-user text-gray-400 text-xl"></i>
                        </div>
                        <input id="avatar" type="file" name="avatar" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg shadow-md text-sm font-bold text-white bg-[#eda268] hover:bg-[#d88c52] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#eda268]">
                        <i class="fa-solid fa-user-plus mr-2"></i>
                        Créer mon compte
                    </button>
                </div>

                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-2 bg-white text-gray-500 font-medium">Ou s'inscrire avec</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('auth.social', 'google') }}" class="flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/><path d="M1 1h22v22H1z" fill="none"/></svg>
                        Google
                    </a>
                    <a href="{{ route('auth.social', 'github') }}" class="flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fa-brands fa-github text-gray-900 mr-2 text-base"></i>
                        GitHub
                    </a>
                </div>
                
            </form>

            <!-- Lien de connexion -->
            <div class="mt-4 text-center text-xs">
                <span class="text-[#8299b1] font-medium">Vous avez déjà un compte ?</span>
                <a href="{{ route('login') }}" class="font-bold text-[#5d85a6] hover:text-[#3b5266] transition-colors ml-1">Connectez-vous</a>
            </div>
        </div>
    </div>

</body>
</html>
