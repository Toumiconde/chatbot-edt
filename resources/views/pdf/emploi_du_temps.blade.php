<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 4px; }
        h2 { font-size: 13px; text-align: center; color: #555; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #2d6a4f; color: #fff; padding: 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) td { background-color: #f4f4f4; }
        .footer { margin-top: 24px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <h1>Centre Informatique — UGANC</h1>
    <h2>{{ $titre }}</h2>

    @if($cours->isEmpty())
        <p style="text-align:center; color:#888;">Aucun cours trouvé pour cette sélection.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Jour</th>
                    <th>Heure début</th>
                    <th>Heure fin</th>
                    <th>Matière</th>
                    <th>Enseignant</th>
                    <th>Salle</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cours as $c)
                <tr>
                    <td>{{ $c->jour }}</td>
                    <td>{{ substr($c->heure_debut, 0, 5) }}</td>
                    <td>{{ substr($c->heure_fin, 0, 5) }}</td>
                    <td>{{ $c->matiere->nom }}</td>
                    <td>{{ $c->enseignant->prenom }} {{ $c->enseignant->nom }}</td>
                    <td>{{ $c->salle->nom }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} — Chatbot EDT — Groupe 6 — 2024/2025
    </div>
</body>
</html>
