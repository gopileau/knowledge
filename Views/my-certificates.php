<?php require_once __DIR__.'/../includes/header.php'; ?>

<div class="container">
    <h1>Mes Certificats</h1>
    
    <?php if (!empty($certificates)): ?>
        <div class="certificate-list">
            <?php foreach ($certificates as $certificate): ?>
                <div class="certificate-card">
                    <h3>Certificat #<?= htmlspecialchars($certificate['id']) ?></h3>
                    <p>Date d'obtention: <?= htmlspecialchars($certificate['issue_date']) ?></p>
                    <a href="/certificates/download/<?= $certificate['id'] ?>" class="btn btn-primary">Télécharger</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun certificat obtenu pour le moment.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__.'/../includes/footer.php'; ?>
