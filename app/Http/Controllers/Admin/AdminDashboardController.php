<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Competence;
use App\Models\Demandeur;
use App\Models\Diplome;
use App\Models\Entreprise;
use App\Models\Langue;
use App\Models\Localisation;
use App\Models\Nationalite;
use App\Models\Offre;
use App\Models\Qualification;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
    // =========================================================
    // DASHBOARD
    // =========================================================

    public function index()
    {
        $offresCount             = Offre::count();
        $offresActives           = Offre::where('active', true)->count();
        $usersCount              = User::count();
        $totalCandidatures       = Candidature::count();
        $embauches               = Candidature::where('statut', 'acceptee')->count();
        $tauxConversion          = $totalCandidatures > 0 ? round(($embauches / $totalCandidatures) * 100, 2) : 0;
        $offresExpirees          = Offre::where('date_expiration', '<', now())->where('active', true)->get();
        $entreprisesNonVerifiees = Entreprise::where('verifiee', false)->get();
        $dernieresInscriptions   = User::latest()->take(5)->get();

        // Graphique : offres publiées par jour sur les 30 derniers jours
        $chartRaw = Offre::selectRaw('DATE(created_at) as jour, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('jour')
            ->orderBy('jour')
            ->pluck('total', 'jour')
            ->toArray();

        // Remplir les 30 jours même s'il n'y a pas d'offre ce jour-là
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $chartData[] = $chartRaw[$day] ?? 0;
        }
        $maxChart = max(array_merge([1], $chartData)); // évite division par 0

        return view('admin.dashboard', compact(
            'offresCount', 'offresActives', 'usersCount', 'tauxConversion',
            'offresExpirees', 'entreprisesNonVerifiees', 'dernieresInscriptions',
            'chartData', 'maxChart'
        ));
    }


    // =========================================================
    // CANDIDATS
    // =========================================================

    public function candidats()
    {
        $candidats = Demandeur::with(['user.roles', 'experiences', 'qualifications'])->paginate(15);
        return view('admin.candidats.index', compact('candidats'));
    }

    public function createCandidat()
    {
        return view('admin.candidats.create');
    }

    public function storeCandidat(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nom'                => $request->nom,
            'prenom'             => $request->prenom,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'email_verified_at'  => now(),
        ]);
        $user->assignRole('candidat');
        Demandeur::create(['user_id' => $user->id]);

        return redirect()->route('admin.candidats')->with('success', 'Candidat créé avec succès.');
    }

    public function editCandidat(Demandeur $demandeur)
    {
        $user = $demandeur->user;
        return view('admin.candidats.edit', compact('demandeur', 'user'));
    }

    public function updateCandidat(Request $request, Demandeur $demandeur)
    {
        $user = $demandeur->user;
        $request->validate([
            'nom'    => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email'  => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        $user->update(['nom' => $request->nom, 'prenom' => $request->prenom, 'email' => $request->email]);
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }
        return redirect()->route('admin.candidats')->with('success', 'Candidat mis à jour.');
    }

    public function destroyCandidat(Demandeur $demandeur)
    {
        /** @var \App\Models\User|null $user */
        $user = $demandeur->user;
        if (!$user) { $demandeur->delete(); return redirect()->route('admin.candidats')->with('success', 'Candidat supprimé.'); }
        if ($user->id == Auth::id()) { return redirect()->back()->with('error', 'Action impossible sur votre propre compte.'); }
        $user->delete();
        return redirect()->route('admin.candidats')->with('success', 'Candidat supprimé.');
    }

    public function toggleCandidat(Demandeur $demandeur)
    {
        /** @var \App\Models\User|null $user */
        $user = $demandeur->user;
        if (!$user) { return redirect()->back()->with('error', 'Utilisateur non trouvé.'); }
        $user->update(['email_verified_at' => $user->email_verified_at ? null : now()]);
        return redirect()->back()->with('success', 'Statut du candidat mis à jour.');
    }

    // =========================================================
    // ENTREPRISES
    // =========================================================

    public function entreprises()
    {
        $entreprises = Entreprise::paginate(10);
        return view('admin.entreprises.index', compact('entreprises'));
    }

    public function createEntreprise()
    {
        return view('admin.entreprises.create');
    }

    public function storeEntreprise(Request $request)
    {
        $request->validate(['raison_sociale' => 'required|string|max:255', 'email_contact' => 'required|email']);
        Entreprise::create($request->all());
        return redirect()->route('admin.entreprises')->with('success', 'Entreprise créée.');
    }

    public function showEntreprise(Entreprise $entreprise)
    {
        $entreprise->load(['offres.typeContrat', 'offres.secteurActivite', 'recruteurs.user']);
        return view('admin.entreprises.show', compact('entreprise'));
    }

    public function editEntreprise(Entreprise $entreprise)
    {
        return view('admin.entreprises.edit', compact('entreprise'));
    }

    public function updateEntreprise(Request $request, Entreprise $entreprise)
    {
        $request->validate(['raison_sociale' => 'required|string|max:255', 'email_contact' => 'required|email|max:255']);
        $entreprise->update($request->all());
        return redirect()->route('admin.entreprises')->with('success', 'Entreprise mise à jour.');
    }

    public function destroyEntreprise(Entreprise $entreprise)
    {
        $entreprise->delete();
        return response()->json(['success' => true]);
    }

    public function validateEntreprise(Entreprise $entreprise)
    {
        $entreprise->update(['verifiee' => true]);
        return response()->json(['success' => true]);
    }

    public function suspendEntreprise(Entreprise $entreprise)
    {
        $entreprise->update(['active' => false]);
        return response()->json(['success' => true]);
    }

    public function verifyEntreprise(Entreprise $entreprise)
    {
        $entreprise->update(['verifiee' => true]);
        return redirect()->back()->with('success', 'Entreprise vérifiée.');
    }

    public function exportEntreprises()
    {
        return response()->streamDownload(function () {
            echo "ID,Raison Sociale,Email\n";
            foreach (Entreprise::all() as $e) {
                echo "{$e->id_entreprise},{$e->raison_sociale},{$e->email_contact}\n";
            }
        }, 'entreprises.csv');
    }

    // =========================================================
    // OFFRES
    // =========================================================

    public function offres()
    {
        $offres = Offre::with(['entreprise', 'typeContrat', 'secteurActivite'])->latest()->paginate(10);
        return view('admin.offres.index', compact('offres'));
    }

    public function createOffre()
    {
        $entreprises    = Entreprise::orderBy('raison_sociale')->get();
        $typesContrat   = TypeContrat::orderBy('libelle')->get();
        $secteurs       = SecteurActivite::orderBy('libelle')->get();
        $localisations  = Localisation::orderBy('ville')->get();
        return view('admin.offres.create', compact('entreprises', 'typesContrat', 'secteurs', 'localisations'));
    }

    public function storeOffre(Request $request)
    {
        $request->validate([
            'titre'           => 'required|string|max:255',
            'description'     => 'required|string',
            'id_entreprise'   => 'required|exists:entreprises,id_entreprise',
            'id_localisation' => 'required|exists:localisations,id_localisation',
            'id_type_cont'    => 'required|exists:type_contrats,id_type_cont',
            'id_sect_act'     => 'required|exists:secteur_activites,id_sect_act',
            'date_expiration' => 'nullable|date',
        ]);

        $offre = Offre::create([
            'titre'            => $request->titre,
            'description'      => $request->description,
            'mission'          => $request->mission,
            'profil_recherche' => $request->profil_recherche,
            'id_entreprise'    => $request->id_entreprise,
            'id_type_cont'     => $request->id_type_cont,
            'id_sect_act'      => $request->id_sect_act,
            'date_expiration'  => $request->date_expiration,
            'salaire_min'      => $request->salaire_min,
            'salaire_max'      => $request->salaire_max,
            'statut_salaire'   => $request->statut_salaire ?? 'net',
            'active'           => $request->boolean('active', true),
            'debutant_accepte' => $request->boolean('debutant_accepte'),
            'permis_b_requis'  => $request->boolean('permis_b_requis'),
            'vehicule_requis'  => $request->boolean('vehicule_requis'),
            'travail_nuit'     => $request->boolean('travail_nuit'),
            'travail_weekend'  => $request->boolean('travail_weekend'),
            'date_publication' => now(),
        ]);

        // Attacher la localisation
        $offre->localisations()->attach($request->id_localisation, ['est_principale' => true]);

        return redirect()->route('admin.offres')->with('success', 'Offre créée avec succès.');
    }

    public function editOffre(Offre $offre)
    {
        return view('admin.offres.edit', compact('offre'));
    }

    public function updateOffre(Request $request, Offre $offre)
    {
        $request->validate(['titre' => 'required|string|max:255', 'description' => 'required|string']);
        $offre->update($request->all());
        return redirect()->route('admin.offres')->with('success', 'Offre mise à jour.');
    }

    public function toggleOffre(Offre $offre)
    {
        $offre->update(['active' => !$offre->active]);
        return redirect()->back()->with('success', 'Statut de l\'offre mis à jour.');
    }

    public function validateOffre(Offre $offre)
    {
        $offre->update(['active' => true]);
        return response()->json(['success' => true]);
    }

    public function deactivateOffre(Offre $offre)
    {
        $offre->update(['active' => false]);
        return response()->json(['success' => true]);
    }

    public function destroyOffre(Offre $offre)
    {
        $offre->delete();
        return response()->json(['success' => true]);
    }

    // =========================================================
    // CANDIDATURES
    // =========================================================

    public function candidatures()
    {
        $candidatures = Candidature::with(['offre.entreprise', 'demandeur.user'])->latest('date_candidature')->paginate(15);
        $secteurs     = SecteurActivite::all();
        $stats = [
            'total'       => Candidature::count(),
            'en_attente'  => Candidature::where('statut', 'en_attente')->count(),
            'acceptees'   => Candidature::where('statut', 'acceptee')->count(),
            'taux_succes' => Candidature::count() > 0
                ? round((Candidature::where('statut', 'acceptee')->count() / Candidature::count()) * 100, 1)
                : 0,
        ];
        return view('admin.candidatures.index', compact('candidatures', 'secteurs', 'stats'));
    }

    public function showCandidature(Candidature $candidature)
    {
        $candidature->load(['offre.entreprise', 'offre.typeContrat', 'demandeur.user', 'demandeur.experiences']);
        return response()->json($candidature);
    }

    public function updateCandidatureStatut(Request $request, Candidature $candidature)
    {
        $request->validate(['statut' => 'required|string']);
        $candidature->update(['statut' => $request->statut]);
        return response()->json(['success' => true]);
    }

    public function destroyCandidature(Candidature $candidature)
    {
        $candidature->delete();
        return response()->json(['success' => true]);
    }

    public function exportCandidatures()
    {
        return response()->streamDownload(function () {
            echo "ID,Candidat,Offre,Entreprise,Date,Statut\n";
            foreach (Candidature::with(['offre.entreprise', 'demandeur.user'])->get() as $c) {
                $nom      = optional(optional($c->demandeur)->user)->name ?? 'N/A';
                $titre    = optional($c->offre)->titre ?? 'N/A';
                $societe  = optional(optional($c->offre)->entreprise)->raison_sociale ?? 'N/A';
                echo "{$c->id_candidature},{$nom},{$titre},{$societe},{$c->date_candidature},{$c->statut}\n";
            }
        }, 'candidatures.csv');
    }

    public function exportCandidaturesPDF()
    {
        return response()->json(['message' => 'PDF export disponible prochainement.']);
    }

    // =========================================================
    // STATISTIQUES & RAPPORTS
    // =========================================================

    public function statistiques()
    {
        return view('admin.statistiques.dashboard_global');
    }

    public function rapportsExportables()
    {
        return view('admin.statistiques.rapport_exportables');
    }

    public function rapports()
    {
        return view('admin.rapports.index');
    }

    // =========================================================
    // AUDIT & LOGS
    // =========================================================

    public function logs()
    {
        return view('admin.audit.logs');
    }

    public function auditHistorique()
    {
        return view('admin.audit.historique');
    }

    public function auditPermissions()
    {
        return view('admin.audit.permission');
    }

    // =========================================================
    // RÔLES
    // =========================================================

    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    // =========================================================
    // RECOMMANDATION
    // =========================================================

    public function analysePerformances()
    {
        return view('admin.recommandation.analyse_performances');
    }

    public function ajusteAlgorithmes()
    {
        return view('admin.recommandation.ajuste_algorithmes');
    }

    // =========================================================
    // MODÉRATION
    // =========================================================

    public function modererOffres()
    {
        return view('admin.offres.moderer');
    }

    public function gereSignalements()
    {
        $signalements = \App\Models\Signalement::with(['user', 'signalable'])->latest()->paginate(15);
        $stats = [
            'total' => \App\Models\Signalement::count(),
            'en_attente' => \App\Models\Signalement::where('statut', 'en_attente')->count(),
            'offres' => \App\Models\Signalement::where('signalable_type', 'App\Models\Offre')->count(),
            'profils' => \App\Models\Signalement::whereIn('signalable_type', ['App\Models\User', 'App\Models\Entreprise'])->count(),
        ];
        return view('admin.moderation.gere_signalements', compact('signalements', 'stats'));
    }

    public function ignorerSignalement($id)
    {
        $sig = \App\Models\Signalement::findOrFail($id);
        $sig->update(['statut' => 'rejete']);
        return redirect()->back()->with('success', 'Signalement ignoré.');
    }

    public function bannirSignalable($id)
    {
        $sig = \App\Models\Signalement::findOrFail($id);
        $sig->update(['statut' => 'traite']);
        $target = $sig->signalable;
        
        if ($target) {
            if ($sig->signalable_type === 'App\Models\User' || $sig->signalable_type === 'App\Models\Demandeur' || $sig->signalable_type === 'App\Models\Entreprise') {
                if ($target instanceof \App\Models\User) {
                    $target->update(['actif' => false]);
                } elseif ($target->user) {
                    $target->user->update(['actif' => false]);
                }
            } elseif ($sig->signalable_type === 'App\Models\Offre') {
                $target->update(['statut' => 'supprimee']);
            }
        }
        return redirect()->back()->with('success', 'Cible bannie/désactivée avec succès.');
    }

    // =========================================================
    // CONFIGURATION GÉNÉRALE
    // =========================================================

    public function config()
    {
        return view('admin.config.index');
    }

    // ----- SECTEURS -----
    public function configSecteurs()
    {
        $secteurs = SecteurActivite::withCount('offres')->orderBy('libelle')->get();
        return view('admin.config.secteurs.index', compact('secteurs'));
    }
    public function createSecteur() { return view('admin.config.secteurs.create'); }
    public function showSecteur(SecteurActivite $secteur) { return view('admin.config.secteurs.show', compact('secteur')); }
    public function editSecteur(SecteurActivite $secteur) { return view('admin.config.secteurs.edit', compact('secteur')); }
    public function storeSecteur(Request $request)
    {
        $request->validate(['libelle' => 'required|string|max:255']);
        SecteurActivite::create($request->only('libelle', 'code_secteur_description'));
        return redirect()->route('admin.config.secteurs')->with('success', 'Secteur ajouté.');
    }
    public function updateSecteur(Request $request, SecteurActivite $secteur)
    {
        $request->validate(['libelle' => 'required|string|max:255']);
        $secteur->update($request->only('libelle', 'code_secteur_description'));
        return redirect()->route('admin.config.secteurs')->with('success', 'Secteur mis à jour.');
    }
    public function destroySecteur(SecteurActivite $secteur)
    {
        $secteur->delete();
        return redirect()->route('admin.config.secteurs')->with('success', 'Secteur supprimé.');
    }

    // ----- TYPES CONTRAT -----
    public function configTypes()
    {
        $types = TypeContrat::withCount('offres')->orderBy('libelle')->get();
        return view('admin.config.types_contrat.index', compact('types'));
    }
    public function createType() { return view('admin.config.types_contrat.create'); }
    public function showType(TypeContrat $type) { return view('admin.config.types_contrat.show', compact('type')); }
    public function editType(TypeContrat $type) { return view('admin.config.types_contrat.edit', compact('type')); }
    public function storeType(Request $request)
    {
        $request->validate(['libelle' => 'required|string|max:255', 'code' => 'required|string|max:20']);
        TypeContrat::create($request->only('libelle', 'code', 'duree'));
        return redirect()->route('admin.config.types')->with('success', 'Type de contrat ajouté.');
    }
    public function updateType(Request $request, TypeContrat $type)
    {
        $request->validate(['libelle' => 'required|string|max:255', 'code' => 'required|string|max:20']);
        $type->update($request->only('libelle', 'code', 'duree'));
        return redirect()->route('admin.config.types')->with('success', 'Type de contrat mis à jour.');
    }
    public function destroyType(TypeContrat $type)
    {
        $type->delete();
        return redirect()->route('admin.config.types')->with('success', 'Type de contrat supprimé.');
    }

    // ----- COMPÉTENCES -----
    public function configCompetences()
    {
        $competences = Competence::orderBy('libelle')->get();
        return view('admin.config.competences.index', compact('competences'));
    }
    public function createCompetence() { return view('admin.config.competences.create'); }
    public function showCompetence(Competence $competence) { return view('admin.config.competences.show', compact('competence')); }
    public function editCompetence(Competence $competence) { return view('admin.config.competences.edit', compact('competence')); }
    public function storeCompetence(Request $request)
    {
        $request->validate(['libelle' => 'required|string|max:255']);
        Competence::create($request->only('libelle', 'categorie'));
        return redirect()->route('admin.config.competences')->with('success', 'Compétence ajoutée.');
    }
    public function updateCompetence(Request $request, Competence $competence)
    {
        $request->validate(['libelle' => 'required|string|max:255']);
        $competence->update($request->only('libelle', 'categorie'));
        return redirect()->route('admin.config.competences')->with('success', 'Compétence mise à jour.');
    }
    public function destroyCompetence(Competence $competence)
    {
        $competence->delete();
        return redirect()->route('admin.config.competences')->with('success', 'Compétence supprimée.');
    }

    // ----- NATIONALITÉS -----
    public function configNationalites()
    {
        $nationalites = Nationalite::orderBy('libelle')->get();
        return view('admin.config.nationalites.index', compact('nationalites'));
    }
    public function createNationalite() { return view('admin.config.nationalites.create'); }
    public function showNationalite(Nationalite $nationalite) { return view('admin.config.nationalites.show', compact('nationalite')); }
    public function editNationalite(Nationalite $nationalite) { return view('admin.config.nationalites.edit', compact('nationalite')); }
    public function storeNationalite(Request $request)
    {
        $request->validate(['libelle' => 'required|string|max:255', 'code_iso' => 'required|string|max:5']);
        Nationalite::create($request->only('libelle', 'code_iso'));
        return redirect()->route('admin.config.nationalites')->with('success', 'Nationalité ajoutée.');
    }
    public function updateNationalite(Request $request, Nationalite $nationalite)
    {
        $request->validate(['libelle' => 'required|string|max:255', 'code_iso' => 'required|string|max:5']);
        $nationalite->update($request->only('libelle', 'code_iso'));
        return redirect()->route('admin.config.nationalites')->with('success', 'Nationalité mise à jour.');
    }
    public function destroyNationalite(Nationalite $nationalite)
    {
        $nationalite->delete();
        return redirect()->route('admin.config.nationalites')->with('success', 'Nationalité supprimée.');
    }

    // ----- LANGUES -----
    public function configLangues()
    {
        $langues = Langue::orderBy('libelle')->get();
        return view('admin.config.langues.index', compact('langues'));
    }
    public function createLangue() { return view('admin.config.langues.create'); }
    public function showLangue(Langue $langue) { return view('admin.config.langues.show', compact('langue')); }
    public function editLangue(Langue $langue) { return view('admin.config.langues.edit', compact('langue')); }
    public function storeLangue(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code_iso' => 'required|string|max:5',
        ]);
        Langue::create($request->only('libelle', 'code_iso'));
        return redirect()->route('admin.config.langues')->with('success', 'Langue ajoutée.');
    }
    public function updateLangue(Request $request, Langue $langue)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code_iso' => 'required|string|max:5',
        ]);
        $langue->update($request->only('libelle', 'code_iso'));
        return redirect()->route('admin.config.langues')->with('success', 'Langue mise à jour.');
    }
    public function destroyLangue(Langue $langue)
    {
        $langue->delete();
        return redirect()->route('admin.config.langues')->with('success', 'Langue supprimée.');
    }

    // ----- LOCALISATIONS -----
    public function configLocalisations()
    {
        $localisations = Localisation::orderBy('ville')->get();
        return view('admin.config.localisations.index', compact('localisations'));
    }
    public function createLocalisation() { return view('admin.config.localisations.create'); }
    public function showLocalisation(Localisation $localisation) { return view('admin.config.localisations.show', compact('localisation')); }
    public function editLocalisation(Localisation $localisation) { return view('admin.config.localisations.edit', compact('localisation')); }
    public function storeLocalisation(Request $request)
    {
        $request->validate(['ville' => 'required|string|max:255', 'pays' => 'required|string|max:255']);
        Localisation::create($request->only('ville', 'pays', 'region', 'code_postal'));
        return redirect()->route('admin.config.localisations')->with('success', 'Localisation ajoutée.');
    }
    public function updateLocalisation(Request $request, Localisation $localisation)
    {
        $request->validate(['ville' => 'required|string|max:255', 'pays' => 'required|string|max:255']);
        $localisation->update($request->only('ville', 'pays', 'region', 'code_postal'));
        return redirect()->route('admin.config.localisations')->with('success', 'Localisation mise à jour.');
    }
    public function destroyLocalisation(Localisation $localisation)
    {
        $localisation->delete();
        return redirect()->route('admin.config.localisations')->with('success', 'Localisation supprimée.');
    }

    // ----- QUALIFICATIONS -----
    public function configQualifications()
    {
        $qualifications = Qualification::orderBy('intitule')->get();
        return view('admin.config.qualifications.index', compact('qualifications'));
    }
    public function createQualification() { return view('admin.config.qualifications.create'); }
    public function showQualification(Qualification $qualification) { return view('admin.config.qualifications.show', compact('qualification')); }
    public function editQualification(Qualification $qualification) { return view('admin.config.qualifications.edit', compact('qualification')); }
    public function storeQualification(Request $request)
    {
        $request->validate(['intitule' => 'required|string|max:255']);
        Qualification::create($request->only('intitule'));
        return redirect()->route('admin.config.qualifications')->with('success', 'Qualification ajoutée.');
    }
    public function updateQualification(Request $request, Qualification $qualification)
    {
        $request->validate(['intitule' => 'required|string|max:255']);
        $qualification->update($request->only('intitule'));
        return redirect()->route('admin.config.qualifications')->with('success', 'Qualification mise à jour.');
    }
    public function destroyQualification(Qualification $qualification)
    {
        $qualification->delete();
        return redirect()->route('admin.config.qualifications')->with('success', 'Qualification supprimée.');
    }

    // ----- DIPLÔMES -----
    public function configDiplomes()
    {
        $diplomes = Diplome::orderBy('libelle')->get();
        return view('admin.config.diplomes.index', compact('diplomes'));
    }
    public function createDiplome() { return view('admin.config.diplomes.create'); }
    public function showDiplome(Diplome $diplome) { return view('admin.config.diplomes.show', compact('diplome')); }
    public function editDiplome(Diplome $diplome) { return view('admin.config.diplomes.edit', compact('diplome')); }
    public function storeDiplome(Request $request)
    {
        $request->validate(['libelle' => 'required|string|max:255', 'niveau' => 'required|string|max:20']);
        Diplome::create($request->only('libelle', 'niveau', 'specialite'));
        return redirect()->route('admin.config.diplomes')->with('success', 'Diplôme ajouté.');
    }
    public function updateDiplome(Request $request, Diplome $diplome)
    {
        $request->validate(['libelle' => 'required|string|max:255', 'niveau' => 'required|string|max:20']);
        $diplome->update($request->only('libelle', 'niveau', 'specialite'));
        return redirect()->route('admin.config.diplomes')->with('success', 'Diplôme mis à jour.');
    }
    public function destroyDiplome(Diplome $diplome)
    {
        $diplome->delete();
        return redirect()->route('admin.config.diplomes')->with('success', 'Diplôme supprimé.');
    }

    // =========================================================
    // SUPPORT
    // =========================================================

    public function support()
    {
        $tickets = \App\Models\SupportTicket::with('user')->latest()->paginate(15);
        $stats = [
            'ouverts' => \App\Models\SupportTicket::whereIn('statut', ['ouvert', 'en_attente'])->count(),
            'en_attente' => \App\Models\SupportTicket::where('statut', 'en_attente')->count(),
            'priorite_haute' => \App\Models\SupportTicket::whereIn('priorite', ['haute', 'urgente'])->where('statut', '!=', 'resolu')->count(),
        ];
        return view('admin.support.index', compact('tickets', 'stats'));
    }

    public function notifier()
    {
        $counts = [
            'all' => \App\Models\User::count(),
            'candidats' => \App\Models\Demandeur::count(),
            'recruteurs' => \App\Models\Entreprise::count(), // or count users with role recruteur
        ];
        return view('admin.support.notifier', compact('counts'));
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'target' => 'required|in:all,candidats,recruteurs',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Ici viendrait la logique d'envoi (Notifications Laravel / Mail)
        // Pour l'instant on simule le succès
        
        return redirect()->route('admin.support')->with('success', 'La notification a été diffusée avec succès.');
    }

    public function showTicket($ticketId)
    {
        $ticket = \App\Models\SupportTicket::with(['user', 'messages.user'])->findOrFail($ticketId);
        return view('admin.support.voir_ticket', compact('ticket'));
    }

    public function replyTicket(Request $request, $ticketId)
    {
        $request->validate(['message' => 'required|string']);
        $ticket = \App\Models\SupportTicket::findOrFail($ticketId);
        $ticket->messages()->create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'message' => $request->message
        ]);
        $ticket->update(['statut' => 'en_attente']); // User expects reply or admin replied
        return redirect()->back()->with('success', 'Réponse envoyée.');
    }

    public function closeTicket($ticketId)
    {
        $ticket = \App\Models\SupportTicket::findOrFail($ticketId);
        $ticket->update(['statut' => 'resolu']);
        return redirect()->back()->with('success', 'Ticket résolu.');
    }


    public function utilisateurs(Request $request)
    {
        $query = User::with('roles')->orderBy('nom')->orderBy('prenom')->orderBy('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            $query->where('actif', $request->status === 'actif');
        }

        $utilisateurs = $query->paginate(15)->withQueryString();
        return view('admin.utilisateurs.index', compact('utilisateurs'));
    }

    public function createUtilisateur()
    {
        $roles = Role::all();
        return view('admin.utilisateurs.create', compact('roles'));
    }

    public function storeUtilisateur(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
            'avatar'   => 'nullable|image|max:2048'
        ]);

        $data = $request->only('nom', 'prenom', 'email');
        $data['password'] = Hash::make($request->password);
        $data['email_verified_at'] = now();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($data);
        $user->assignRole($request->role);

        // Si le rôle est candidat, on crée le profil Demandeur
        if ($request->role === 'candidat') {
            \App\Models\Demandeur::create(['user_id' => $user->id]);
        }

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur créé avec succès.');
    }

    public function editUtilisateur(User $utilisateur)
    {
        $roles = Role::all();
        return view('admin.utilisateurs.edit', compact('utilisateur', 'roles'));
    }

    public function updateUtilisateur(Request $request, User $utilisateur)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $utilisateur->id,
            'role'     => 'required|exists:roles,name',
            'avatar'   => 'nullable|image|max:2048'
        ]);

        $data = $request->only('nom', 'prenom', 'email');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $utilisateur->update($data);
        $utilisateur->syncRoles([$request->role]);

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroyUtilisateur(User $utilisateur)
    {
        if ($utilisateur->id === Auth::id()) {
            return redirect()->route('admin.utilisateurs')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $utilisateur->delete();
        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur supprimé.');
    }

    public function suspendreUtilisateur(User $utilisateur)
    {
        if ($utilisateur->id === Auth::id()) {
            return response()->json(['error' => 'Vous ne pouvez pas suspendre votre propre compte.'], 403);
        }

        $utilisateur->update(['actif' => !$utilisateur->actif]);
        return response()->json(['success' => true]);
    }

    public function rolesMatrix()
    {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('admin.roles.matrix', compact('roles', 'permissions'));
    }


    public function exportAudit()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=audit_permissions_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Administrateur', 'Action', 'Cible', 'Details']);
            
            // Simulation de données pour l'exemple
            fputcsv($file, [now()->format('d/m/Y H:i'), 'Admin Principal', 'ATTRIBUTION ROLE', 'Marc Dupont', 'Ajout du role "Moderateur"']);
            fputcsv($file, [now()->subDay()->format('d/m/Y H:i'), 'Admin Principal', 'MODIFICATION PERMISSION', 'Recruteurs', 'Retrait du droit "Suppression"']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
