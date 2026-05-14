<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Exception;

class ScrapeAcpeOffres extends Command
{
    /**
     * Signature de la commande.
     */
    protected $signature = 'acpe:scrape-offres
                            {--pages=3 : Nombre de pages à scraper (237 pages max)}
                            {--delay=1 : Délai en secondes entre chaque requête}
                            {--force : Re-scraper même les offres déjà existantes}
                            {--dry-run : Afficher les résultats sans enregistrer en BDD}';

    /**
     * Description de la commande.
     */
    protected $description = 'Scraper les offres d\'emploi depuis le site ACPE (acpe.cg)';

    private string $baseUrl = 'https://www.acpe.cg';
    private int $nouveaux = 0;
    private int $mis_a_jour = 0;
    private int $erreurs = 0;

    /**
     * Exécuter la commande.
     */
    public function handle(): int
    {
        $pages    = (int) $this->option('pages');
        $delay    = (int) $this->option('delay');
        $force    = $this->option('force');
        $dryRun   = $this->option('dry-run');

        $this->info('');
        $this->info('╔══════════════════════════════════════════════╗');
        $this->info('║     🕷️  ACPE Scraper — Offres d\'emploi       ║');
        $this->info('╚══════════════════════════════════════════════╝');
        $this->info('');
        $this->info("📄 Pages à scraper  : {$pages}");
        $this->info("⏱️  Délai           : {$delay}s entre chaque page");
        $this->info("🔁 Force update     : " . ($force ? 'OUI' : 'NON'));
        $this->info("🧪 Dry-run          : " . ($dryRun ? 'OUI (rien sera enregistré)' : 'NON'));
        $this->info('');

        // Vérifier les prérequis
        if (!$this->verifierPrerequis()) {
            return self::FAILURE;
        }

        // Scraper chaque page
        for ($page = 1; $page <= $pages; $page++) {
            $this->info("─────────────────────────────────────────────");
            $this->info("📃 Scraping page {$page}/{$pages}...");

            $offresIds = $this->scraperPage($page);

            if (empty($offresIds)) {
                $this->warn("  ⚠️  Aucune offre trouvée sur la page {$page}. Arrêt.");
                break;
            }

            $this->info("  ✅ " . count($offresIds) . " offres trouvées sur cette page");

            // Pour chaque offre, récupérer les détails
            $bar = $this->output->createProgressBar(count($offresIds));
            $bar->start();

            foreach ($offresIds as $offreId) {
                try {
                    $this->scraperDetailOffre($offreId, $force, $dryRun);
                } catch (Exception $e) {
                    $this->erreurs++;
                    $errMsg = $e->getMessage();
                    Log::error("Erreur scraping offre #{$offreId}: {$errMsg}");
                    $this->newline();
                    $this->error("  ❌ Offre #{$offreId}: " . substr($errMsg, 0, 120));
                }

                $bar->advance();
                sleep($delay > 0 ? 1 : 0); // Délai poli entre chaque offre
            }

            $bar->finish();
            $this->info('');

            // Délai entre les pages
            if ($page < $pages) {
                sleep($delay);
            }
        }

        // Résumé final
        $this->info('');
        $this->info('╔══════════════════════════════════════════════╗');
        $this->info('║                📊 RÉSUMÉ                     ║');
        $this->info('╠══════════════════════════════════════════════╣');
        $this->info("║  ✅ Nouvelles offres     : {$this->nouveaux}");
        $this->info("║  🔄 Mises à jour         : {$this->mis_a_jour}");
        $this->info("║  ❌ Erreurs              : {$this->erreurs}");
        $this->info('╚══════════════════════════════════════════════╝');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Vérifier que la table offres a les colonnes nécessaires.
     */
    private function verifierPrerequis(): bool
    {
        if (!DB::getSchemaBuilder()->hasColumn('offres', 'acpe_id')) {
            $this->error('❌ La colonne acpe_id n\'existe pas dans la table offres.');
            $this->error('   Exécutez d\'abord : php artisan migrate');
            return false;
        }

        $this->info('✅ Prérequis OK');
        return true;
    }

    /**
     * Scraper une page de la liste des offres pour récupérer les IDs.
     */
    private function scraperPage(int $page): array
    {
        $url = "{$this->baseUrl}/offres-emplois?page={$page}";

        try {
            $html = $this->fetchPage($url);
            $crawler = new Crawler($html);
            $ids = [];

            // Chercher tous les liens "Consulter l'offre" → /details-offre-emplois/{id}
            $crawler->filter('a[href*="/details-offre-emplois/"]')->each(function (Crawler $node) use (&$ids) {
                $href = $node->attr('href');
                if (preg_match('/\/details-offre-emplois\/(\d+)/', $href, $matches)) {
                    $id = (int) $matches[1];
                    if (!in_array($id, $ids)) {
                        $ids[] = $id;
                    }
                }
            });

            return $ids;
        } catch (Exception $e) {
            $this->error("  ❌ Erreur lors du scraping de la page {$page}: " . $e->getMessage());
            $this->erreurs++;
            return [];
        }
    }

    /**
     * Scraper le détail d'une offre et l'enregistrer en BDD.
     */
    private function scraperDetailOffre(int $acpeId, bool $force, bool $dryRun): void
    {
        // Vérifier si l'offre existe déjà
        $exists = DB::table('offres')->where('acpe_id', $acpeId)->exists();

        if ($exists && !$force) {
            // Juste mettre à jour la date de synchro
            if (!$dryRun) {
                DB::table('offres')
                    ->where('acpe_id', $acpeId)
                    ->update(['derniere_synchro' => now()]);
            }
            return;
        }

        $url = "{$this->baseUrl}/details-offre-emplois/{$acpeId}";
        $html = $this->fetchPage($url);
        $crawler = new Crawler($html);

        // Extraire les données
        $data = $this->extraireOffre($crawler, $acpeId, $url);

        if (!$data) {
            $this->erreurs++;
            return;
        }

        if ($dryRun) {
            $this->newline();
            $this->line("  [DRY-RUN] Offre #{$acpeId}: {$data['titre']} — {$data['entreprise']}");
            return;
        }

        // Trouver ou créer les entités liées
        $idEntreprise = $this->trouverOuCreerEntreprise($data['entreprise']);
        $idTypeCont   = $this->trouverOuCreerTypeContrat($data['type_contrat']);
        $idSectAct    = $this->trouverOuCreerSecteur($data['secteur']);

        if (!$idEntreprise || !$idTypeCont || !$idSectAct) {
            $this->erreurs++;
            return;
        }

        // Nettoyer et tronquer les champs longs
        $titreClean       = mb_substr(trim($data['titre'] ?: "Offre #{$acpeId}"), 0, 255);
        $descClean        = mb_substr(trim($data['description'] ?: 'Voir offre sur acpe.cg'), 0, 65000);
        $missionClean     = $data['mission']         ? mb_substr(trim($data['mission']), 0, 65000) : null;
        $profilClean      = $data['profil_recherche'] ? mb_substr(trim($data['profil_recherche']), 0, 65000) : null;
        $qualifClean      = $data['qualification']   ? mb_substr(trim($data['qualification']), 0, 255) : null;
        $deptClean        = $data['localisation']    ? mb_substr(trim($data['localisation']), 0, 100) : null;
        $salaireClean     = mb_substr(trim($data['salaire'] ?: 'négociable'), 0, 100);

        // Préparer les données pour la BDD
        $offreData = [
            'titre'               => $titreClean,
            'description'         => $descClean,
            'mission'             => $missionClean,
            'profil_recherche'    => $profilClean,
            'date_publication'    => $data['date_publication'],
            'date_expiration'     => $data['date_expiration'],
            'salaire_min'         => null,
            'salaire_max'         => null,
            'statut_salaire'      => $salaireClean,
            'active'              => true,
            'id_entreprise'       => $idEntreprise,
            'id_type_cont'        => $idTypeCont,
            'id_sect_act'         => $idSectAct,
            'acpe_id'             => $acpeId,
            'url_source'          => mb_substr($url, 0, 255),
            'source'              => 'acpe_scraping',
            'qualification_requise' => $qualifClean,
            'departement'         => $deptClean,
            'derniere_synchro'    => now(),
        ];

        if ($exists) {
            DB::table('offres')
                ->where('acpe_id', $acpeId)
                ->update(array_merge($offreData, ['updated_at' => now()]));
            $this->mis_a_jour++;
        } else {
            $offreData['created_at'] = now();
            $offreData['updated_at'] = now();
            DB::table('offres')->insert($offreData);
            $this->nouveaux++;
        }

        // Localisation
        if (!empty($data['localisation'])) {
            $this->associerLocalisation($acpeId, $data['localisation']);
        }
    }

    /**
     * Extraire les données d'une page de détail d'offre.
     */
    private function extraireOffre(Crawler $crawler, int $acpeId, string $url): ?array
    {
        try {
            // Titre : chercher h4 spécifique qui contient le poste
            // Sur acpe.cg, le titre est dans un h4 ou h3 lié à /job-single.html
            $titre = '';
            $crawler->filter('a[href*="job-single"]')->each(function (Crawler $node) use (&$titre) {
                $t = trim($node->text());
                if (!$titre && strlen($t) > 2) {
                    $titre = $t;
                }
            });
            // Fallback : h1 ou title
            if (!$titre || strlen($titre) < 3) {
                $titre = $this->extraireTexte($crawler, 'h1');
            }
            if (!$titre || strlen($titre) < 3) {
                $titre = $this->extraireTexte($crawler, 'title');
                $titre = preg_replace('/^ACPE\s*[-–|]\s*/i', '', $titre);
                $titre = trim($titre);
            }

            // Entreprise
            $entreprise = 'Non précisé';
            $crawler->filter('h4')->each(function (Crawler $node) use (&$entreprise) {
                $text = trim($node->text());
                if (!empty($text) && $text !== 'Non précisé' && strlen($text) > 1) {
                    $entreprise = $text;
                }
            });

            // Description complète
            $description = '';
            $crawler->filter('p, li')->each(function (Crawler $node) use (&$description) {
                $text = trim($node->text());
                if (strlen($text) > 20) {
                    $description .= $text . "\n";
                }
            });

            // Extraire sections spécifiques depuis le texte complet
            $texteComplet = $crawler->text();

            $mission         = $this->extraireSection($texteComplet, 'Missions principales', 'Profil recherché');
            $profilRecherche = $this->extraireSection($texteComplet, 'Profil recherché', 'Les plus');

            // Dates
            $datePublication = $this->extraireDate($texteComplet, 'Date de publication');
            $dateExpiration  = $this->extraireDate($texteComplet, 'Date d\'expiration');

            // Salaire
            $salaire = 'négociable';
            if (preg_match('/Salaire\s*[:\s]+([^\n]+)/i', $texteComplet, $m)) {
                $salaire = trim($m[1]);
            }

            // Type de contrat (CDD, CDI, Stage)
            $typeContrat = 'CDD';
            foreach (['CDI', 'CDD', 'Stage', 'Freelance', 'Intérim', 'Alternance'] as $type) {
                if (stripos($texteComplet, $type) !== false) {
                    $typeContrat = $type;
                    break;
                }
            }

            // Localisation — extraire uniquement la ville, pas tout le texte
            $localisation = 'Brazzaville';
            // Chercher "Lieu : Brazzaville, Congo"
            if (preg_match('/Lieu\s*[:\s]+([A-Za-zÀ-ÿ\s\-]+?)(?:,|Durée|$)/i', $texteComplet, $m)) {
                $ville = trim($m[1]);
                if (strlen($ville) >= 2 && strlen($ville) <= 80) {
                    $localisation = $ville;
                }
            }
            // Chercher une ville connue du Congo
            if ($localisation === 'Brazzaville') {
                $villesConnues = ['BRAZZAVILLE','POINTE-NOIRE','OYO','DOLISIE','NIARI','BOUENZA','SANGHA','PLATEAUX','CUVETTE','POOL','KOUILOU','LIKOUALA','LEKOUMOU','IMPFONDO','OUESSO','GAMBOMA','SIBITI'];
                foreach ($villesConnues as $v) {
                    if (stripos($texteComplet, $v) !== false) {
                        $localisation = ucwords(strtolower($v));
                        break;
                    }
                }
            }
            // Sécurité : tronquer à 80 caractères max
            $localisation = mb_substr(trim($localisation), 0, 80);

            // Qualification
            $qualification = null;
            if (preg_match('/Qualification\s*[:\s]+([^\n]+)/i', $texteComplet, $m)) {
                $qualification = $this->nettoyerTexteScrape($m[1]);
            } elseif (preg_match('/Expérience\s*[:\s]+([^\n]+)/i', $texteComplet, $m)) {
                $qualification = $this->nettoyerTexteScrape($m[1]);
            }

            return [
                'titre'           => $titre ?: "Offre #{$acpeId}",
                'entreprise'      => $entreprise,
                'description'     => $description ?: 'Voir offre sur acpe.cg',
                'mission'         => $mission,
                'profil_recherche' => $profilRecherche,
                'date_publication' => $datePublication,
                'date_expiration'  => $dateExpiration,
                'salaire'         => $salaire,
                'type_contrat'    => $typeContrat,
                'localisation'    => $localisation,
                'qualification'   => $qualification,
                'secteur'         => 'Non défini',
            ];
        } catch (Exception $e) {
            Log::error("Erreur extraction offre #{$acpeId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extraire une section de texte entre deux marqueurs.
     */
    private function extraireSection(string $texte, string $debut, string $fin): ?string
    {
        $pattern = '/' . preg_quote($debut, '/') . '\s*[:\s]*(.*?)(?=' . preg_quote($fin, '/') . ')/si';
        if (preg_match($pattern, $texte, $matches)) {
            $section = trim($matches[1]);
            return strlen($section) > 5 ? $section : null;
        }
        return null;
    }

    /**
     * Extraire une date depuis le texte.
     */
    private function extraireDate(string $texte, string $label): string
    {
        $pattern = '/' . preg_quote($label, '/') . '\s*[:\s]*(\d{2}\/\d{2}\/\d{4})/i';
        if (preg_match($pattern, $texte, $matches)) {
            try {
                return Carbon::createFromFormat('d/m/Y', $matches[1])->format('Y-m-d');
            } catch (Exception $e) {
                // ignore
            }
        }
        return now()->format('Y-m-d');
    }

    /**
     * Extraire le texte d'un sélecteur CSS.
     */
    private function extraireTexte(Crawler $crawler, string $selector): string
    {
        try {
            $node = $crawler->filter($selector)->first();
            return $node->count() ? $this->nettoyerTexteScrape($node->text()) : '';
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Nettoyer le texte récupéré (enlever le bruit de l'ACPE, espaces en trop, etc.)
     */
    private function nettoyerTexteScrape(?string $texte): string
    {
        if (!$texte) return '';

        // Supprimer le texte récurrent du footer de l'ACPE
        $bruit = [
            '“L’Agence Congolaise Pour l’Emploi accompagne les chercheurs d’emploi et les entreprises pour favoriser l’insertion professionnelle et le développement des compétences locales.”',
            '#EnsemblePourEmploi',
            'Liens rapides',
            'Qui sommes-nous ?',
            'Les offres d\'emploi',
            'Actualités',
            'Contact',
            'Contactez-nous',
            'Téléphone: 2526',
            'Adresse:',
            'Email: contact@acpe.cg',
            '© ACPE. Tous droits réservés.',
            'Choisissez votre type de compte pour continuer.',
            'S\'inscrire en tant que demandeur d\'emploi',
            'S\'inscrire en tant qu\'entreprise',
            'Vous avez déjà un compte ?',
            'Se connecter en tant que demandeur d\'emploi',
            'Se connecter en tant qu\'entreprise',
            'SE CONNECTER A VOTRE ESPACE',
            'Vous n\'avez pas encore un compte ?',
        ];

        foreach ($bruit as $b) {
            $texte = str_replace($b, '', $texte);
        }

        // Nettoyer les espaces, tabulations, retours chariots multiples
        $texte = preg_replace('/\s+/', ' ', $texte);
        $texte = trim($texte);

        return $texte;
    }

    /**
     * Trouver ou créer une entreprise.
     */
    private function trouverOuCreerEntreprise(string $nom): ?int
    {
        $nom = trim($nom) ?: 'Entreprise non précisée';

        $entreprise = DB::table('entreprises')->where('raison_sociale', $nom)->first();

        if ($entreprise) {
            return $entreprise->id_entreprise;
        }

        return DB::table('entreprises')->insertGetId([
            'raison_sociale' => $nom,
            'verifiee'       => false,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    /**
     * Trouver ou créer un type de contrat.
     */
    private function trouverOuCreerTypeContrat(string $type): ?int
    {
        $type = strtoupper(trim($type));
        $contrat = DB::table('type_contrats')
            ->whereRaw('UPPER(libelle) = ?', [$type])
            ->first();

        if ($contrat) {
            return $contrat->id_type_cont;
        }

        return DB::table('type_contrats')->insertGetId([
            'libelle'    => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Trouver ou créer un secteur d'activité.
     */
    private function trouverOuCreerSecteur(string $secteur): ?int
    {
        $secteur = trim($secteur) ?: 'Autre';
        $existing = DB::table('secteur_activites')
            ->where('libelle', $secteur)
            ->first();

        if ($existing) {
            return $existing->id_sect_act;
        }

        return DB::table('secteur_activites')->insertGetId([
            'libelle'    => $secteur,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Associer une localisation à une offre.
     */
    private function associerLocalisation(int $acpeId, string $ville): void
    {
        $offre = DB::table('offres')->where('acpe_id', $acpeId)->first();
        if (!$offre) return;

        $villePropre = ucwords(strtolower(trim($ville)));

        // Trouver ou créer la localisation
        $localisation = DB::table('localisations')
            ->whereRaw('UPPER(ville) = ?', [strtoupper($ville)])
            ->first();

        if (!$localisation) {
            $locId = DB::table('localisations')->insertGetId([
                'ville'      => $villePropre,
                'pays'       => 'Congo',
                'region'     => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $locId = $localisation->id_localisation;
        }

        // Associer via la table pivot si pas déjà fait
        $exists = DB::table('offre_localisation')
            ->where('id_offre', $offre->id_offre)
            ->where('id_localisation', $locId)
            ->exists();

        if (!$exists) {
            DB::table('offre_localisation')->insert([
                'id_offre'        => $offre->id_offre,
                'id_localisation' => $locId,
            ]);
        }
    }

    /**
     * Faire une requête HTTP vers une URL.
     */
    private function fetchPage(string $url): string
    {
        $context = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'header'  => implode("\r\n", [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language: fr-FR,fr;q=0.9',
                    'Accept-Encoding: identity',
                    'Connection: close',
                ]),
                'timeout'         => 30,
                'follow_location' => true,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        $content = @file_get_contents($url, false, $context);

        if ($content === false) {
            throw new Exception("Impossible de charger l'URL: {$url}");
        }

        return $content;
    }
}
