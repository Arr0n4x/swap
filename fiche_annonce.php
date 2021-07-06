<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

if (isset($_GET['id_annonce'])) {
    $infos_annonce = $pdo->prepare("SELECT * FROM annonce WHERE id_annonce = :id_annonce");
    $infos_annonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $infos_annonce->execute();


    // Requete d'affichage des photos
    $id_annonce = $_GET['id_annonce'];

    $info_photo = $pdo->prepare("SELECT * FROM annonce, photo WHERE  id_annonce = :id_annonce AND photo_id = id_photo");
    $info_photo->bindParam('id_annonce', $id_annonce, PDO::PARAM_STR);
    $info_photo->execute();

    // Requete de recuperation des info du membre ayant posté l'annonce
    $info_membre = $pdo->prepare("SELECT * FROM annonce, membre WHERE  id_annonce = :id_annonce AND membre_id = id_membre");
    $info_membre->bindParam('id_annonce', $id_annonce, PDO::PARAM_STR);
    $info_membre->execute();

    // on vérifie si on a récupéré un article
    if ($infos_annonce->rowCount() > 0) {
        $infos = $infos_annonce->fetch(PDO::FETCH_ASSOC);
        $membre_info = $info_membre->fetch(PDO::FETCH_ASSOC);

        // On propose d'autres annonces
        $liste_annonces = $pdo->query("SELECT id_annonce, titre, description_courte, prix, photo FROM annonce ORDER BY  date_enregistrement");
    } else {

        header('location:index.php');
    }
} else {
    header('location:index.php');
}


// Si un commentaire est posté via le formulaire en lightbox
if(isset($_POST['comm'])) {
$commentaire = $_POST['comm'];
            
// ON COMMENCE L'ENREGISTREMENT
$enregistre_comm = $pdo->prepare("INSERT INTO commentaire (membre_id, annonce_id, commentaire, date_enregistrement, membre_id_2) VALUES (:membre_id, :annonce_id, :commentaire, NOW(), :membre_id_2)");
$enregistre_comm->bindParam(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
$enregistre_comm->bindParam(':membre_id_2',$membre_info['id_membre'], PDO::PARAM_STR);
$enregistre_comm->bindParam(':annonce_id', $id_annonce, PDO::PARAM_STR);
$enregistre_comm->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
$enregistre_comm->execute();
header('location:index');

}

// Si un avis et une note sont postés via le formulaire en lightbox
if(isset($_POST['notedonnee']) && isset($_POST['avisdonne'])) {
    $note = $_POST['notedonnee'];
    $avis = $_POST['avisdonne'];


                
    // ON COMMENCE L'ENREGISTREMENT
    $enregistre_avis = $pdo->prepare("INSERT INTO note (membre_id1, membre_id2, note, avis, date_enregistrement) VALUES (:membre_id1, :membre_id2, :note, :avis, NOW())");
    $enregistre_avis->bindParam(':membre_id1', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
    $enregistre_avis->bindParam(':membre_id2',$membre_info['id_membre'], PDO::PARAM_STR);
    $enregistre_avis->bindParam(':note', $note, PDO::PARAM_STR);
    $enregistre_avis->bindParam(':avis', $avis, PDO::PARAM_STR);

    $enregistre_avis->execute();
    header('location:index');
    
    }
    // Recuperation des commentaire
    $liste_commentaires = $pdo->prepare("SELECT * FROM commentaire WHERE annonce_id = $id_annonce ORDER BY date_enregistrement DESC" ) ;
    $liste_commentaires->execute();

    





include 'inc/header.inc.php';
 include 'inc/nav.inc.php';
?>
        <main class="container">
            <div class="bg-light star   p-5 rounded text-center">
            <br>
                <h1><i class="fab  fa-rebel text-danger fa-2x faa-burst animated-hover"></i> fiche annonce <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead episode text-warning">It's a long way to the top if you wanna Swap n' Roll<hr><?php echo $msg; ?></p>                
            </div>

            <div class="row">
        <div class="col-6 mt-3">
            <h2 class="text-warning">
                <!-- TITRE - CATEGORIE -->
                <?php echo $infos['titre']; ?></h2>
        </div>
        <div class="col-6 mt-3">
            <!-- LIEN CONTACT -->
            <!-- Ligthbox contact -->
            <button type="button" class="btn bg-seaGreen" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="<?php echo $membre_info['pseudo']; ?>">Contacter le vendeur</button>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Envoyer un message à <?php echo $membre_info['pseudo']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3 text-center ">
                                    <p class="fw-bolder">Appeler le : <?php echo $membre_info['telephone']; ?></p>
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Message :</label>
                                    <textarea class="form-control" id="message-text"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn bg-seaGreen">Envoyer message</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Presnetation de l'annonce -->
    <div class="row mt-2">
        <div class="col-6 mt-2">

            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    // ON met un compteur a 0 afin de pouvoir mettre un 'active' sur la premiere image du carousel
                    $counter = 1;

                    while ($photo = $info_photo->fetch(PDO::FETCH_ASSOC)) {


                        foreach ($photo as $indice => $valeur) {
                            // on recupere les indices des photos de la table photo
                            if ($indice == 'photo1' || $indice == 'photo2'  ||  $indice == 'photo3' ||  $indice == 'photo4'  ||  $indice == 'photo5') {
                                //On affiche les photo que si la variable $valeur n'est pas vide
                                if (!empty($valeur)) {  ?>

                                    <!-- si le compteur est a 1 le carousel sera 'active' -->
                                    <div class="row carousel-item <?php if ($counter === 1) {
                                                                        echo ' active';
                                                                    } ?>">

                                        <img src="<?php echo URL . 'assets/img_annonce/' . $valeur; ?>" alt="" class="d-block h-100 img-thumbnail">
                                    </div>

                    <?php
                                    // On incremente le compteur à chaque tour de la boucle pour l'affichage du carousel
                                    $counter++;
                                }
                            }
                        }
                    }
                    ?>
                    <!-- Bouttons du carousel -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
                  
        </div>
        <div class="col-6 mt-4 text-warning">
            <h3 class="episode">Description : </h3>
            <!--  DESCRIPTION -->
            <?php echo $infos['description_longue']; ?>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-3 mt-2 text-warning">
            <!-- DATE OF PUBLICATION -->
            <i class="fas fa-calendar-alt seaGreen"></i> <?php echo $infos['date_enregistrement']; ?>
        </div>
        <div class="col-3 mt-2 text-warning">
            <!-- PROFIL   -->

            <i class="fas fa-journal-whills"></i> <a href="profil_annonce.php?id_membre=<?php echo $membre_info['id_membre']; ?>">Voir le profil de <?php echo $membre_info['pseudo']; ?></a>
        </div>
        <div class="col-3 mt-2 text-warning">

            <!-- PRICE  -->
         <?php echo $infos['prix']; ?> <i class="fas fa-euro-sign seaGreen"></i>
        </div>
        <div class="col-3 mt-2 text-warning">
            <!-- ADRESS -->
            <i class="fas fa-map-marker-alt seaGreen "></i> <?php echo $membre_info['adresse'] . ', ' . $membre_info['cp'] . ' ' . $membre_info['ville']; ?>
        </div>
    </div>
    <div class="row ">
        <div class="col-12 col-md-6">
            
            <!-- GOOGLE MAP -->
            <!-- 
            echo' <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d20221.45108460531!2d3.1667109410979837!3d50.6887388769978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c32891f6ff8a69%3A0x40af13e816459b0!2s'.$infos['cp'].'%20'.$infos['ville'].'!5e0!3m2!1sfr!2sfr!4v1624952409172!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
              -->
                <?php
              echo'<iframe
                    width="600"
                    height="450"
                    style="border:0"
                    loading="lazy"
                    allowfullscreen
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDhOVnzQ_V-1J-Ck3pIR-TYe5CQWtWomb4
                        &q='.$infos['cp'].','.$infos['ville'].'">
                    </iframe>'
                ?>

        </div>
        <div class="col-12 col-md-6">
            <!-- COMMENTAIRES  -->
            <div class="star p-5 table-responsive rounded text-center shadow-lg border border-warning max">
                        <h2 style="color: yellow;"> commentaires  </h2>    
            
            <table class="table bg-light table-bordered  text-center rounded">
                    <thead class="">
                        <tr class="text-white star">
                        
                             <th>nom</th> 
                            <th>commentaire</th>
                            <th>date </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                         while($commentaire = $liste_commentaires->fetch(PDO::FETCH_ASSOC)){
                             $rec_membre = $pdo->query('SELECT pseudo FROM membre WHERE id_membre = '.$commentaire['membre_id'].'');
                             $membre= $rec_membre->fetch(PDO::FETCH_ASSOC);
                                 echo '<tr><td>'.$membre['pseudo'].'</td>
                                 <td>'.$commentaire['commentaire'] .'</td>
                                 <td>'.$commentaire['date_enregistrement'].'</td></tr>' ;      
                     }
                    ?>  
                    </tbody>
                    </table>
            </div>
    </div>
    <!-- LightBox avis et commentaire si l'utilisateur est connecté-->
    <?php   if(user_is_connected()== true){           ?>
    <div class="row mt-4">
        <div class="col-6">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#avis">
                Déposer une note ou poser une question
            </button>

            <!-- Modal -->
            <div class="modal fade p-4" id="avis" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Avis ou commentaire</h5>
                            <form class="row" method="post">
                                <select name="noter" id="noter">
                                    <option value="avis">Noter/donner un avis sur le vendeur</option>
                                    <option value="commentaire">Laisse un commentaire sur l'annonce</option>
                                </select>

                        </div>
                        <!-- <div class="modal-body">
                ...
            </div> -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Choisir</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- Si l'utilisteur connete decide de laisser un commentaire  -->
                <?php
    if(isset($_POST['noter']) && $_POST['noter'] == 'commentaire' ) {
            echo '<form method="post" class="row border p-3  shadow  mb-5 rounded w-50 mx-auto">
            <div class="col-11 col-lg-6 text-center text-warning mx-auto">
            <div class="mb-3 ">
            <label for="form_commentaire">Laisser un commentaire</label>
            <textarea name="comm" id="comm" class="form-control rounded mx-auto " required ></textarea> 
            <input type="submit" class="btn btn-secondary">
            </div>
            </div>
            </form>';

        }elseif(isset($_POST['noter']) && $_POST['noter'] == 'avis'){
            echo '<form method="post" class="row border p-3 text-warning shadow mb-5 rounded w-50 mx-auto">
            <div class="col-11 text-center mx-auto">
            <div class="mb-3  text-center">
            <label for="notedonnee">Donnez une note sur 5</label>
            <select name="notedonnee" id="notedonnee"  class="form-control rounded-pill w-25 mx-auto text-center">
            <option value="1" class="text-center">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            </select>
            <label for="avisdonne">Donnez votre avis</label>
            <textarea  class="form-control rounded" name="avisdonne"></textarea>
            <input type="submit" class="btn btn-secondary mt-3">
            </div>
            </div>
            </form>';


        }
        ?>
   <div class="row">
       <div class="col-12">
           <h2 class="grayS afterh2">Autres annonces</h2>

       </div>
   </div>
    <div class="row mt-2 mb-3">

        <?php

        while ($annonce = $liste_annonces->fetch(PDO::FETCH_ASSOC)) {
            echo  '<div class="col-2">
                             <div class=" bg-light border-dark border">
                             <img  src="' . URL . 'assets/img_annonce/' . $annonce['photo'] . '
                            " class="card-img-top img-fluid" alt="photo_produit">
                            <div class="card-body"><h4>' . $annonce['titre'] . '</h4>
                           <button type="button" class="btn btn-outline-dark"><a href="fiche_annonce.php?id_annonce=' . $annonce['id_annonce'] . '
                            "style="text-decoration: none; color: black;">Voir l\'annonce</a></button></div></div></div>';
            }

        ?>
    </div>
   
</main>
<?php
include 'inc/footer.inc.php';