<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

// user must be connected otherwise he will be sent on connexion page
if (user_is_connected() ) {
    


// Declaration of variables to avoid form's error
$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email ='';
$civilite ='';
$telephone ='';
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //DELETE ACCOUNT
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_SESSION['membre']['id_membre']) ) {
        
        $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
        $suppression->bindParam(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
        $suppression->execute(); 

        session_destroy();
        echo "<script type='text/javascript'>alert('Vos données ont été supprimer');document.location.href = 'inscription.php';
                </script>";

        
    }

    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //END OF DELETE ACCOUNT
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------



    // Recuperation des titre des annonces commentees----------------------
        $info_annonce = $pdo->prepare("SELECT * FROM commentaire AS c, annonce AS a, membre AS M WHERE c.annonce_id = a.id_annonce AND m.id_membre = :membre_id ORDER BY c.date_enregistrement DESC" ) ;
        $info_annonce->bindParam(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
        $info_annonce->execute();
    // Recuperation des commentaire sur nos propres annonces 
        $info_commentaires = $pdo->prepare("SELECT * FROM commentaire AS c, annonce AS a WHERE a.membre_id = ".$_SESSION['membre']['id_membre']." ORDER BY c.date_enregistrement DESC" ) ;
        $info_commentaires->execute();


    //  If all fields are filled in----------------------
        if(isset($_POST['pseudo']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['telephone']) &&isset($_POST['civilite'])) {
         
    // Remove the special characters and spaces and inject the results of the form into the variables----------------------
        $pseudo = trim($_POST['pseudo']);
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $telephone = trim($_POST['telephone']);
        $civilite = trim($_POST['civilite']);
    

    // errors are anticipated----------------------
        $erreur = false;

    // checking mail----------------------
    // - cheking mail's format----------------------
        if( filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $erreur = true;
        $msg .= '<div class="alert alert-warning" role="alert"> Attention, Le format du mail est invalide.</div>';
        }
    // FOR MODIFICATION----------------------
    // We check if the id_membre exists and is not empty: if it does, we are in modification----------------------
        if( !empty($_POST['id_membre']) ) {
        $id_membre = trim($_POST['id_membre']);
        }else{
        $msg .= '<div class="alert alert-warning" role="alert"> Attention, Le format du mail est invalide.</div>';
        }
    // if all is ok initiate the modification----------------------
        if($erreur == false) {
        echo 'hello';
        $verif_id_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
        $verif_id_membre->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
        $verif_id_membre->execute();
        // Il ne faut pas vérifier si la référence  existe dans le cadre d'une modif, donc on rajoute un controle sur id_membre qui doit être vide. Car en cas d'insert, l'id_membre sera vide, en revanche sur une  modif il n'est pas vide.
        if( $verif_id_membre->rowCount() >= 1 ) {
        $enregistrement = $pdo->prepare("UPDATE membre SET  nom = :nom, prenom = :prenom, telephone = :telephone, email = :email, civilite = :civilite WHERE id_membre = :id_membre");
        $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
        $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $enregistrement->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
        $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $enregistrement->execute(); 
        
        session_destroy();
        echo 'if(confirm == ok';
        echo "<script type='text/javascript'>alert('Vos modifications sont bien enregistrés merci de bien vouloir vous re-connecter');document.location.href = 'connexion.php';
        </script>";
                    }
            }

        }
        $membre_id = $_SESSION['membre']['id_membre'];
        $liste_annonces = $pdo->query("SELECT id_annonce, titre, description_courte, prix, photo FROM annonce WHERE membre_id = $membre_id ORDER BY  titre");
}else{
        header('location:connexion.php');
}

        include 'inc/header.inc.php'; 
        include 'inc/nav.inc.php';
        
?>
        <main class="container">
        <br>
        <br>
        <br>
            <div class="star p-5 rounded text-center">
            <br>
                <h1><i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i> profil <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead episode" style="color: yellow;">It's a long way to the top if you wanna Swap n' Roll<hr><?php echo $msg; ?></p>                
            </div>

            <div class="row">
            <div class="col-sm-6 mt-5 mb-5 mx-auto">
            <?php
                if($_SESSION['membre']['civilite'] == 'm') {
                    $civilite = 'homme';
                } else {
                    $civilite = 'femme';
                }

                if($_SESSION['membre']['statut'] == 1) {
                    $statut = 'membre';
                } else {
                    $statut = 'administrateur';
                }
            ?>
            <ul class="list-group">
                <li class="list-group-item star border-warning text-center text-warning episode p-3">Bonjour <?php echo ucfirst($_SESSION['membre']['pseudo']); ?> </li>
                <li class="list-group-item p-3 visually-hidden"><b>N° utilisateur: </b><?php echo $_SESSION['membre']['id_membre']; ?></li>
                <li class="list-group-item p-3"><b>Pseudo : </b><?= $_SESSION['membre']['pseudo']; ?></li>              
                <li class="list-group-item p-3"><b>Nom : </b><?php echo $_SESSION['membre']['nom']; ?></li>
                <li class="list-group-item p-3"><b>Prénom : </b><?php echo $_SESSION['membre']['prenom']; ?></li>
                <li class="list-group-item p-3"><b>Email : </b><?php echo $_SESSION['membre']['email']; ?></li>
                <li class="list-group-item p-3"><b>Telephone : </b><?php echo $_SESSION['membre']['telephone']; ?></li> 
                <li class="list-group-item p-3"><b>Civilité : </b><?php echo $civilite; ?></li>           
                <li class="list-group-item p-3"><b>Statut : </b>vous êtes <?php echo $statut; ?></li>  
            </ul>
            </div>
            </div>
                        
                        <div class="row gx-5 justify-content-center mb-5">
                            <div class="col-1 mb-3">
                            <button type="button " class="btn btn-primary " id="modifprofil">modifier</button>
                            </div>
                            <form class="row border rounded  bg-dark text-light mx-auto hidden" id="formprofil" method="post" action="">
                        <div class="col-sm-12 text-center  ">
                            <div class="mb-3">
                                <input type="text" hidden class="form-control w-50 mx-auto" id="id_membre" name="id_membre" value="<?php echo $_SESSION['membre']['id_membre']; ?>">
                            </div>
                            <div class="mb-3">
                                <input type="text" hidden class="form-control w-50 mx-auto" id="pseudo" name="pseudo" value="<?php echo $_SESSION['membre']['pseudo']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="nom" class="form-label text-light"><i class="fab fa-galactic-senate"></i> Nom</label>
                                <input type="text" class="form-control w-50 mx-auto" id="nom" name="nom" value="<?php echo $_SESSION['membre']['nom']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label text-light"><i class="fas fa-jedi"></i> Prénom</label>
                                <input type="text" class="form-control w-50 mx-auto" id="prenom" name="prenom" value="<?php echo $_SESSION['membre']['prenom']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="telephone" class="form-label text-light"> <i class="text-light fas fa-phone-alt"></i> Telephone </label>
                                <input type="text" class="form-control w-50 mx-auto" id="telephone" name="telephone" value="<?php echo $_SESSION['membre']['telephone']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-light"><i class="text-light fas fa-at"></i> Email </label>
                                <input type="email" class="form-control w-50 mx-auto" id="email" name="email" value="<?php echo $_SESSION['membre']['email']; ?>">
                            </div>                            
                            <div class="mb-3 ">
                                <label for="civilite" class="form-label text-light "><i class="text-light fas fa-venus-mars"></i> Civilité </label>
                                <select class="form-control w-50 mx-auto " id="civilite" name="civilite" value="<?php echo $_SESSION['membre']['civilite']; ?>">
                                    <option value="m">homme</option>
                                    <option value="f">femme</option>
                                </select>
                            </div>
                            <div class="mb-3 mt-4">
                                <button type="submit" class="btn btn-outline-dark border-light bg-success w-80" id="enregistrement" > Enregister <i class="fas fa-arrow-circle-right"></i></button>
                                <button type="submit" class="btn btn-outline-dark border-light bg-warning w-80" id="annuler" > annuler <i class="fas fa-arrow-circle-right"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="col-1">
                    <a href="?action=supprimer&id_membre='<?php $_SESSION['membre']['id_membre'] ?> '" class="btn btn-danger" >Supprimer</a>
                    </div>
                    <div class="star p-5 mt-5 rounded text-center shadow-lg border border-warning ">
                        <h2 style="color: yellow;"> Vos annonces </h2>               
                    </div>
                <div class="col-12 mt-5   rounded p-3">
                    <table class="table bg-light table-bordered  text-center rounded">
                    <thead class="">
                        <tr class="text-white star">
                            <th>id</th>
                            <th>Titre</th>
                            <th>description courte</th>
                            <th>prix</th>
                            <th>photos</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php
                         while($annonce = $liste_annonces->fetch(PDO::FETCH_ASSOC)){
                                 echo '<tr>';
                                foreach($annonce AS $indice => $valeur) {
                                    if($indice == 'photo') {
                                        echo'<td><img src="' . URL . 'assets/img_annonce/' . $valeur . '" width="70" class="img_fluid" alt="image produit">';
                                    }else{
                                        echo '<td>' . $valeur . '</td>';
                                    }
                                }
                            }

                    ?> 
                    </tbody>
                    </table>
                </div>
                <div class="star p-5 mt-5 rounded text-center shadow-lg border border-warning ">
                        <h2 style="color: yellow;"> commentaires reçus </h2>               
                    </div>
                <div class="col-12 mt-5   rounded p-3">
                    <table class="table bg-light table-bordered  text-center rounded">
                    <thead class="">
                        <tr class="text-white star">
                        
                            <th>titre annonce</th>
                            <th>commentaire</th>
                            <th>date </th>
                            <th>répondre</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                         while(($titreannonce = $info_annonce->fetch(PDO::FETCH_ASSOC)) && ($commentaire = $info_commentaires->fetch(PDO::FETCH_ASSOC))){
                                 echo '<tr><td>'.$titreannonce['titre'] .'</td>
                                           <td>'.$commentaire['commentaire'] .'</td>
                                           <td>'.$commentaire['date_enregistrement'].'</td> 
                                           <td> <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#avis">
                                           répondre
                                       </button>
                           
                                       <!-- Modal -->
                                       <div class="modal fade p-4" id="avis" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                           <div class="modal-dialog">
                                               <div class="modal-content">
                                                   <div class="modal-header">
                                                       <h5 class="modal-title" id="exampleModalLabel">Réponse</h5>
                                                       <form class="row" method="post">
                                                       <textarea class="form-control rounded" id="reponse" name="reponse"></textarea>
                                                       <button type="submit" class="btn btn-primary">envoyer</button>
                                                       </form>
                                                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                   </div>
                                               </div>
                                           </div>
                                       </div></td>
                                        </tr>';
                              
                     }
      

                    ?>  
                    </tbody>
                    </table>
                </div>
                

                
                        

            <script src="<?php echo URL; ?>assets/js/script_profil.js"></script>  
        </main>
<?php
include 'inc/footer.inc.php';
?>