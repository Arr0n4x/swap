<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

// Restriction d'accès, si l'utilsiateur est déjà connecté, on redirige vers profil.php
  if( user_is_connected() == true) {
      header('location:profil.php');
  }

    $pseudo = '';
    $mdp = '';
    $nom = '';
    $prenom = '';
    $telephone = '';
    $email = '';
    $civilite = '';

    // on vérifie l'existence des éléments dans POST
    if ( isset($_POST['pseudo']) &&
         isset($_POST['mdp']) &&
         isset($_POST['nom']) &&
         isset($_POST['prenom']) &&
         isset($_POST['telephone']) &&
         isset($_POST['email']) &&
         isset($_POST['civilite']) 
     ){
            

            // on place chaque informations dans une variable plus simple en écriture et on applqieu un trim au passage.
            $pseudo = trim($_POST['pseudo']);
            $mdp = trim($_POST['mdp']);
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $telephone = trim($_POST['telephone']);
            $email = trim($_POST['email']);
            $civilite = trim($_POST['civilite']);
           

            // Controles : 
            // - taille du pseudo
            // - carcatères présents dans le pseudo 
            // - disponibilité du pseudo
            // - format du pseudo
            // - format du mail
            // - le mdp ne doit pas être vide

            // création d'une variavble de controle qui nous permettra de savoir s'il y a eu des erreurs dans nos test.
            $erreur = false;
            // Taille du pseudo entre 4 et 14 inclus
            if( iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14) {
                $erreur = true;
                $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Le pseudo c\'est entre 4 et 14 caractères boloss.</div>';
            }
            // Pour vérifier les charactères du pseudo, nous devons utiliser une expression régulière (regex)
            // Une regex, n'est pas du php, on s'en sert via des fonction de php
            //preg_match( ) est une fonction prédéfinie permettant de tester une chaine selon une expression régulière et on obtient true si les caractèresautorisés par la regex. Sinon on obtient false.
            $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
            
            if($verif_caractere == false) {
                // cas d'erreur
                $erreur = true;
                $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Caractère autorisé pour le pseudo : a-z 0-9 ._- ...boloss</div>';
            }


            // - disponibilité du pseudo 
            // Pour savoir si le pseudo est disponible nous devons faire une requet en bdd, si on récupère une ligne, le pseudo est indisponible sinon ok
            $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
            $verif_pseudo->bindParam(':pseudo' , $pseudo, PDO::PARAM_STR);
            $verif_pseudo->execute();


            if( $verif_pseudo->rowCount() > 0) {
                $erreur = true;
                $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Ce nom est déjà pris jeune padawan</div>';


            }
            // contrôle sur le format du mail
            if( filter_var($email, FILTER_VALIDATE_EMAIL) == false ) {
                $erreur = true;
                $msg.= '<div class="alert alert-danger mb-3">Attention,<br>Format du mail invalide.</div>';
            } 

            // controle sur le mdp
            if( iconv_strlen($mdp) < 1 ) {
                $erreur = true;
                $msg .= '<div class="alert alert-danger mb-3">Attention,<br>invalide ce mot de passe est </div>';
            }
            // ou if(empty($mdp)){
            // $erreur = true;
            //  $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Le mdp est vide.</div>';
            //}
            if($erreur == false){
                // cryptage du mdp (hashage)
                $mdp = password_hash($mdp, PASSWORD_DEFAULT);

                // on lance le insert into : 
                $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :telephone, :email, :civilite, 1, NOW())");            
            // 1 = membre
            // 2 = admin
            $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
            $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $enregistrement->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
            $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
            $enregistrement->execute();

            $msg .= '<div class="alert alert-success text-center mb-3">Welcome to the galaxy far away of swap</div>';
            // Pour éviter de renvoyer les données lors du rechargement de page, on envoie sur connexion
            header('location:connexion.php');
             /*
                /*
                créez deux compte : 
                login : admin
                mdp : admin
                //--------------
                login : test
                mdp : test
                */
            }
    }
include 'inc/header.inc.php'; 
 include 'inc/nav.inc.php';
        
?>
        <main class="container">
            <br>
            <br>
            <br>
            <?php  //echo '<pre>' ; print_r($_POST); echo '</pre>';  ?> 
            <div class="star p-5 rounded text-center">
                <h1><i class="fab fa-mandalorian text-light"></i> inscription <i class="fab fa-mandalorian text-light"></i></h1>
                <p class="lead episode" style="color: yellow;">Do you wanna be swaped ?<hr><?php echo $msg; ?></p>                
            </div>
            <div class="row">
                <div class="col-12 mt-3 p-5">
                <form class="row border rounded  bg-dark text-light mx-auto " method="post" action="">
                        <div class="col-sm-12 text-center  ">
                            <div class="mb-3">
                                <label for="pseudo" class="form-label text-light"><i class="text-light fas fa-user-astronaut"></i> Pseudo </label>
                                <input type="text" class="form-control w-50 mx-auto" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="mdp" class="form-label text-light"><i class="fas fa-journal-whills"></i> Mot de passe </label>
                                <input type="text" class="form-control w-50 mx-auto" id="mdp" name="mdp" value="<?php echo $mdp; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="nom" class="form-label text-light"><i class="fab fa-galactic-senate"></i> Nom</label>
                                <input type="text" class="form-control w-50 mx-auto" id="nom" name="nom" value="<?php echo $nom; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label text-light"><i class="fas fa-jedi"></i> Prénom</label>
                                <input type="text" class="form-control w-50 mx-auto" id="prenom" name="prenom" value="<?php echo $prenom; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="telephone" class="form-label text-light"> <i class="text-light fas fa-phone-alt"></i> Telephone </label>
                                <input type="text" class="form-control w-50 mx-auto" id="telephone" name="telephone" value="<?php echo $telephone; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-light"><i class="text-light fas fa-at"></i> Email </label>
                                <input type="text" class="form-control w-50 mx-auto" id="email" name="email" value="<?php echo $email; ?>">
                            </div>                            
                            <div class="mb-3 ">
                                <label for="civilite" class="form-label text-light "><i class="text-light fas fa-venus-mars"></i> Civilité </label>
                                <select class="form-control w-50 mx-auto " id="civilite" name="civilite" value="<?php echo $civilite; ?>">
                                    <option value="m">homme</option>
                                    <option value="f">femme</option>
                                </select>
                            </div>
                            <div class="mb-3 mt-4">
                                <button type="submit" class="btn btn-outline-dark border-light bg-secondary w-80" id="inscription" > Inscription <i class="fas fa-arrow-circle-right"></i></button>
                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </main>
<?php
include 'inc/footer.inc.php';