<?php

// Fonction pour tester si l'utilisateur est connecté
function user_is_connected() {
    // on vérifie si SESSION n'existe pas ou est vide.
    if(empty($_SESSION['membre'])) {
        return false;
    } else {
        return true;
    }

}

// Fonction pour tester si l'utilisateur est admin
function user_is_admin() {
    if(user_is_connected() && $_SESSION['membre']['statut'] == 2) {
        return true;
    }
    return false; // après un return, on sort immédiatement de la fonction donc cette ligne ne sera pas lue si on est rentré dans le if. Comportement similaire si on met un else
}



// function de création du panier (on crée des sous tableaux array dans la session pour représenter le panier)
function creat_cart() {
    if( !isset($_SESSION['panier']) ){
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_article'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

// fonction pour ajouter un article dans le pannier 
function add_product_in_cart($id_article, $titre, $quantite, $prix){
        // Avant d'ajouter l'article, nous devons vérifier si il est déjà présent, dans ce cas on change la quantité sinon on ajoute l'article. 
        // array_search() permet de chercher une valeur dans un tableau, si elle est trouvée on récupère l'indice sinon on récupère false
        $position_article = array_search($id_article,  $_SESSION['panier']['id_article']);
        // si on obtien false, on ajoute sinon on change la quantité graçe à la $position_article
        if($position_article === false) {
            $_SESSION['panier']['id_article'][] = $id_article;
            $_SESSION['panier']['titre'][] = $titre;
            $_SESSION['panier']['quantite'][] = $quantite;
            $_SESSION['panier']['prix'][] = $prix;
        }else {
            $_SESSION['panier']['quantite'][$position_article] += $quantite;
        }

}