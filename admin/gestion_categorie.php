<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

        if( user_is_admin() == false ) {
            header('location:../connexion.php');
            }

        //--------------------------------------
        //--------------------------------------
        //--------------------------------------
        //-------------DELETE MEMBER------------
        //--------------------------------------
        //--------------------------------------

if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_categorie']) ) {
    
    $suppression = $pdo->prepare("DELETE FROM categorie WHERE id_categorie = :id_categorie");// prepare
    $suppression->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);// select target
    $suppression->execute(); // execute
}
    //--------------------------------------
    //--------------END DELETE MEMBER-------
    //--------------------------------------    

        $titre = '';
        $motcles = '';
        $id_categorie='';
        
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //CATEGORY MODIFICATION
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------

        if( isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_categorie']) ){
        // to modify catégory i propose data who's already saved to only change the spécific value
        // a request tu get the information of category and a fetch in the form
        $modification = $pdo->prepare("SELECT * FROM categorie WHERE id_categorie = :id_categorie");
        $modification->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
        $modification->execute();

        $infos_categorie = $modification->fetch(PDO::FETCH_ASSOC);

        $titre = $infos_categorie['titre'];
        $motcles = $infos_categorie['motcles'];
        $id_categorie = $infos_categorie['id_categorie'];

        }
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        // END OF MODIFICATION
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------------

        //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    // ENREGISTREMENT & MODIFICATION ARTICLE
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
        // cheking the existence in POST
        if ( isset($_POST['titre']) &&
            isset($_POST['motcles']) 
        ){
 
 
            $titre = trim($_POST['titre']);
            $motcles = trim($_POST['motcles']);

            $erreur = false;

        // FOR MODIFICATION
        // cheking if id_categorie exist
        if( !empty($_POST['id_categorie']) ) {
            $id_categorie = trim($_POST['id_categorie']);
        }


        

    $erreur = false;
    // CONTROL---------------------------------------------------------------------------------------------------------------------------------------------
    // TITRE
    if( iconv_strlen($titre) < 4 || iconv_strlen($titre) > 14) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Le nom de la  catégorie doit contenir entre 4 et 14 caractères merci.</div>';
    }
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $titre);
            
    if($verif_caractere == false) {
        // cas d'erreur
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Caractère autorisé pour le titre : a-z 0-9 ._- </div>';
    }
     
          // - category's name possibility
          $verif_titre = $pdo->prepare("SELECT * FROM categorie WHERE titre = :titre");
          $verif_titre->bindParam(':titre' , $titre, PDO::PARAM_STR);
          $verif_titre->execute();
          if( $verif_titre->rowCount() > 0 && empty($id_categorie)) {
              $erreur = true;
              $msg .= '<div class="alert alert-danger mb-3">Attention,<br>Ce titre est déjà pris</div>';

          } 
          //saving in the database
            if($erreur == false){
                

                if(empty($id_categorie)) {
                    //if $id_categorie is empty : INSERT
                    $enregistrement = $pdo->prepare("INSERT INTO categorie (titre, motcles) VALUES (:titre, :motcles)");

            }else{
                // ELSE UPDATE
                $enregistrement = $pdo->prepare("UPDATE categorie SET  titre = :titre, motcles = :motcles WHERE id_categorie = :id_categorie");
                $enregistrement->bindParam(':id_categorie', $id_categorie, PDO::PARAM_STR);
                header('location:gestion_categorie.php');
            }

            $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
            $enregistrement->bindParam(':motcles', $motcles, PDO::PARAM_STR);
            $enregistrement->execute();

            $msg .= '<div class="alert alert-success text-center mb-3">catégorie ajouter</div>';
            // avoid to sendback the same datas 
            header('location:gestion_categorie.php');
            }


}


        //------GETTING MEMBER-------------
        //--------------------------------------
        //--------------------------------------
    $liste_categorie = $pdo->query("SELECT * FROM categorie ORDER BY  id_categorie");
        //--------------------------------------
        //--------------------------------------
        //--------------------------------------

        //

           



include '../inc/header.inc.php'; 
include '../inc/nav.inc.php';
        
?>
        <main class="container">
            <br>
            <br>
            <br>
            <div class="star p-5 rounded text-center">
            <br>
            <h1><i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i> gestion catégories <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead episode" style="color: yellow;">Only for admin<hr><?php echo $msg; ?></p>             
            </div>

            <div class="row">
                <div class="col-12 mt-5 mb-5">
                <form class="row border rounded  bg-dark text-light mx-auto " method="post" action="">
                    <div class="col-12 text-center">
                        <div class="mb-3">
                        <p class="episode">Ajout d'une catégorie</p>
                        </div>
                        <div class="mb-3">
                            <label for="titre" class="form-label text-light"><i class="text-light fas fa-user-astronaut"></i> titre </label>
                            <input type="text" class="form-control w-50 mx-auto" id="titre" name="titre" value="<?php echo $titre; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="motcles" class="form-label text-light"><i class="text-light fas fa-user-astronaut"></i> Mots-clés </label>
                            <textarea  class="form-control w-50 mx-auto" id="motcles" name="motcles"><?php echo $motcles; ?></textarea>
                        </div>
                        <div class="mb-3 ">
                            <button type="submit" class="btn btn-outline-success " id="enregistrer" ><i class="fas fa-plus"></i> enregistrer <i class="fas fa-plus"></i></button>
                        </div>

                    </div>
                </form>

                </div>
            </div>
            <div class="row"> 
                    <table class="table border-dark rounded text-center bg-white">
                    <thead  class="star sw text-warning  border  border-warning ">
                        <tr>
                            <th>id</th>
                            <th>nom</th>
                            <th>mots-clés</th>
                            <th>Suppr.</th>
                            <th>Modif.</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            
                        while($categorie= $liste_categorie->fetch(PDO::FETCH_ASSOC)){
                                echo '<tr>
                                <td>'. $categorie['id_categorie'] . '</td>
                                <td>'. $categorie['titre'] . '</td>
                                <td>'.$categorie['motcles'].'</td>
                                <td><a href="?action=supprimer&id_categorie=' . $categorie['id_categorie'] . '" class="btn btn-danger" onclick="return (confirm(\'êtes vous sûr ?\'))"><i class="far fa-trash-alt"></i></a></td>
                                <td><a href="?action=modifier&id_categorie=' . $categorie['id_categorie'] . '" class="btn btn-warning"><i class="far fa-edit text-white"></i></a></td>
                                </tr>';

                                }


                    ?>
                    </tbody>
                    </table>
            </div>



        </main>
<?php
include '../inc/footer.inc.php';