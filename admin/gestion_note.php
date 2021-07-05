<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';


// Si l'utilisateur n'est pas admin on redirige vers connexion.php
if( user_is_admin() == false ) {
    header('location:../connexion.php');
    }

    $info_notes = $pdo->query("SELECT * FROM note ");
// Recuperation des nom des membres qui notent
$info_membre_1 = $pdo->query("SELECT * FROM membre AS m, note AS n WHERE m.id_membre = n.membre_id1");

// Recuperation de la personne qui a été notée

$info_membre_2 = $pdo->query("SELECT * FROM membre AS m, note AS n WHERE m.id_membre = n.membre_id2");





include '../inc/header.inc.php'; 
include '../inc/nav.inc.php';
        
?>
        <main class="container">
            <br>
            <br>
            <br>
            <div class="star p-5 rounded text-center">
            <br>
            <h1><i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i> gestion notes <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead episode" style="color: yellow;">It's a long way to the top if you wanna Swap n' Roll<hr><?php echo $msg; ?></p>                
            </div>

            <div class="row">
                <div class="col-12">

                <table class="table border rounded text-center bg-secondary">
                    <thead  class="star sw text-white  border  border">
                        <tr>
                            <th>Id note</th>
                            <th>Id membre noté</th>
                            <th>Id membre donnant la note</th>
                            <th>Note</th>
                            <th>Avis</th>
                            <th>Date enregistrement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
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
                            
                                    $membre1 = $info_membre_1->fetch(PDO::FETCH_ASSOC);
                                    $membre2 = $info_membre_2->fetch(PDO::FETCH_ASSOC);

                                echo '<tr>
                                <td>'. $note['id_note'] . '</td>
                                <td>'. $membre1['pseudo'] .'</td>
                                <td>'.$membre2['pseudo'].'</td>
                                <td>'.$noteetoilee.'</td>
                                <td>'.$note['avis'].'</td>
                                <td>'.$note['date_enregistrement'].'</td>
                                <td><a href="?action=supprimer&id_note=' . $note['id_note'] . '" class="btn btn-danger" onclick="return (confirm(\'êtes vous sûr ?\'))"><i class="far fa-trash-alt"></i></a></td>
                                </tr>';

                                
                               
                                }
                            }
                            

                    ?>
                    </tbody>
                    </table>

                </div>
            </div>



        </main>
<?php
include '../inc/footer.inc.php';