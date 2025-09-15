<?php require_once __DIR__.'/layout/header.php'; ?>

<link rel="stylesheet" href="/css/style.css">

<div class="checkout-container">
<h2 class="checkout-title">Paiement</h2>

    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <p>Votre panier est vide.</p>
            <a href="/courses">Retourner aux cours</a>
        </div>
    <?php else: ?>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        ?>

        <div class="checkout-total">
            Total à payer : <strong><?= number_format($total, 2) ?> €</strong>
        </div>

        <form id="payment-form" method="POST" action="/cart/checkout">
            <div id="card-element"><!-- Stripe.js injects the Card Element --></div>
            <button id="submit" class="checkout-button">💳 Payer</button>
            <div id="error-message"></div>
        </form>

        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('pk_test_YOUR_PUBLIC_KEY'); // Remplace par ta clé publique Stripe
            const elements = stripe.elements();
            const card = elements.create('card');
            card.mount('#card-element');

            const form = document.getElementById('payment-form');
            const errorMessage = document.getElementById('error-message');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const {paymentMethod, error} = await stripe.createPaymentMethod('card', card);

                if (error) {
                    errorMessage.textContent = error.message;
                } else {
                    // Ajouter paymentMethod.id au formulaire et soumettre
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'payment_method_id';
                    hiddenInput.value = paymentMethod.id;
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        </script>
    <?php endif; ?>
</div>
 <?php require_once __DIR__.'/layout/footer.php'; ?>
