<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';



if (isset($_GET['id_membre'])) {
    // recuperation des infos du membre selectionne sur la fiche annonce
    $profil_membre_annonce = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $profil_membre_annonce->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $profil_membre_annonce->execute();


    if ($profil_membre_annonce->rowCount() > 0) {
    $membre_info = $profil_membre_annonce->fetch(PDO::FETCH_ASSOC);

    // Requete d'affichage des info des annonces du membre selectionné
    $id_membre = $_GET['id_membre'];


    $membre_post = $pdo->prepare("SELECT * FROM membre, note WHERE  membre_id2 = :id_membre AND membre_id1 = id_membre");
    $membre_post->bindParam('id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $membre_post->execute();
    
  
    $liste_annonces_membre = $pdo->prepare("SELECT * FROM  annonce WHERE membre_id = :id_membre");
    $liste_annonces_membre->bindParam('id_membre', $id_membre, PDO::PARAM_STR);
    $liste_annonces_membre->execute();

    // recuperation des notes : membre_id2 est l'utilisateur qui est noté
    $info_notes = $pdo->prepare("SELECT * FROM membre, note WHERE  id_membre = :id_membre AND id_membre = membre_id2");
    $info_notes->bindParam('id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $info_notes->execute();

    // recuperation des du membre_id1 = celui qui a deposé la note
    //   $membre_notant = $pdo->prepare("SELECT * FROM membre, note WHERE  id_membre = :id_membre AND id_membre = membre_id2");
    //   $membre_notant->bindParam('id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    //   $membre_notant->execute();



}

else {

      header('location:index.php');
   
}

}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>
        <main class="container">
            <div class="bg-light p-5 rounded text-center">
            <br>
                <h1><i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i> SWAP <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead">It's a long way to the top if you wanna Swap n' Roll<hr><?php echo $msg; ?></p>                
            </div>

           
            <div class="row">
        <div class=" mx-auto col-lg-8 col-sm-12 mt-2 mb-5">
            <div class="p-2 mt-5 rounded text-center shadow-lg border border-seaGreen">
                <h2 class="seaGreen"> Ses info </h2>
            </div>
            <div class="col-8 mt-5 text-warning">
                <p><span class="fw-bold">Prénom :</span> <?php echo $membre_info['prenom']     ?></p>
                <p><span class="fw-bold">Téléphone :</span> <?php echo $membre_info['telephone']     ?></p>
                <div><span class="fw-bold">Notes et avis :</span>
                    <?php 

                    if ($info_notes->rowCount() > 0) {
                        while ($note = $info_notes->fetch(PDO::FETCH_ASSOC)) {
                            // definission des etoiles en fonction de la note
                            if ($note['note'] == 1) {
                                $noteetoilee =  '<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                            } elseif ($note['note'] == 2) {
                                $noteetoilee = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                            } elseif ($note['note'] == 3) {
                                $noteetoilee = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                            } elseif ($note['note'] == 4) {
                                $noteetoilee = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>';
                            } elseif ($note['note'] == 5) {
                                $noteetoilee = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                            }

                            $post_membre = $membre_post->fetch(PDO::FETCH_ASSOC);
                            
                            echo '<p>Posté par ' . $post_membre['pseudo'] . ' : ' . $noteetoilee . ' ' . $note['avis'] . ', le ' . $note['date_enregistrement']    . '</p>';
                        }
                    }

                    ?></div>
            </div>


            <div class="p-2 mt-5 rounded text-center shadow-lg border border-seaGreen">
                <h2 class="seaGreen">Ses annonces</h2>
            </div>
            <div class="row mt-2 mb-3">
                <?php

                while ($annonce = $liste_annonces_membre->fetch(PDO::FETCH_ASSOC)) {
                    echo  '<div class="col-3">
                             <div class=" bg-light border-dark border">
                             <img  src="' . URL . 'assets/img_annonce/' . $annonce['photo'] . '
                            " class="card-img-top img-fluid" alt="photo_produit">
                            <div class="card-body"><h4>' . $annonce['titre'] . '</h4>
                           <button type="button" class="btn btn-outline-dark"><a href="fiche_annonce.php?id_annonce=' . $annonce['id_annonce'] . '
                            "style="text-decoration: none; color: black;">Voir l\'annonce</a></button></div></div></div>';
                }

                ?>
            </div>
        </div>



        </main>
<?php
include 'inc/footer.inc.php';