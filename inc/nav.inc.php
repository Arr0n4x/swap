
<?php
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
                            </div>
                        </li>

                        <?php } ?>
                        <!-- /liens pour l'admin -->

                    </ul>
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" id="search" placeholder="Rechercher" aria-label="Search">
                        <button class="btn btn-outline-light text-warning" type="submit">Rechercher</button>
                    </form>
                </div>
            </div>
        </nav>
        
                    