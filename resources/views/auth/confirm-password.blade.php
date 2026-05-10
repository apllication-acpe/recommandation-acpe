<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de mot de passe - ACPE Reco</title>

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
        .anim-down { animation: fadeInDown 0.5s ease-out forwards; }
        .anim-up   { animation: fadeInUp 0.55s ease-out 0.1s forwards; opacity:0; }
        .bg-particles {
            background-color: #f0f4f8;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(32,66,99,0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(237,162,104,0.08) 0%, transparent 50%);
        }
        input:focus { outline: none; }
        .field-group:focus-within .field-icon { color: #eda268; }
        .field-group:focus-within { border-color: #eda268; box-shadow: 0 0 0 3px rgba(237,162,104,0.15); }
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
                    <i class="fa-solid fa-shield-halved text-acpe-orange text-lg"></i>
                    <h1 class="text-xl font-bold tracking-wide">Zone sécurisée</h1>
                </div>
                <p class="text-acpe-light text-xs font-medium mt-1">Confirmez votre mot de passe pour continuer</p>
            </div>

            <!-- Body -->
            <div class="px-8 py-8">

                <!-- Info -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3.5 mb-6 flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-acpe-light mt-0.5 flex-shrink-0"></i>
                    <p class="text-sm text-acpe-blue leading-relaxed">
                        Cette zone est sécurisée. Veuillez confirmer votre mot de passe avant de continuer.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-start gap-2">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
                            <ul class="list-disc pl-3 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-acpe-blue uppercase tracking-wider mb-2">
                            Mot de passe
                        </label>
                        <div class="field-group flex rounded-xl border border-gray-200 bg-gray-50 transition-all duration-200">
                            <span class="field-icon inline-flex items-center px-4 text-gray-400 transition-colors duration-200">
                                <i class="fa-solid fa-lock text-base"></i>
                            </span>
                            <input id="password" type="password" name="password"
                                required autocomplete="current-password"
                                class="flex-1 bg-transparent text-sm text-acpe-blue font-medium py-3.5 pr-4 border-0 focus:ring-0 placeholder-gray-400"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-acpe-orange hover:bg-amber-600 active:scale-[0.98] transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-acpe-orange/50 focus:ring-offset-2">
                            <i class="fa-solid fa-shield-check text-base"></i>
                            Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} ACPE — Tous droits réservés</p>
    </div>

</body>
</html>
