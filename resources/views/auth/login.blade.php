<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - ACPE Reco</title>
    
    <!-- Google Fonts -->
        <!-- Favicon ACPE -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}"><link rel="preconnect" href="https://fonts.googleapis.com">
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
<body class="bg-gray-100 text-gray-800 antialiased min-h-screen flex flex-col font-sans justify-center items-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Top Header (Correspondance avec l'image claire) -->
        <div class="bg-[#204263] border-b-4 border-[#eda268] px-6 py-10 text-center relative">
            <div class="flex items-center justify-center space-x-3 text-white mb-1">
                <i class="fa-solid fa-arrow-right-to-bracket text-xl text-[#eda268]"></i>
                <h1 class="text-2xl font-bold tracking-wide">Connexion</h1>
            </div>
            <p class="text-[#7a9bb8] text-xs font-medium mt-1">Accédez à vos recommandations d'offres</p>
        </div>

        <!-- Formulaire -->
        <div class="px-8 py-10">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Gestion des erreurs -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-6 font-medium">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-[#204263] uppercase tracking-wider mb-2">Adresse e-mail</label>
                    <div class="flex rounded-md border border-[#c7d2db] shadow-sm focus-within:border-[#eda268] focus-within:ring-1 focus-within:ring-[#eda268]/30 transition-all bg-white">
                        <span class="inline-flex items-center px-4 rounded-l-md border-r border-[#c7d2db] text-[#8299b1] bg-[#f8fafc]">
                            <i class="fa-regular fa-envelope text-lg"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-0 focus:ring-0 text-[#3b5266] font-medium px-4 py-3.5 placeholder-[#a1b4c6]" placeholder="yohann.mboussa@demo.cg">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-[#204263] uppercase tracking-wider mb-2">Mot de passe</label>
                    <div class="flex rounded-md border border-[#c7d2db] shadow-sm focus-within:border-[#eda268] focus-within:ring-1 focus-within:ring-[#eda268]/30 transition-all bg-white">
                        <span class="inline-flex items-center px-4 rounded-l-md border-r border-[#c7d2db] text-[#8299b1] bg-[#f8fafc]">
                            <i class="fa-solid fa-lock text-lg"></i>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-0 focus:ring-0 text-[#3b5266] font-medium px-4 py-3.5 placeholder-[#a1b4c6]" placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-[#eda268] focus:ring-[#eda268] border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-xs text-[#8299b1] font-medium">Se souvenir de moi</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="text-xs text-[#5d85a6] hover:text-[#3b5266] font-semibold transition-colors" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 rounded-lg shadow-md text-base font-bold text-white bg-[#eda268] hover:bg-[#d88c52] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#eda268]">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>
                        Se connecter
                    </button>
                </div>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500 font-medium">Ou continuer avec</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('auth.social', 'google') }}" class="flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/><path d="M1 1h22v22H1z" fill="none"/></svg>
                        Google
                    </a>
                    <a href="{{ route('auth.social', 'github') }}" class="flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fa-brands fa-github text-gray-900 mr-2 text-lg"></i>
                        GitHub
                    </a>
                </div>
                
            </form>

            <!-- Lien d'inscription -->
            <div class="mt-8 text-center text-sm">
                <span class="text-[#8299b1] font-medium">Pas encore inscrit ?</span>
                <a href="{{ route('register') }}" class="font-bold text-[#5d85a6] hover:text-[#3b5266] transition-colors ml-1">Créer un compte</a>
            </div>
        </div>
    </div>

</body>
</html>
