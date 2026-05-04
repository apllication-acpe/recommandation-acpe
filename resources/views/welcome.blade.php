<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACPE Reco - Système de Recommandation</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN (Garantit que le design marche à 100% même sans npm run dev) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        acpe: {
                            blue: '#204d7c',     /* Le bleu foncé du header et du texte */
                            orange: '#e87c22',   /* Le orange des boutons et du logo */
                            light: '#fdf3e9',    /* Le beige/orange très clair du cercle */
                            hover: '#d56c17'     /* Orange au survol */
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-gray-800 antialiased min-h-screen flex flex-col font-sans">

    <!-- Header -->
    <header class="bg-acpe-blue border-b-4 border-acpe-orange">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-briefcase text-acpe-orange text-xl"></i>
                <span class="text-white text-xl font-bold tracking-tight">ACPE Reco</span>
            </div>

            <!-- Auth Links -->
            <div class="flex items-center space-x-8 text-sm font-medium text-blue-100">
                @auth
                    <a href="{{ url('/dashboard') }}" class="hover:text-white transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-table-columns mr-2"></i>
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors duration-200 flex items-center">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>
                        Connexion
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fa-solid fa-user-plus mr-2"></i>
                            S'inscrire
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col justify-center">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="space-y-6 animate-fade-in-up">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-acpe-blue leading-tight tracking-tight">
                        Trouvez l'emploi qui vous correspond.
                    </h1>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Le système de recommandation de l'Agence Congolaise Pour l'Emploi vous aide à identifier, en quelques secondes, les offres les plus compatibles avec votre profil et votre parcours.
                    </p>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Créez votre compte, renseignez vos compétences et vos expériences, et laissez le moteur de matching faire le reste.
                    </p>
                    
                    <div class="pt-6 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-5">
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-md text-white bg-acpe-orange hover:bg-acpe-hover transition-colors duration-200 shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-user-plus mr-2.5"></i>
                            Je m'inscris
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-8 py-3.5 border-2 border-gray-200 text-base font-semibold rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 transition-colors duration-200 shadow-sm">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-2.5 text-gray-400"></i>
                            Je me connecte
                        </a>
                    </div>
                </div>

                <!-- Hero Graphic -->
                <div class="hidden md:flex justify-center items-center">
                    <div class="w-80 h-80 bg-acpe-light rounded-full flex items-center justify-center shadow-inner relative transform hover:scale-105 transition-transform duration-500 ease-out">
                        <i class="fa-solid fa-briefcase text-8xl text-acpe-orange drop-shadow-md"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 mb-8 border-t border-gray-100 mt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <!-- Feature 1 -->
                <div class="flex flex-col items-center group">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-5 group-hover:bg-blue-100 transition-colors duration-300">
                        <i class="fa-solid fa-chart-column text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-acpe-blue mb-3">Scoring multi-critères</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
                        Six critères pondérés : diplôme, compétences, langues, expérience, localité, secteur. Des résultats transparents et justifiés.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="flex flex-col items-center group">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-5 group-hover:bg-blue-100 transition-colors duration-300">
                        <i class="fa-solid fa-briefcase text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-acpe-blue mb-3">Analyse de carrière</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
                        Recommandations fondées sur votre parcours professionnel réel, pour prolonger et accélérer votre trajectoire.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="flex flex-col items-center group">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-5 group-hover:bg-blue-100 transition-colors duration-300">
                        <i class="fa-solid fa-shield-halved text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-acpe-blue mb-3">Données sécurisées</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
                        Authentification chiffrée, sessions protégées, respect strict de vos données personnelles.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 mt-auto relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 flex flex-col items-center justify-center relative z-10">
            <p class="text-xs text-gray-400 font-medium">
                © {{ date('Y') }}-{{ date('Y')+1 }} ACPE 
            </p>
            <div class="mt-4 w-12 h-1 bg-gray-200 rounded-full"></div>
        </div>
    </footer>

    <!-- Ajout d'une petite animation CSS custom pour rendre fluide -->
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
    </style>
</body>
</html>
