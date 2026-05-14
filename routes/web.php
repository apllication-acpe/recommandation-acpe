<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Candidat\CandidatDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;

use App\Http\Controllers\Auth\SocialAuthController;

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('auth.social');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();
    
    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('demandeur')) {
        return redirect()->route('candidat.dashboard');
    }
    abort(403, 'Rôle non défini.');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Candidats (User management consolidated)
        Route::get('/candidats', [AdminDashboardController::class, 'candidats'])->name('candidats');
        Route::get('/candidats/create', [AdminDashboardController::class, 'createCandidat'])->name('candidats.create');
        Route::post('/candidats/store', [AdminDashboardController::class, 'storeCandidat'])->name('candidats.store');
        Route::get('/candidats/{demandeur}/edit', [AdminDashboardController::class, 'editCandidat'])->name('candidats.edit');
        Route::patch('/candidats/{demandeur}/update', [AdminDashboardController::class, 'updateCandidat'])->name('candidats.update');
        Route::delete('/candidats/{demandeur}/delete', [AdminDashboardController::class, 'destroyCandidat'])->name('candidats.destroy');
        Route::patch('/candidats/{demandeur}/toggle', [AdminDashboardController::class, 'toggleCandidat'])->name('candidats.toggle');

        // Entreprises
        Route::get('/entreprises/create', [AdminDashboardController::class, 'createEntreprise'])->name('entreprises.create');
        Route::post('/entreprises/store', [AdminDashboardController::class, 'storeEntreprise'])->name('entreprises.store');
        Route::get('/entreprises', [AdminDashboardController::class, 'entreprises'])->name('entreprises');
        Route::get('/entreprises/export', [AdminDashboardController::class, 'exportEntreprises'])->name('entreprises.export');
        Route::get('/entreprises/{entreprise}', [AdminDashboardController::class, 'showEntreprise'])->name('entreprises.show');
        Route::get('/entreprises/{entreprise}/edit', [AdminDashboardController::class, 'editEntreprise'])->name('entreprises.edit');
        Route::patch('/entreprises/{entreprise}/update', [AdminDashboardController::class, 'updateEntreprise'])->name('entreprises.update');
        Route::post('/entreprises/{entreprise}/valider', [AdminDashboardController::class, 'validateEntreprise'])->name('entreprises.validate_js');
        Route::post('/entreprises/{entreprise}/suspendre', [AdminDashboardController::class, 'suspendEntreprise'])->name('entreprises.suspend_js');
        Route::delete('/entreprises/{entreprise}', [AdminDashboardController::class, 'destroyEntreprise'])->name('entreprises.destroy');
        Route::patch('/entreprises/{entreprise}/verify', [AdminDashboardController::class, 'verifyEntreprise'])->name('entreprises.verify');
        
        // Offres
        Route::get('/offres', [AdminDashboardController::class, 'offres'])->name('offres');
        Route::get('/offres/acpe', [AdminDashboardController::class, 'offresAcpe'])->name('offres.acpe');
        Route::post('/offres/acpe/sync', [AdminDashboardController::class, 'syncAcpe'])->name('offres.acpe.sync');
        Route::get('/offres/create', [AdminDashboardController::class, 'createOffre'])->name('offres.create');
        Route::post('/offres/store', [AdminDashboardController::class, 'storeOffre'])->name('offres.store');
        Route::get('/offres/moderer', [AdminDashboardController::class, 'modererOffres'])->name('offres.moderer');
        Route::get('/offres/{offre}/edit', [AdminDashboardController::class, 'editOffre'])->name('offres.edit');
        Route::patch('/offres/{offre}/update', [AdminDashboardController::class, 'updateOffre'])->name('offres.update');
        Route::post('/offres/{offre}/valider', [AdminDashboardController::class, 'validateOffre'])->name('offres.validate');
        Route::post('/offres/{offre}/desactiver', [AdminDashboardController::class, 'deactivateOffre'])->name('offres.deactivate');
        Route::delete('/offres/{offre}', [AdminDashboardController::class, 'destroyOffre'])->name('offres.destroy');
        Route::patch('/offres/{offre}/toggle', [AdminDashboardController::class, 'toggleOffre'])->name('offres.toggle');

        // Candidatures
        Route::get('/candidatures', [AdminDashboardController::class, 'candidatures'])->name('candidatures');
        Route::get('/candidatures/export', [AdminDashboardController::class, 'exportCandidatures'])->name('candidatures.export');
        Route::get('/candidatures/export-pdf', [AdminDashboardController::class, 'exportCandidaturesPDF'])->name('candidatures.export-pdf');
        Route::get('/candidatures/{candidature}', [AdminDashboardController::class, 'showCandidature'])->name('candidatures.show');
        Route::patch('/candidatures/{candidature}/statut', [AdminDashboardController::class, 'updateCandidatureStatut'])->name('candidatures.statut');
        Route::delete('/candidatures/{candidature}', [AdminDashboardController::class, 'destroyCandidature'])->name('candidatures.destroy');

        // Statistiques & Rapports
        Route::get('/statistiques', [AdminDashboardController::class, 'statistiques'])->name('statistiques');
        Route::get('/statistiques/rapports', [AdminDashboardController::class, 'rapportsExportables'])->name('statistiques.rapports');

        // Audit & Logs
        Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs');
        Route::get('/audit/historique', [AdminDashboardController::class, 'auditHistorique'])->name('audit.historique');
        Route::get('/audit/permissions', [AdminDashboardController::class, 'auditPermissions'])->name('audit.permissions');

        // Configuration
        Route::get('/config', [AdminDashboardController::class, 'config'])->name('config');
        
        // Messagerie Admin
        Route::get('/messagerie', [\App\Http\Controllers\Candidat\MessagerieController::class, 'index'])->name('messagerie');
        Route::post('/messagerie/store', [\App\Http\Controllers\Candidat\MessagerieController::class, 'store'])->name('messagerie.store');
        
        // Secteurs
        Route::get('/config/secteurs', [AdminDashboardController::class, 'configSecteurs'])->name('config.secteurs');
        Route::get('/config/secteurs/create', [AdminDashboardController::class, 'createSecteur'])->name('config.secteurs.create');
        Route::post('/config/secteurs', [AdminDashboardController::class, 'storeSecteur'])->name('config.secteurs.store');
        Route::get('/config/secteurs/{secteur}', [AdminDashboardController::class, 'showSecteur'])->name('config.secteurs.show');
        Route::get('/config/secteurs/{secteur}/edit', [AdminDashboardController::class, 'editSecteur'])->name('config.secteurs.edit');
        Route::patch('/config/secteurs/{secteur}', [AdminDashboardController::class, 'updateSecteur'])->name('config.secteurs.update');
        Route::delete('/config/secteurs/{secteur}', [AdminDashboardController::class, 'destroySecteur'])->name('config.secteurs.destroy');

        // Types
        Route::get('/config/types-contrat', [AdminDashboardController::class, 'configTypes'])->name('config.types');
        Route::get('/config/types-contrat/create', [AdminDashboardController::class, 'createType'])->name('config.types.create');
        Route::post('/config/types-contrat', [AdminDashboardController::class, 'storeType'])->name('config.types.store');
        Route::get('/config/types-contrat/{type}', [AdminDashboardController::class, 'showType'])->name('config.types.show');
        Route::get('/config/types-contrat/{type}/edit', [AdminDashboardController::class, 'editType'])->name('config.types.edit');
        Route::patch('/config/types-contrat/{type}', [AdminDashboardController::class, 'updateType'])->name('config.types.update');
        Route::delete('/config/types-contrat/{type}', [AdminDashboardController::class, 'destroyType'])->name('config.types.destroy');

        // Competences
        Route::get('/config/competences', [AdminDashboardController::class, 'configCompetences'])->name('config.competences');
        Route::get('/config/competences/create', [AdminDashboardController::class, 'createCompetence'])->name('config.competences.create');
        Route::post('/config/competences', [AdminDashboardController::class, 'storeCompetence'])->name('config.competences.store');
        Route::get('/config/competences/{competence}', [AdminDashboardController::class, 'showCompetence'])->name('config.competences.show');
        Route::get('/config/competences/{competence}/edit', [AdminDashboardController::class, 'editCompetence'])->name('config.competences.edit');
        Route::patch('/config/competences/{competence}', [AdminDashboardController::class, 'updateCompetence'])->name('config.competences.update');
        Route::delete('/config/competences/{competence}', [AdminDashboardController::class, 'destroyCompetence'])->name('config.competences.destroy');

        // Nationalites
        Route::get('/config/nationalites', [AdminDashboardController::class, 'configNationalites'])->name('config.nationalites');
        Route::get('/config/nationalites/create', [AdminDashboardController::class, 'createNationalite'])->name('config.nationalites.create');
        Route::post('/config/nationalites', [AdminDashboardController::class, 'storeNationalite'])->name('config.nationalites.store');
        Route::get('/config/nationalites/{nationalite}', [AdminDashboardController::class, 'showNationalite'])->name('config.nationalites.show');
        Route::get('/config/nationalites/{nationalite}/edit', [AdminDashboardController::class, 'editNationalite'])->name('config.nationalites.edit');
        Route::patch('/config/nationalites/{nationalite}', [AdminDashboardController::class, 'updateNationalite'])->name('config.nationalites.update');
        Route::delete('/config/nationalites/{nationalite}', [AdminDashboardController::class, 'destroyNationalite'])->name('config.nationalites.destroy');

        // Langues
        Route::get('/config/langues', [AdminDashboardController::class, 'configLangues'])->name('config.langues');
        Route::get('/config/langues/create', [AdminDashboardController::class, 'createLangue'])->name('config.langues.create');
        Route::post('/config/langues', [AdminDashboardController::class, 'storeLangue'])->name('config.langues.store');
        Route::get('/config/langues/{langue}', [AdminDashboardController::class, 'showLangue'])->name('config.langues.show');
        Route::get('/config/langues/{langue}/edit', [AdminDashboardController::class, 'editLangue'])->name('config.langues.edit');
        Route::patch('/config/langues/{langue}', [AdminDashboardController::class, 'updateLangue'])->name('config.langues.update');
        Route::delete('/config/langues/{langue}', [AdminDashboardController::class, 'destroyLangue'])->name('config.langues.destroy');

        // Localisations
        Route::get('/config/localisations', [AdminDashboardController::class, 'configLocalisations'])->name('config.localisations');
        Route::get('/config/localisations/create', [AdminDashboardController::class, 'createLocalisation'])->name('config.localisations.create');
        Route::post('/config/localisations', [AdminDashboardController::class, 'storeLocalisation'])->name('config.localisations.store');
        Route::get('/config/localisations/{localisation}', [AdminDashboardController::class, 'showLocalisation'])->name('config.localisations.show');
        Route::get('/config/localisations/{localisation}/edit', [AdminDashboardController::class, 'editLocalisation'])->name('config.localisations.edit');
        Route::patch('/config/localisations/{localisation}', [AdminDashboardController::class, 'updateLocalisation'])->name('config.localisations.update');
        Route::delete('/config/localisations/{localisation}', [AdminDashboardController::class, 'destroyLocalisation'])->name('config.localisations.destroy');

        // Qualifications
        Route::get('/config/qualifications', [AdminDashboardController::class, 'configQualifications'])->name('config.qualifications');
        Route::get('/config/qualifications/create', [AdminDashboardController::class, 'createQualification'])->name('config.qualifications.create');
        Route::post('/config/qualifications', [AdminDashboardController::class, 'storeQualification'])->name('config.qualifications.store');
        Route::get('/config/qualifications/{qualification}', [AdminDashboardController::class, 'showQualification'])->name('config.qualifications.show');
        Route::get('/config/qualifications/{qualification}/edit', [AdminDashboardController::class, 'editQualification'])->name('config.qualifications.edit');
        Route::patch('/config/qualifications/{qualification}', [AdminDashboardController::class, 'updateQualification'])->name('config.qualifications.update');
        Route::delete('/config/qualifications/{qualification}', [AdminDashboardController::class, 'destroyQualification'])->name('config.qualifications.destroy');

        // Diplomes
        Route::get('/config/diplomes', [AdminDashboardController::class, 'configDiplomes'])->name('config.diplomes');
        Route::get('/config/diplomes/create', [AdminDashboardController::class, 'createDiplome'])->name('config.diplomes.create');
        Route::post('/config/diplomes', [AdminDashboardController::class, 'storeDiplome'])->name('config.diplomes.store');
        Route::get('/config/diplomes/{diplome}', [AdminDashboardController::class, 'showDiplome'])->name('config.diplomes.show');
        Route::get('/config/diplomes/{diplome}/edit', [AdminDashboardController::class, 'editDiplome'])->name('config.diplomes.edit');
        Route::patch('/config/diplomes/{diplome}', [AdminDashboardController::class, 'updateDiplome'])->name('config.diplomes.update');
        Route::delete('/config/diplomes/{diplome}', [AdminDashboardController::class, 'destroyDiplome'])->name('config.diplomes.destroy');
        
        // Recommandation
        Route::get('/recommandation/analyse', [AdminDashboardController::class, 'analysePerformances'])->name('recommandation.analyse');
        Route::get('/recommandation/ajuste', [AdminDashboardController::class, 'ajusteAlgorithmes'])->name('recommandation.ajuste');

        // Support
        Route::get('/support', [AdminDashboardController::class, 'support'])->name('support');
        Route::get('/support/notifier', [AdminDashboardController::class, 'notifier'])->name('support.notifier');
        Route::post('/support/notifier', [AdminDashboardController::class, 'sendNotification'])->name('support.notifier.send');
        Route::get('/support/ticket/{ticket}', [AdminDashboardController::class, 'showTicket'])->name('support.ticket');
        Route::post('/support/ticket/{ticket}/reply', [AdminDashboardController::class, 'replyTicket'])->name('support.ticket.reply');
        Route::post('/support/ticket/{ticket}/close', [AdminDashboardController::class, 'closeTicket'])->name('support.ticket.close');

        // Moderation
        Route::get('/moderation/signalements', [AdminDashboardController::class, 'gereSignalements'])->name('moderation.signalements');
        Route::post('/moderation/signalements/{id}/ignorer', [AdminDashboardController::class, 'ignorerSignalement'])->name('moderation.signalements.ignorer');
        Route::post('/moderation/signalements/{id}/bannir', [AdminDashboardController::class, 'bannirSignalable'])->name('moderation.signalements.bannir');


        // Utilisateurs
        Route::get('/utilisateurs', [AdminDashboardController::class, 'utilisateurs'])->name('utilisateurs');
        Route::get('/utilisateurs/create', [AdminDashboardController::class, 'createUtilisateur'])->name('utilisateurs.create');
        Route::post('/utilisateurs', [AdminDashboardController::class, 'storeUtilisateur'])->name('utilisateurs.store');
        Route::get('/utilisateurs/{utilisateur}/edit', [AdminDashboardController::class, 'editUtilisateur'])->name('utilisateurs.edit');
        Route::patch('/utilisateurs/{utilisateur}', [AdminDashboardController::class, 'updateUtilisateur'])->name('utilisateurs.update');
        Route::delete('/utilisateurs/{utilisateur}', [AdminDashboardController::class, 'destroyUtilisateur'])->name('utilisateurs.destroy');
        Route::post('/utilisateurs/{utilisateur}/suspendre', [AdminDashboardController::class, 'suspendreUtilisateur'])->name('utilisateurs.suspendre');

        // Roles
        Route::get('/roles', [AdminDashboardController::class, 'roles'])->name('roles');
        Route::get('/roles/matrix', [AdminDashboardController::class, 'rolesMatrix'])->name('roles.matrix');

        // Audit & Sécurité
        Route::get('/audit/permissions', [AdminDashboardController::class, 'auditPermissions'])->name('audit.permissions');
        Route::get('/audit/permissions/export', [AdminDashboardController::class, 'exportAudit'])->name('audit.permissions.export');
    });



    // Demandeur Routes
    Route::middleware(['role:demandeur'])->prefix('candidat')->name('candidat.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Candidat\CandidatDashboardController::class, 'index'])->name('dashboard');
        Route::get('/offres', [\App\Http\Controllers\Candidat\CandidatDashboardController::class, 'offres'])->name('offres.index');
        Route::get('/offres/{offre}', [\App\Http\Controllers\Candidat\CandidatDashboardController::class, 'showOffre'])->name('offres.show');
        Route::post('/offres/{offre}/postuler', [\App\Http\Controllers\Candidat\CandidatureController::class, 'postuler'])->name('offres.postuler');
        Route::get('/messagerie', [\App\Http\Controllers\Candidat\MessagerieController::class, 'index'])->name('messagerie');
        Route::post('/messagerie/store', [\App\Http\Controllers\Candidat\MessagerieController::class, 'store'])->name('messagerie.store');
        Route::get('/favoris', [\App\Http\Controllers\Candidat\FavoriController::class, 'index'])->name('favoris');
        Route::post('/favoris/{offre}/toggle', [\App\Http\Controllers\Candidat\FavoriController::class, 'toggle'])->name('favoris.toggle');
        Route::get('/alertes', [\App\Http\Controllers\Candidat\AlerteController::class, 'index'])->name('alertes');
        Route::post('/alertes', [\App\Http\Controllers\Candidat\AlerteController::class, 'store'])->name('alertes.store');
        Route::post('/alertes/{alerte}/toggle', [\App\Http\Controllers\Candidat\AlerteController::class, 'toggle'])->name('alertes.toggle');
        Route::delete('/alertes/{alerte}', [\App\Http\Controllers\Candidat\AlerteController::class, 'destroy'])->name('alertes.destroy');
        Route::get('/candidatures', [\App\Http\Controllers\Candidat\CandidatureController::class, 'index'])->name('candidatures');
        Route::delete('/candidatures/{id}', [\App\Http\Controllers\Candidat\CandidatureController::class, 'destroy'])->name('candidatures.destroy');
        
        // Gestion du profil demandeur
        Route::get('/profil', [\App\Http\Controllers\Candidat\ProfilController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [\App\Http\Controllers\Candidat\ProfilController::class, 'update'])->name('profil.update');

        // Moteur de recommandations
        Route::get('/recommandations/criteres', [\App\Http\Controllers\Candidat\RecommandationController::class, 'criteres'])->name('reco.criteres');
        Route::get('/recommandations/professionnelle', [\App\Http\Controllers\Candidat\RecommandationController::class, 'professionnelle'])->name('reco.professionnelle');
    });
    // Messagerie partagée
    Route::get('/messagerie/create', [\App\Http\Controllers\MessageController::class, 'create'])->name('messagerie.create');
    Route::post('/messagerie/store', [\App\Http\Controllers\MessageController::class, 'store'])->name('messagerie.store');
    Route::get('/messagerie/{message}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messagerie.show');
    Route::post('/messagerie/{message}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('messagerie.reply');
    Route::get('/messagerie', [\App\Http\Controllers\MessageController::class, 'index'])->name('messagerie.index');
});

require __DIR__.'/auth.php';
