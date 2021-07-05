<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

// ------ VALID USER : ADMIN------------
//--------------------------------------
//--------------------------------------
    if( user_is_admin() == false ) {
    header('location:../connexion.php');
    }
//--------------------------------------
//--------------------------------------
//--------------------------------------

//-------------DELETE MEMBER------------
//--------------------------------------
//--------------------------------------

if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre']) ) {
    // si l'indice action existe dans $_GET et si sa valeur est égal à supprimmer && et si id_article existe et n'est pas vide dans $_GET
    // Requete delete basée sur l'id_article pour supprimer l'article  en question.
    $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");// preparer la requete
    $suppression->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);// selectionner la cible de la requete
    $suppression->execute(); // executer la requete 
}
//--------------------------------------
//--------------------------------------
//--------------------------------------

//------RECUPERATION MEMBRE-------------
//--------------------------------------
//--------------------------------------
$liste_membre = $pdo->query("SELECT id_membre, pseudo, nom, prenom, telephone, email, civilite, statut, date_enregistrement FROM membre ORDER BY  nom");
//--------------------------------------
//--------------------------------------
//--------------------------------------



include '../inc/header.inc.php'; 
include '../inc/nav.inc.php';
        
?>
        <main class="container">
            <br>
            <br>
            <br>
            <div class="star p-5 rounded text-center">
            <br>
            <h1><i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i> gestion membres <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead episode" style="color: yellow;">It's a long way to the top if you wanna Swap n' Roll<hr><?php echo $msg; ?></p>             
            </div>
            <br>
            <br>
            <br>
            <div class="row"> 
                    <table class="table border-dark rounded text-center bg-white">
                    <thead  class="star sw text-warning  border  border-warning ">
                        <tr>
                            <th>id</th>
                            <th>pseudo</th>
                            <th>nom</th>
                            <th>prenom</th>
                            <th>telephone</th>
                            <th>email</th>
                            <th>civilité</th>
                            <th>statut</th>
                            <th>membre depuis</th>
                            <th>Suppr</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            
                        while($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)){
                                echo '<tr>';
                                

                                foreach($membre AS $indice => $valeur) {
                                    if($indice == 'photo') {
                                        echo'<td><img src="' . URL . 'assets/img_membres/' . $valeur . '" width="70" class="img_fluid" alt="image produit">';
                                    }else{
                                        echo '<td>' . $valeur . '</td>';
                                    }
                                }

                                // Rajout liens pour l'action supprimer
                                echo '<td><a href="?action=supprimer&id_membre=' . $membre['id_membre'] . '" class="btn btn-danger" onclick="return (confirm(\'êtes vous sûr ?\'))"><i class="far fa-trash-alt"></i></a></td>';
                                echo '</tr>';
                               
                        }

                    ?>
                    </tbody>
                    </table>
            </div>



        </main>
<?php
include '../inc/footer.inc.php';