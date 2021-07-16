<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

 // user's unconnexion
 if( isset($_GET['action']) && $_GET['action'] == 'deconnexion' ) {
    // session is destroyed
    session_destroy();
    // unset($_SESSION['membre']);
    // $msg .= '<div class="alert alert-success">Déconnexion ok</div>';
}

// access restricted
if( user_is_connected() == true ) {
     header('location:profil.php');
}

$pseudo = '';

// if form is valid
if( isset($_POST['pseudo']) && isset($_POST['mdp']) ) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // pseudo request
    $connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $connexion->execute();

    // rowcont of pseudo
    if( $connexion->rowCount() > 0 ) {
        // pseudo ok
        // mdp checking 
        $infos = $connexion->fetch(PDO::FETCH_ASSOC); // 1 seule ligne = 1 fetch (pas de boucle)
        // echo '<pre>'; print_r($infos); echo '</pre>';
        // password_verify(mdp_du_form, mdp_de_la_bdd) permet de confirmer si le mdp correspond à celui enregistré au préalable
        if( password_verify($mdp, $infos['mdp']) ) {
            // mdp ok
            $_SESSION['membre'] = array();
            $_SESSION['membre']['id_membre'] = $infos['id_membre'];
            $_SESSION['membre']['pseudo'] = $infos['pseudo'];
            $_SESSION['membre']['mdp'] = $infos['mdp'];
            $_SESSION['membre']['nom'] = $infos['nom'];
            $_SESSION['membre']['prenom'] = $infos['prenom'];
            $_SESSION['membre']['telephone'] = $infos['telephone'];
            $_SESSION['membre']['email'] = $infos['email'];
            $_SESSION['membre']['civilite'] = $infos['civilite'];
            $_SESSION['membre']['statut'] = $infos['statut'];  
            
            // if user is connect redirection on profil
            header('location:profil.php');
            
        } else {
            // mdp error's
            $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Erreur sur le pseudo et/ou le mot de passe.</div>';
        }

    } else {
        // pseudo error's
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Erreur sur le pseudo et/ou le mot de passe.</div>';
    }



}



include 'inc/header.inc.php'; 
include 'inc/nav.inc.php';
        
?>
        <main class="container">
            <?php //echo '<pre>'; print_r($_SESSION); echo '</pre>';  ?>
            <br>
            <br>
            <br>
            <div class="star p-5 rounded text-center">
                <h1><i class="fab fa-rebel text-danger fa-1x faa-burst animated-hover"></i> connection <i class="fab fa-rebel text-danger fa-1x faa-burst animated-hover"></i></h1>
                <hr style="color: yellow;">
                <p class="lead episode" style="color: yellow;">time for a lightspeed jump<hr><?php echo $msg; ?></p>                
            </div>

            <div class="row">
                <div class="col-4 mx-auto mt-5 bg-light rounded mb-5">
                    <!-- form with two input -->
                    <form method="post" >
                        <div class="mb-3">
                            <label for="pseudo" class="form-label">Pseudo <i class="text-primary fas fa-user-alt"></i></label>
                            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="mdp" class="form-label">Mot de passe <i class="text-primary fas fa-user-alt"></i></label>
                            <input type="password" class="form-control" id="mdp" name="mdp">
                        </div>
                        <div class="mb-3 mt-4">
                            <button type="submit" class="btn btn-outline-primary w-100" id="connexion" ><i class="fas fa-keyboard"></i> Connexion <i class="fas fa-keyboard"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

<?php 
include 'inc/footer.inc.php';