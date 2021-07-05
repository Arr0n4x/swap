<?php


$host = 'mysql:host=localhost;dbname=SWAP'; 
$login = 'root'; // login
$password = ''; // mdp
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
);
$pdo = new PDO($host, $login, $password, $options);

// Création d'une varaible vide que 'lon appelle sur toutes nos pazgs en dessous du titre de la page. Cette variable nous permet de mettre des messages utilisateur dedans, ils s'afficheront naturellement ensuite.
$msg = '';

// Création/ovurerture de la session
session_start();

// délcaration de constantes 
// url absolue
define('URL' , 'http://php/SWAP/');
// chemin racine serveur pour l'enregistrement des fichiers chargé via le formulaire.
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']); // cette information est récupérée dans la super globale $_SERVER : exemple C:/wamp64/www
// chemin depuis le serveur vers notre site 
define('PROJECT_PATH', '/SWAP/' );// a modifier lors de la mise en ligne

// exemple : echo ROOT_PATH . PROJECT_PATH;
// => C:/wamp64/www/DIW59/PHP/SWAP/ 