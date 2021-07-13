<?php
include 'inc/init.inc.php';
 include 'inc/functions.inc.php';
$search=$_GET['myInputValue'];
// $search=strtoupper($search);
$stmt = $pdo->query("SELECT description_courte FROM annonce WHERE description_courte LIKE '%".$search."%' ");
$suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($suggestions);