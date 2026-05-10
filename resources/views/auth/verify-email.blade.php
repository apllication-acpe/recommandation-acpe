<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vérification d'email - ACPE Reco</title>

        <!-- Favicon ACPE -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}"><link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'acpe-blue':   '#204263',
                        'acpe-orange': '#eda268',
                        'acpe-light':  '#7a9bb8',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInDown { from { opacity:0; transform:translateY(-16px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInUp   { from { opacity:0; transform:translateY(16px);  } to { opacity:1; transform:translateY(0); } }
        @keyframes pulse-ring { 0%,100% { box-shadow: 0 0 0 0 rgba(237,162,104,0.3); } 50% { box-shadow: 0 0 0 10px rgba(237,162,104,0); } }
        .anim-down  { animation: fadeInDown 0.5s ease-out forwards; }
        .anim-up    { animation: fadeInUp 0.55s ease-out 0.1s forwards; opacity:0; }
        .pulse-icon { animation: pulse-ring 2s ease-in-out infinite; }
        .bg-particles {
            background-color: #f0f4f8;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(32,66,99,0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(237,162,104,0.08) 0%, transparent 50%);
        }
    </style>
</head>
<body class="bg-particles min-h-screen flex flex-col font-sans justify-center items-center py-12 px-4 antialiased">

    <div class="max-w-md w-full anim-down">

        <!-- Logo Badge -->
        <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-acpe-blue flex items-center justify-center shadow-xl mb-4 ring-4 ring-acpe-orange/20">
                <i class="fa-solid fa-briefcase text-2xl text-acpe-orange"></i>
            </div>
            <span class="text-acpe-blue text-xl font-extrabold tracking-tight">ACPE <span class="text-acpe-orange">Reco</span></span>
            <span class="text-xs text-acpe-light font-medium mt-1">Agence Congolaise Pour l'Emploi</span>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden anim-up">

            <!-- Header -->
            <div class="bg-acpe-blue border-b-4 border-acpe-orange px-8 py-7 text-center">
                <div class="flex items-center justify-center space-x-3 text-white mb-1">
                    <i class="fa-solid fa-envelope-circle-check text-acpe-orange text-lg"></i>
                    <h1 class="text-xl font-bold tracking-wide">Vérifiez votre e-mail</h1>
                </div>
                <p class="text-acpe-light text-xs font-medium mt-1">Une dernière étape avant d'accéder à votre espace</p>
            </div>

            <!-- Body -->
            <div class="px-8 py-8 text-center">

                <!-- Animated Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-acpe-orange/10 flex items-center justify-center pulse-icon">
                        <i class="fa-regular fa-envelope text-4xl text-acpe-orange"></i>
                    </div>
                </div>

                <p class="text-sm text-acpe-blue leading-relaxed mb-6">
                    Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.
                </p>

                <!-- Success Status -->
                @if (session('status') == 'verification-link-sent')
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3.5 rounded-xl mb-6 flex items-start gap-2 text-left">
                        <i class="fa-solid fa-circle-check mt-0.5 flex-shrink-0"></i>
                        <span>Un nouveau lien de vérification a été envoyé à votre adresse e-mail.</span>
                    </div>
                @endif

                <!-- Resend -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-acpe-orange hover:bg-amber-600 active:scale-[0.98] transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-acpe-orange/50 focus:ring-offset-2">
                        <i class="fa-solid fa-paper-plane text-base"></i>
                        Renvoyer l'e-mail de vérification
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-acpe-light hover:text-acpe-blue transition-colors duration-200 mt-2">
                        <i class="fa-solid fa-arrow-right-from-bracket text-[10px]"></i>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} ACPE — Tous droits réservés</p>
    </div>

</body>
</html>
