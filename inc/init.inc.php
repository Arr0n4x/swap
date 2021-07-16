<?php


$host = 'mysql:host=localhost;dbname=SWAP'; 
$login = 'root'; // login
$password = ''; // mdp
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
);
$pdo = new PDO($host, $login, $password, $options);

// msg is variable for message in case of problems
$msg = '';

// création or opening of session
session_start();

// CONST
// url 
define('URL' , 'http://php/SWAP/');
// ROOT PATH
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']); // cette information est récupérée dans la super globale $_SERVER : exemple C:/wamp64/www
// ROOT SERVER
define('PROJECT_PATH', '/SWAP/' );// a modifier lors de la mise en ligne

// exemple : echo ROOT_PATH . PROJECT_PATH;
// => C:/wamp64/www/DIW59/PHP/SWAP/ 