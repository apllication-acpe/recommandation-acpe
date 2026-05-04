<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\Recommandation;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class CandidatDashboardController extends Controller
{
    public function index()
    {
        $demandeur = Auth::user()->demandeur; // Relation à créer dans User

        if (!$demandeur) {
            abort(403, 'Accès refusé. Profil demandeur introuvable.');
        }

        // 1. Offres recommandées (basées sur son profil)
        $recommandations = $demandeur->recommandations()->with('offre')->take(5)->get();
        
        // 2. Suivi des candidatures (en attente, acceptées, refusées)
        $candidatures = $demandeur->candidatures()->with('offre')->latest()->take(5)->get();
        
        // 3. Complétion du profil (en %)
        $completion = 10; // Base
        if ($demandeur->cv_path) $completion += 20;
        if ($demandeur->photo_path) $completion += 10;
        if ($demandeur->adresse) $completion += 10;
        if ($demandeur->date_naissance) $completion += 10;
        if ($demandeur->id_nationalite) $completion += 10;
        
        // 4. Récupérer les vraies statistiques du profil
        $nbExperiences = \App\Models\Experience::where('id_demandeur', $demandeur->id_demandeur)->count();
        if ($nbExperiences > 0) $completion += 30; // Bonus pour avoir rempli ses expériences

        // Pour l'instant, les tables pivots demandeur_diplome, demandeur_competence, demandeur_langue n'existent pas dans le schéma actuel.
        // On récupère via les qualifications si existantes, sinon 0.
        $nbDiplomes = \Illuminate\Support\Facades\DB::table('qualification_demandeur')->where('id_demandeur', $demandeur->id_demandeur)->count();
        $nbCompetences = 0;
        $nbLangues = 0;

        // Limiter la complétion à 100%
        $completion = min(100, $completion);

        return view('candidat.dashboard', compact(
            'demandeur',
            'recommandations',
            'candidatures',
            'completion',
            'nbDiplomes',
            'nbCompetences',
            'nbLangues',
            'nbExperiences'
        ));
    }

    public function dashboard()
    {
        return $this->index();
    }

    public function offres()
    {
        $offres = \App\Models\Offre::where('active', true)
            ->with(['entreprise', 'typeContrat'])
            ->latest()
            ->paginate(12);

        return view('candidat.offres.index', compact('offres'));
    }

    public function messagerie(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer tous les messages liés à l'utilisateur
        $allMessages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();

        // Grouper par conversation (l'autre utilisateur)
        $conversations = $allMessages->groupBy(function ($message) use ($user) {
            return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        });

        $chats = $conversations->map(function ($msgs, $userId) use ($user) {
            $otherUser = User::find($userId);
            $lastMsg = $msgs->first();
            return [
                'user' => $otherUser,
                'last_message' => $lastMsg,
                'unread_count' => $msgs->where('receiver_id', $user->id)->whereNull('lu_at')->count(),
            ];
        });

        // Déterminer la conversation active
        $activeUserId = $request->query('user_id') ?? ($chats->keys()->first());
        $activeChat = $activeUserId ? $chats->get($activeUserId) : null;
        $activeMessages = $activeUserId ? Message::where(function($q) use ($user, $activeUserId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $activeUserId);
            })->orWhere(function($q) use ($user, $activeUserId) {
                $q->where('sender_id', $activeUserId)->where('receiver_id', $user->id);
            })->oldest()->get() : collect();

        return view('candidat.messagerie.index', compact('chats', 'activeChat', 'activeMessages', 'activeUserId'));
    }

    public function favoris()
    {
        return view('candidat.favoris.index');
    }

    public function alertes()
    {
        return view('candidat.alertes.index');
    }

    public function candidatures()
    {
        $demandeur = Auth::user()->demandeur;
        $candidatures = $demandeur->candidatures()->with('offre.entreprise')->latest()->paginate(10);
        return view('candidat.candidatures.index', compact('candidatures'));
    }

    public function showOffre($id)
    {
        $offre = Offre::with(['entreprise', 'typeContrat', 'secteurActivite'])->findOrFail($id);
        $demandeur = Auth::user()->demandeur;
        $dejaPostule = $demandeur ? $demandeur->hasPostulated($offre) : false;

        return view('candidat.offres.show', compact('offre', 'dejaPostule'));
    }
}
