<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


 // User must be connected and admin.
if( user_is_connected() == false ) {
    header('location:../connexion.php');
}

$titre = '';
$description_courte = '';
$description_longue = '';
$prix = '';
$categorie = '';
$photo1 = '';
$photo2 = '';
$photo3 = '';
$photo4 = '';
$photo5 = '';
$pays = '';
$ville = '';
$adresse = '';
$cp ='';
$id_annonce='';



$recup_categorie = $pdo->query('SELECT * FROM categorie ORDER BY titre');

//----------------------------------------------------------------
//----------------------------------------------------------------
// Modification d'une annonce
//----------------------------------------------------------------
//----------------------------------------------------------------
if(!empty($_POST['id_annonce'])){
    $id_annonce=trim($_POST['id_annonce']);
}
if(isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_annonce'])){
    // Pour la modification d'un annonce il faut proposer à l'utilisateur les données déjà enregistrées afin qu'il ne change que la ou les valeurs qu'il souhaite
    // Une requete pour récupérer les infos de cette annonce, un fetch et on affiche dasn le form via les variables déjà en place dans le form
    
    $modification = $pdo->prepare('SELECT * FROM annonce WHERE id_annonce = :id_annonce');
    $modification->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $modification->execute();
  
    $infos_annonce = $modification->fetch(PDO::FETCH_ASSOC);
    $id_annonce = $infos_annonce['id_annonce'];
    $titre = $infos_annonce['titre'];
    $description_courte = $infos_annonce['description_courte'];
    $description_longue = $infos_annonce['description_longue'];
    $prix = $infos_annonce['prix'];
    $photo = $infos_annonce['photo'];
    $pays = $infos_annonce['pays'];
    $ville = $infos_annonce['ville'];
    $adresse = $infos_annonce['adresse'];
    $cp = $infos_annonce['cp'];
    $membre = $infos_annonce['membre_id'];
    $categorie = $infos_annonce['categorie_id'];
  }
  
  
  //----------------------------------------------------------------
  //----------------------------------------------------------------
  // Fin modification d'un annonce
  //----------------------------------------------------------------
  //----------------------------------------------------------------




// cheking all the isset
if(isset($_POST['titre']) &&
   isset($_POST['description_courte']) &&
   isset($_POST['description_longue']) &&
   isset($_POST['prix']) &&
   isset($_POST['categorie']) &&
   isset($_POST['pays']) &&
   isset($_POST['ville']) &&
   isset($_POST['adresse']) &&
   isset($_POST['cp'])) { 

    $titre = trim($_POST['titre']);
    $description_courte = trim($_POST['description_courte']);
    $description_longue = trim($_POST['description_longue']);
    $prix = trim($_POST['prix']);
    $categorie = $_POST['categorie'];
    $pays = trim($_POST['pays']);
    $ville = trim($_POST['ville']);
    $adresse = trim($_POST['adresse']);
    $cp = trim($_POST['cp']);
    // error management
    $erreur = false;
    // price control
    if(!is_numeric($prix)) {
        $prix = 0;
        $msg .= '<div class="alert alert-warning" role="alert"> Cet article n\'ayant pas un prix, le prix a été mis à zéro.</div>';
    }
    // management on picture
    //
    if( !empty($_FILES['photo1']['name']) ) {
        // extension control and extension accepted : jpg, jpeg, png, gif, webp
            $tab_extension = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        // on découpe la  chaine depuis la fin pour récupérer ce qui se trouve après le dernier "."
            $extension1 = pathinfo($_FILES['photo1']['name'], PATHINFO_EXTENSION);
            $extension2 = pathinfo($_FILES['photo2']['name'], PATHINFO_EXTENSION);
            $extension3 = pathinfo($_FILES['photo3']['name'], PATHINFO_EXTENSION);
            $extension4 = pathinfo($_FILES['photo4']['name'], PATHINFO_EXTENSION);
            $extension5 = pathinfo($_FILES['photo5']['name'], PATHINFO_EXTENSION);

        // add a reference to avoid saving the same file twice ( reference is unique)
        //  create an aleatory number with a random_int() that will be concatenated with the photo's name
        if(!empty($_FILES['photo1']['name'])){
            $random = random_int(1, 1000);
            $photo1 = $random . '-' . $_FILES['photo1']['name'];
        }

        

       
        
            
        // checking extension
        // in_array() back true or false if a value is a part of a group of values in a array
        //photo1
        if( in_array($extension1, $tab_extension) ) {
            // On enlève les espaces et on les remplace par un -  dans le noms des images : 
            $photo1 = str_replace(' ', '-', $photo1);
            // REGEX of replacement caracter
            $photo1 = preg_replace('/[^A-Za-z0-9.\-]/', '', $photo1);
            // copy() // predefined function to copy a file from a location provided in 1st argument to a folder provided in 2nd with the desired name
            copy($_FILES['photo1']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img_annonce/' . $photo1);
        } else {
            // error case
            $erreur = true;
            $msg .= '<div class="alert alert-warning" role="alert">Format de l\'image invalide, formats acceptés : jpg / jpeg / png / gif / webp.</div>';
        }
        //photo2
        if(!empty($_FILES['photo2']['name'])) {
                $random = random_int(1, 1000);
                $photo2 = $random . '-' . $_FILES['photo2']['name'];

            if( in_array($extension2, $tab_extension) ) {
            
            $photo2 = str_replace(' ', '-', $photo2);
            $photo2 = preg_replace('/[^A-Za-z0-9.\-]/', '', $photo2);
            copy($_FILES['photo2']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img_annonce/' . $photo2);
            } else {
            $erreur = true;
            $msg .= '<div class="alert alert-warning" role="alert">Format de l\'image invalide, formats acceptés : jpg / jpeg / png / gif / webp.</div>';
            }
        }
        //photo3
        if(!empty($_FILES['photo3']['name'])) {
            
                $random = random_int(1, 1000);
                $photo3 = $random . '-' . $_FILES['photo3']['name'];
            
            if( in_array($extension3, $tab_extension) ) {
            $photo3 = str_replace(' ', '-', $photo3);
            $photo3 = preg_replace('/[^A-Za-z0-9.\-]/', '', $photo3);
            copy($_FILES['photo3']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img_annonce/' . $photo3);
            } else {
            $erreur = true;
            $msg .= '<div class="alert alert-warning" role="alert">Format de l\'image invalide, formats acceptés : jpg / jpeg / png / gif / webp.</div>';
            }
        }
        //photo4
        if(!empty($_FILES['photo4']['name'])) {
            
                $random = random_int(1, 1000);
                $photo4 = $random . '-' . $_FILES['photo4']['name'];
            
            if( in_array($extension4, $tab_extension) ) {
            $photo4 = str_replace(' ', '-', $photo4);
            $photo4 = preg_replace('/[^A-Za-z0-9.\-]/', '', $photo4);
            copy($_FILES['photo4']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img_annonce/' . $photo4);
            } else {
            $erreur = true;
            $msg .= '<div class="alert alert-warning" role="alert">Format de l\'image invalide, formats acceptés : jpg / jpeg / png / gif / webp.</div>';
            }
        }
        //photo5
        if(!empty($_FILES['photo5']['name'])) {
            
                $random = random_int(1, 1000);
                $photo5 = $random . '-' . $_FILES['photo5']['name'];
            
            if( in_array($extension5, $tab_extension) ) {
        //   $photo5 = $random_int(1, 1000) . '-' . $_FILES['photo5']['name'];
            $photo5 = str_replace(' ', '-', $photo5);
            $photo5 = preg_replace('/[^A-Za-z0-9.\-]/', '', $photo5);
            copy($_FILES['photo5']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img_annonce/' . $photo5);
            } else {
            $erreur = true;
            $msg .= '<div class="alert alert-warning" role="alert">Format de l\'image invalide, formats acceptés : jpg / jpeg / png / gif / webp.</div>';
            }
        }
        // registration on Database
    }
        if( $erreur == false ) {
            if(empty($id_annonce)){
            $enregistrement_photo = $pdo->prepare('INSERT INTO photo (id_photo, photo1, photo2, photo3, photo4, photo5) VALUES(NULL, :photo1, :photo2, :photo3, :photo4, :photo5)');
            $enregistrement_photo->bindParam(':photo1', $photo1, PDO::PARAM_STR);
            $enregistrement_photo->bindParam(':photo2', $photo2, PDO::PARAM_STR);
            $enregistrement_photo->bindParam(':photo3', $photo3, PDO::PARAM_STR);
            $enregistrement_photo->bindParam(':photo4', $photo4, PDO::PARAM_STR);
            $enregistrement_photo->bindParam(':photo5', $photo5, PDO::PARAM_STR);
            $enregistrement_photo->execute();
            $id_photo = $pdo->lastInsertId();
                
        
            $recup_categorie_id = $recup_categorie->fetch(PDO::FETCH_ASSOC);
            $id_categorie = $recup_categorie_id['id_categorie'];
            $enregistrement_annonce = $pdo->prepare("INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, membre_id, photo_id, categorie_id, date_enregistrement) VALUES (:titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, :membre_id, :photo_id, :categorie_id, NOW())");
            $enregistrement_annonce->bindParam(':titre', $titre, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':description_courte', $description_courte, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':description_longue', $description_longue, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':prix', $prix, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':photo', $photo1, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':pays', $pays, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':ville', $ville, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':cp', $cp, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':photo_id', $id_photo, PDO::PARAM_STR);
            $enregistrement_annonce->bindParam(':categorie_id', $_POST['categorie'] , PDO::PARAM_STR);
            $enregistrement_annonce->execute();
            
            header('location:depot_annonce.php');
        }else{
             
        
            $recup_categorie_id = $recup_categorie->fetch(PDO::FETCH_ASSOC);
            $id_categorie = $recup_categorie_id['id_categorie'];
            $modification_annonce = $pdo->prepare("UPDATE annonce SET  titre=:titre, description_courte=:description_courte, description_longue=:description_longue, prix=:prix, pays=:pays, ville=:ville, adresse=:adresse, cp=:cp, membre_id=:membre_id, categorie_id=:categorie_id WHERE id_annonce = :id_annonce");
            $modification_annonce->bindParam(':titre', $titre, PDO::PARAM_STR);
            $modification_annonce->bindParam(':description_courte', $description_courte, PDO::PARAM_STR);
            $modification_annonce->bindParam(':description_longue', $description_longue, PDO::PARAM_STR);
            $modification_annonce->bindParam(':prix', $prix, PDO::PARAM_STR);
            $modification_annonce->bindParam(':pays', $pays, PDO::PARAM_STR);
            $modification_annonce->bindParam(':ville', $ville, PDO::PARAM_STR);
            $modification_annonce->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $modification_annonce->bindParam(':cp', $cp, PDO::PARAM_STR);
            $modification_annonce->bindParam(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
            $modification_annonce->bindParam(':categorie_id', $_POST['categorie'] , PDO::PARAM_STR);
            
            $modification_annonce->bindParam(':id_annonce', $id_annonce, PDO::PARAM_STR);
            $modification_annonce->execute();
            
            // header('location:depot_modification')

        }
                    
        }
        var_dump($erreur);
        
} 


//-------------DELETE ANNONCE------------
//--------------------------------------
//--------------------------------------

if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_annonce']) ) {
    // si l'indice action existe dans $_GET et si sa valeur est égal à supprimmer && et si id_annonce existe et n'est pas vide dans $_GET
    // Requete delete basée sur l'id_annonce pour supprimer l'annonce  en question.
    $select_annonce = $pdo->prepare("SELECT * FROM annonce WHERE id_annonce = :id_annonce");
    $select_annonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $select_annonce->execute();
    $annonce_suppr = $select_annonce->fetch(PDO::FETCH_ASSOC);
    $id_photo_supp = $annonce_suppr['photo_id'];
    $suppression = $pdo->prepare("DELETE FROM annonce WHERE id_annonce = :id_annonce");// preparer la requete
    $suppression->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);// selectionner la cible de la requete
    $suppression_id_photo = $pdo->prepare("DELETE FROM photo WHERE id_photo = :photo_id");
    $suppression_id_photo->bindParam(':photo_id',$id_photo_supp, PDO::PARAM_STR);
    $suppression_id_photo->execute();
    $suppression->execute(); // executer la requete
    
}
//--------------------------------------
//--------------------------------------
//--------------------------------------


$membre_id = $_SESSION['membre']['id_membre'];


$liste_annonces = $pdo->query("SELECT id_annonce, titre, description_courte, prix, photo FROM annonce WHERE membre_id = $membre_id ORDER BY  titre");




include 'inc/header.inc.php'; 
include 'inc/nav.inc.php';
?>
       <main class="container ">
       <br>
       <br>
       <br>
            <div class="star p-5 rounded text-center shadow-lg border border-warning ">
                <h1>Déposer votre annonce</h1>
                <hr style="color: yellow;">  
                <p class="lead episode" style="color: yellow;">Balance ton swap<hr><?php echo $msg; ?></p>             
            </div>

            <div class="row">
                <div class="col-12 mt-5">
                <form class="row border p-3 mb-5 rounded star " method="post" action="" enctype="multipart/form-data">
                        <div class="col-sm-6 text-center text-white">
                            <div class="mb-3">
                                <label for="titre" class="form-label"><i class="fas fa-pencil-alt seaGreen"></i> Titre</label>
                                <input type="text" class="form-control rounded" id="titre" name="titre" value="<?php echo $titre; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description_courte" class="form-label"><i class="fas fa-pencil-alt seaGreen"></i> Description courte</label>
                                <textarea class="form-control rounded" id="description_courte" name="description_courte"> <?php echo $description_courte; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description_longue" class="form-label"><i class="fas fa-pencil-alt seaGreen"></i> Description longue</label>
                                <textarea class="form-control rounded" id="description_longue" name="description_longue"><?php echo $description_longue; ?></textarea>
                            </div> 
                            <div class="mb-3">
                                <label for="prix" class="form-label"><i class="fas fa-euro-sign seaGreen"></i> Prix</label>
                                <input type="text" class="form-control rounded" id="prix" name="prix" value="<?php echo $prix; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="categorie" class="form-label"><i class=" seaGreen fas fa-list-ul"></i> Catégorie</label>
                                <select class="form-control rounded" id="categorie" name="categorie">
                                <?php while($categorie = $recup_categorie->fetch(PDO::FETCH_ASSOC)){?>
                                   
                                <option value="<?php echo $categorie['id_categorie'] ?>"> <?php echo $categorie['titre'] ?> </option>
                                
                                <?php } ?>
                                </select>
                                    <div class="mb-3">
                                        <label for="photo1" class="form-label"><i class="far fa-image seaGreen"></i> Photo 1</label>
                                        <input type="file" class="form-control" id="photo1" name="photo1" >
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo2" class="form-label"><i class="far fa-image seaGreen"></i> Photo 2</label>
                                        <input type="file" class="form-control" id="photo2" name="photo2" >
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo3" class="form-label"><i class="far fa-image seaGreen"></i> Photo 3</label>
                                        <input type="file" class="form-control" id="photo3" name="photo3" >
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo4" class="form-label"><i class="far fa-image seaGreen"></i> Photo 4</label>
                                        <input type="file" class="form-control" id="photo4" name="photo4" >
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo5" class="form-label"><i class="far fa-image seaGreen"></i> Photo 5</label>
                                        <input type="file" class="form-control" id="photo5" name="photo5" >
                                    </div>
                            </div>
                        </div>
                        <div class="col-sm-6 text-center text-white">
                            <div class="row">
                            </div>
                            <div class="mb-3">
                                <label for="pays" class="form-label"><i class="fas fa-house-user seaGreen"></i> Pays</label>
                                <input type="text" class="form-control " id="pays" name="pays" value="<?php echo $pays; ?>">
                            </div>
                            <div class="mb-3 ">
                                <label for="adresse" class="form-label"><i class="fas fa-house-user "></i> Adresse</label>
                                <input type="text" class="form-control " id="adresse" name="adresse" value="<?php echo $adresse; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="cp" class="form-label"><i class="fas fa-house-user seaGreen"></i> Code postal</label>
                                <input type="text" class="form-control " id="cp" name="cp" value="<?php echo $cp; ?>">
                                <div style="display: none; color: #f55;" id="error-message"></div>
                            </div>
                            <div class="mb-3">
                                <label for="ville" class="form-label"><i class="fas fa-house-user seaGreen"></i> Ville</label>
                                <input type="text" class="form-control " id="ville" name="ville" value="<?php echo $ville; ?>">
                                  <!-- <select class="form-control " id="ville" name="ville" ></select> -->
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control " id="id_annonce" name="id_annonce" value="<?php echo $id_annonce; ?>">
                            </div>
                            <div class="mb-3">     
                                <button type="submit" class="btn btn-outline-light text-warning episode " id="validation_annonce" >Valider</button>
                            </div> 
                        </div>
                        
                       
                </form>     
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
                            <th>Modif</th>
                            <th>Suppr</th>
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

                                // Rajout de deux liens pour les actions : modifier, supprimer
                                echo '<td><a href="?action=modifier&id_annonce=' . $annonce['id_annonce'] . '" class="btn btn-primary"><i class="far fa-edit"></i></a></td>';
                                echo '<td><a href="?action=supprimer&id_annonce=' . $annonce['id_annonce'] . '" class="btn btn-danger" onclick="return (confirm(\'êtes vous sûr ?\'))"><i class="far fa-trash-alt"></i></a></td>';
                                echo '</tr>';
                     }

                    ?> 
                    </tbody>
                    </table>
                </div>
            </div>
            



        </main>
<?php
include 'inc/footer.inc.php';