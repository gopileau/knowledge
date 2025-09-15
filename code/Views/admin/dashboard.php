<?php
// ───────────── 1) LOGIQUE PHP ────────────────────────────────────────────────
require_once __DIR__.'/../../includes/auth.php';
require_once __DIR__.'/../../includes/db.php';

if (!hasRole('admin')) {
    header('Location: /auth/login.php');
    exit;
}

$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

use App\Models\Order;
use App\Models\User;
use App\Models\Course;
use App\Models\Certification;

$userCount          = (new User())->getUserCount();
$courseCount        = (new Course())->getCourseCount();
$orderCount         = (new Order())->getOrderCount();
$certificationCount = (new Certification())->getCertificationCount();
$recentOrders       = (new Order())->getRecentOrders(5);

$title = 'Tableau de bord Admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?> - Knowledge Learning</title>

    <!-- Feuilles de style globales (pas besoin d’en créer d’autres) -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php include __DIR__.'/../layout/header.php'; ?>

<div class="dashboard-container"><!-- même classe que le dashboard utilisateur -->
    <h1><?= htmlspecialchars($title) ?></h1>

    <!-- ── STAT CARDS ───────────────────────────────────────────────────── -->
    <div class="stats"><!-- même grille -->
        <div class="stat">
            <h2>Utilisateurs</h2>
            <p><?= $userCount ?></p>
        </div>
        <div class="stat">
            <h2>Cours</h2>
            <p><?= $courseCount ?></p>
        </div>
        <div class="stat">
            <h2>Commandes</h2>
            <p><?= $orderCount ?></p>
        </div>
        <div class="stat">
            <h2>Certifications</h2>
            <p><?= $certificationCount ?></p>
        </div>
    </div>

    <!-- ── MENU BOUTONS ────────────────────────────────────────────────── -->
<div class="admin-menu"><!-- conteneur libre, boutons déjà stylés par .btn -->
        <a href="/admin/users"          class="btn btn-primary">Gestion utilisateurs</a>
        <a href="/admin/contents"       class="btn btn-primary">Gestion contenus</a>
        <a href="/admin/orders"         class="btn btn-primary">Historique achats</a>
        <a href="/admin/certifications" class="btn btn-primary">Certifications</a>
    </div>

    <!-- ── TABLE DES COMMANDES ─────────────────────────────────────────── -->
    <div class="recent-orders">
        <h2>Dernières commandes</h2>

        <?php if ($recentOrders): ?>
            <table class="cart-table"><!-- on ré-utilise le style tableau déjà présent -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Date</th>
                        <th>Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>
                                <a href="/admin/orders/<?= $order['id'] ?>">
                                    <?= $order['id'] ?>
                                </a>
                            </td>
                            <td>
                                <a href="/admin/users/<?= $order['user_id'] ?? '' ?>">
                                    <?= htmlspecialchars($order['user_email'] ?? 'N/A') ?>
                                </a>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td><?= number_format($order['total_price'] ?? 0, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande récente.</p>
        <?php endif; ?>
    </div>

    <a href="/logout" class="logout-button">Se déconnecter</a>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
</body>
</html>
