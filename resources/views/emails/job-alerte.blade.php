<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background-color: #204263; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 30px; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 20px; }
        .button { background-color: #eda268; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; }
        .job-card { background-color: #f8f9fa; border-left: 4px solid #eda268; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ACPE - Alerte Emploi</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Bonne nouvelle ! Une nouvelle offre correspondant à votre alerte <strong>"{{ $alerte->titre }}"</strong> vient d'être publiée.</p>
            
            <div class="job-card">
                <h3 style="margin-top: 0; color: #204263;">{{ $offre->titre }}</h3>
                <p style="margin-bottom: 5px;"><strong>Entreprise :</strong> {{ $offre->entreprise->raison_sociale }}</p>
                <p style="margin-bottom: 5px;"><strong>Lieu :</strong> {{ $offre->lieu ?? 'Non précisé' }}</p>
                <p style="margin-bottom: 5px;"><strong>Type de contrat :</strong> {{ $offre->typeContrat->libelle ?? 'N/A' }}</p>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/candidat/offres/' . $offre->id_offre) }}" class="button">Voir l'offre complète</a>
            </div>

            <p style="margin-top: 30px; font-size: 11px; color: #666;">Vous recevez ce mail car vous avez créé une alerte sur la plateforme ACPE. Vous pouvez modifier ou supprimer vos alertes à tout moment dans votre espace candidat.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ACPE Recommandation. Tous droits réservés.
        </div>
    </div>
</body>
</html>
