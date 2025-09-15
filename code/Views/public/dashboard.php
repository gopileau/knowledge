<?php require_once __DIR__.'/../includes/header.php'; ?>

<div class="container">
    <h1>Tableau de Bord Public</h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cours Disponibles</h5>
                    <p class="card-text">Parcourez nos cours disponibles.</p>
                    <a href="/courses" class="btn btn-primary">Voir les cours</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Connexion</h5>
                    <p class="card-text">Accédez à votre espace personnel.</p>
                    <a href="/login" class="btn btn-primary">Se connecter</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inscription</h5>
                    <p class="card-text">Rejoignez notre plateforme.</p>
                    <a href="/register" class="btn btn-primary">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/../includes/footer.php'; ?>
