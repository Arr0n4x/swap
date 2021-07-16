<?php

// connected user's function
function user_is_connected() {
    // verify if SESSION is empty or not
    if(empty($_SESSION['membre'])) {
        return false;
    } else {
        return true;
    }

}

// admin users testing function
function user_is_admin() {
    if(user_is_connected() && $_SESSION['membre']['statut'] == 2) {
        return true;
    }
    return false; 
}



// basket's function
function creat_cart() {
    if( !isset($_SESSION['panier']) ){
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_article'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

// add article on basket
function add_product_in_cart($id_article, $titre, $quantite, $prix){
        $position_article = array_search($id_article,  $_SESSION['panier']['id_article']);
        if($position_article === false) {
            $_SESSION['panier']['id_article'][] = $id_article;
            $_SESSION['panier']['titre'][] = $titre;
            $_SESSION['panier']['quantite'][] = $quantite;
            $_SESSION['panier']['prix'][] = $prix;
        }else {
            $_SESSION['panier']['quantite'][$position_article] += $quantite;
        }

}