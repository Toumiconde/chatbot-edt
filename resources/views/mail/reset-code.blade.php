<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Code de réinitialisation</title>
    <style>
        body {font-family: 'Inter', sans-serif; background: #f4f7f9; color: #333; padding: 30px;}
        .card {max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 30px 40px; box-shadow: 0 8px 24px rgba(0,0,0,.1);}
        h1 {font-size: 1.6rem; margin-bottom: .5rem; color: #1e3a8a;}
        p {margin: .8rem 0; line-height: 1.5;}
        .code {display: inline-block; background: #e5e7eb; color: #111827; font-size: 1.4rem; letter-spacing: .15rem; padding: .6rem 1rem; border-radius: 8px; font-weight: 600; margin: 1rem 0;}
        .footer {margin-top: 2rem; font-size: .85rem; color: #6b7280;}
    </style>
</head>
<body>
    <div class="card">
        <h1>Code de réinitialisation</h1>
        <p>Bonjour,</p>
        <p>Vous avez demandé à réinitialiser votre mot de passe sur la plateforme UGANC. Utilisez le code ci‑dessous pour poursuivre la procédure :</p>
        <div class="code">{{ $code }}</div>
        <p>Ce code est valable <strong>15 minutes</strong>. Si vous n’avez pas fait cette demande, vous pouvez ignorer cet e‑mail en toute sécurité.</p>
        <p class="footer">© {{ date('Y') }} UGANC – Tous droits réservés</p>
    </div>
</body>
</html>
