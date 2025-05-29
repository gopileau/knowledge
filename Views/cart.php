<?php require_once __DIR__.'/layout/header.php'; ?>

<?php
// Si le panier est vide
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])):
?>
    <h2>Votre panier est vide.</h2>
    <p><a href="/dashboard">Retourner aux cours</a></p>
<?php
else:
?>
    <h2>Panier</h2>
    <table>
        <thead>
            <tr>
                <th>Cours/Leçon</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                // Calcul du total pour chaque élément
                $item_total = $item['quantity'] * $item['price'];
                $total += $item_total;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2) ?> €</td>
                    <td><?= number_format($item_total, 2) ?> €</td>
                    <td>
                        <a href="/cart/remove/<?= $item['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h3>Total : <?= number_format($total, 2) ?> €</h3>

    <!-- Bouton pour vider le panier -->
    <a href="/cart/clear">Vider le panier</a>
    
    <!-- Bouton pour procéder à la commande -->
    <a href="/checkout">Passer à la caisse</a>
<?php endif; ?>

<?php require_once __DIR__.'/layout/footer.php'; ?>
