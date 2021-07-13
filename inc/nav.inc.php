
<?php

// if(isset($_GET['recherche'])){
//     $recherche= $pdo->prepare("SELECT * FROM categorie WHERE motcles LIKE '%".$_GET['recherche']."%'");
//     $recherche->bindParam(':motcles', $_GET['recherche'], PDO::PARAM_STR);
//     $recherche->execute();
//     $resultat_recherche = $recherche->fetchAll(PDO::FETCH_ASSOC);
//     var_dump($resultat_recherche);
    
// }
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top star">
            <div class="container-fluid ">
                <a class="navbar-brand text-warning sw" href="<?php echo URL; ?>index.php">SWAP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarCollapse ">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0 ">
                        
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="<?php echo URL; ?>qui_sommes_nous.php">Qui sommes nous ?</a>
                        </li>
                        

                        <?php if( user_is_connected() == false ) { ?>

                        <li class="nav-item">
                        <a class="nav-link text-warning" href="<?php echo URL; ?>connexion.php">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="<?php echo URL; ?>inscription.php">Inscription</a>
                        </li>
        

                        <?php } else { ?>

                            <li class="nav-item">
                                <a class="nav-link text-warning"  href="<?php echo URL; ?>profil.php">Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning"  href="<?php echo URL; ?>depot_annonce.php">Mes annonces</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a>
                            </li>

                        <?php } ?>
                        
                        <!-- liens pour l'admin -->
                        <?php if( user_is_admin() == true ) { ?>


                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle text-warning" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gestion</a>
                            <div class="dropdown-menu star " aria-labelledby="dropdown01">
                                <a class="dropdown-item text-warning" href="<?php echo URL; ?>admin/gestion_membres.php">Membre</a>
                                <a class="dropdown-item text-warning" href="<?php echo URL; ?>admin/gestion_categorie.php">Categorie</a>
                                <a class="dropdown-item text-warning" href="<?php echo URL; ?>admin/gestion_commentaire.php">Commentaires</a>
                                <a class="dropdown-item text-warning" href="<?php echo URL; ?>admin/gestion_note.php">Notes</a>
                                <a class="dropdown-item text-warning" href="<?php echo URL; ?>admin/statistique.php">Statistiques</a>
                            </div>
                        </li>

                        <?php } ?>
                        <!-- /liens pour l'admin -->

                    </ul>
                    <form class="d-flex" method="GET" action="<?php echo URL; ?>index.php">
                    <label  class="text-warning"for="myDataList" class="form-label">Chercher un produit :</label>
                    <input class="form-control" list="datalistOptions" id="myDataList" name="recherche" placeholder="rechercher">
                    <datalist id="datalistOptions"></datalist>
                    <button class="btn btn-outline-dark text-warning w-80">rechercher</button>
                    </form>
                </div>
            </div>
            <!-- JQUERY CDN -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="crossorigin="anonymous"></script>
            <script src="assets/js/app.js"></script>
        </nav>
        
                    