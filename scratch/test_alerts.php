<?php

use App\Models\Offre;
use App\Models\Alerte;
use App\Models\Localisation;
use App\Models\TypeContrat;
use App\Models\SecteurActivite;
use App\Observers\OffreObserver;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- TEST FINAL ALERTES (V2) ---\n";

// Récupération d'IDs valides
$typeId = TypeContrat::first()->id_type_cont ?? 1;
$secteurId = SecteurActivite::first()->id_sect_act ?? 1;

// 1. On cherche ou on crée la localisation Brazzaville
$loc = Localisation::firstOrCreate(['ville' => 'Brazzaville'], ['pays' => 'Congo']);

// 2. On crée l'offre
$offre = new Offre();
$offre->titre = 'Développeur Laravel Brazzaville';
$offre->description = 'Offre de test pour valider les alertes emploi.';
$offre->active = 1;
$offre->id_entreprise = 1;
$offre->id_type_cont = $typeId;
$offre->id_sect_act = $secteurId;
$offre->date_publication = now();
$offre->date_expiration = now()->addDays(30);
$offre->save();

echo "Offre créée ID: {$offre->id_offre}\n";

// 3. On attache la localisation
$offre->localisations()->attach($loc->id_localisation, ['est_principale' => true]);

// 4. On déclenche l'alerte
$observer = new OffreObserver();
$observer->created($offre);

echo "--- TEST TERMINÉ ---\n";
echo "Vérifiez vos messages et emails !\n";
