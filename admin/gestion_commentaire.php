<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';
                    if( user_is_admin() == false ) {
                    header('location:../connexion.php');
                    }

                     //-------------------------------------
                    //--------------------------------------
                    //--------------------------------------
                    //-------------DELETE COMMENTS----------
                    //--------------------------------------
                    //--------------------------------------

                    if( isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_commentaire']) ) {
                        // si l'indice action existe dans $_GET et si sa valeur est égal à supprimmer && et si id_article existe et n'est pas vide dans $_GET
                        // Requete delete basée sur l'id_article pour supprimer l'article  en question.
                        $suppression = $pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = :id_commentaire");// preparer la requete
                        $suppression->bindParam(':id_commentaire', $_GET['id_commentaire'], PDO::PARAM_STR);// selectionner la cible de la requete
                        $suppression->execute(); // executer la requete 
                        }
                        //--------------------------------------
                        //--------------END DELETE COMMENTS-----
                        //--------------------------------------   


                        
                        //------RECUPERATION MEMBRE-------------
                        //--------------------------------------
                        //--------------------------------------
                        $liste_commentaire = $pdo->query("SELECT * FROM commentaire ORDER BY  id_commentaire");
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
            <h1><i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i> gestion commentaires <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead episode" style="color: yellow;">Analyse commentaire<hr><?php echo $msg; ?></p>           
            </div>

            <div class="row">
                <div class="col-12 mt-5">
                <table class="table border rounded text-center bg-secondary">
                    <thead  class="star sw text-white  border  border">
                        <tr>
                            <th>Id commentaire</th>
                            <th>Id membre</th>
                            <th>Id annonce</th>
                            <th>Commentaire</th>
                            <th>Date d'enregistrement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            
                        while($commentaire = $liste_commentaire->fetch(PDO::FETCH_ASSOC)){
                                echo '<tr>
                                <td>'. $commentaire['id_commentaire'] . '</td>
                                <td>'. $commentaire['membre_id'] . '</td>
                                <td>'.$commentaire['annonce_id'].'</td>
                                <td>'.$commentaire['commentaire'].'</td>
                                <td>'.$commentaire['date_enregistrement'].'</td>
                                <td><a href="?action=supprimer&id_commentaire=' . $commentaire['id_commentaire'] . '" class="btn btn-danger" onclick="return (confirm(\'êtes vous sûr ?\'))"><i class="far fa-trash-alt"></i></a></td>
                                </tr>';

                                
                               
                                }

                        

                    ?>
                    </tbody>
                    </table>
                </div>



        </main>
<?php
include '../inc/footer.inc.php';