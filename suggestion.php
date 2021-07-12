<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';
$search=$_GET['myInputValue'];
$search=htmlspecialchars($search);
$stmt = $pdo->query("SELECT  description_longue FROM annonce WHERE   description_longue LIKE '%".$search."%' ");
$suggestion = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($suggestion);
