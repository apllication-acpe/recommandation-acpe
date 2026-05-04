<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ACPE Reco') }}</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Alpine.js pour les animations et interactions -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

        <!-- Custom CSS Animations -->
        <style>
            [x-cloak] { display: none !important; }
            
            /* Animation d'apparition fluide */
            @keyframes slideUpFade {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-up {
                animation: slideUpFade 0.6s ease-out forwards;
            }
            
            /* Staggered animation delays for cards */
            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-800 bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Header Optionnel -->
            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-100 animate-slide-up">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Contenu Principal -->
            <main class="flex-grow" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
                <div x-show="show" x-transition.opacity.duration.500ms x-cloak>
                    {{ $slot }}
                </div>
            </main>
            
            <!-- Global Footer -->
            <footer class="py-8 border-t border-gray-200 bg-white mt-auto">
                <div class="max-w-7xl mx-auto px-4 flex flex-col items-center justify-center">
                    <p class="text-xs text-gray-400 font-medium">
                        © {{ date('Y') }}-{{ date('Y')+1 }} ACPE 
                    </p>
                    <div class="mt-4 w-12 h-1 bg-gray-200 rounded-full"></div>
                </div>
            </footer>
        </div>
    </body>
</html>
