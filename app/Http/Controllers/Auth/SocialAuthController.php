<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Demandeur;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['oauth' => "Erreur lors de la connexion via $provider. Veuillez réessayer."]);
        }

        // Extract names
        $nameParts = explode(' ', $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur');
        $nom = array_pop($nameParts);
        $prenom = implode(' ', $nameParts) ?: 'Utilisateur';

        // Check if user already exists
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update provider info if needed
            if (!$user->provider_id) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $user->avatar ?? $socialUser->getAvatar(),
                ]);
            }
        } else {
            // Create a new user
            $user = User::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'password' => null, // No password for social login
                'email_verified_at' => now(), // We trust Google/Github
            ]);

            // Assign role
            $user->assignRole('demandeur');

            // Create Demandeur profile
            Demandeur::create([
                'user_id' => $user->id,
            ]);
        }

        // Login user
        Auth::login($user, true);

        // Redirect based on role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('candidat.dashboard')->with('success', 'Connexion réussie via ' . ucfirst($provider));
    }
}
