<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// ------ VALID USER : ADMIN------------
//--------------------------------------
//--------------------------------------
if (user_is_admin() == false) {
    header('location:../connexion.php');
}
//--------------------------------------
//--------------------------------------
//--------------------------------------

//-------------SUPPRIMER ANNONCE------------
//--------------------------------------
//--------------------------------------

if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_annonce'])) {
    $suppression = $pdo->prepare("DELETE FROM annonce WHERE id_annonce = :id_annonce"); // prepare
    $suppression->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR); // choose target
    $suppression->execute(); // execute
}
//--------------------------------------
//--------------------------------------
//--------------------------------------

//------GET ANNONCE-------------
//--------------------------------------
//--------------------------------------

$liste_annonce = $pdo->query("SELECT annonce.*, pseudo AS membre, categorie.titre AS categorie FROM annonce
LEFT JOIN membre ON membre.id_membre = annonce.membre_id
LEFT JOIN categorie ON categorie.id_categorie = annonce.categorie_id
ORDER BY annonce.id_annonce
");
//--------------------------------------
//--------------------------------------
//--------------------------------------




include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>
<main class="container">
            <div class="star p-5 rounded text-center shadow-lg border border-warning ">
                <h1>Gestion annonce</h1>
                <hr style="color: yellow;">  
                <p class="lead episode" style="color: yellow;">this is the way<hr><?php echo $msg; ?></p>             
            </div>
            <br>
            <br>
            <br>
    <div class="row">
        <table class="table border rounded text-center bg-secondary">
            <thead class="star sw text-white  border  border">
            <tr>
                            <th>Id</th>
                            <th>Titre</th>
                            <th>Description courte</th>
                            <th>Description longue</th>
                            <th>Prix</th>
                            <th>Photo</th>
                            <th>Pays</th>
                            <th>Ville</th>
                            <th>Adresse</th>
                            <th>cp</th>
                            <th>photo_id</th>
                            <th>date d'enregistrement</th>
                            <th>Membre</th>
                            <th>Catégorie</th>
                            <th>suppr</th>
                        </tr>
            </thead>
            <tbody>
               <?php 
              while ($annonce = $liste_annonce->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';

                foreach ($annonce as $indice => $valeur) {
                    if ($indice == 'photo') {
                        echo '<td><img src="' . URL . 'assets/img_annonce/' . $valeur . '" width="100" class="img-fluid" alt="image produit"></td>';
                    } elseif ($indice == 'description_courte') {
                        echo '<td>' . substr($valeur, 0, 15) . ' <a href="">...</a></td>';
                    } else {
                        if($indice !== 'categorie_id' && $indice !== 'membre_id'){
                            echo '<td >' . $valeur . '</td>';
                        }
                        
                    }
                }
               
                echo '<td><a href="?action=supprimer&id_annonce=' . $annonce['id_annonce'] . '" class="btn btn-danger" onclick="return(confirm(\'Êtes vous sûr de supprimer ?\'))"><i class="far fa-trash-alt"></i></a></td>';
                echo '</tr>';
            }


        ?> 
            </tbody>
        </table>
      
    </div>
    </div>
 
</main>
<?php
include '../inc/footer.inc.php';