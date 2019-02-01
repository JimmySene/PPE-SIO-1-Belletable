<?php 

// Connexion MySQL à la BDD
function sql_connect() 
{
    $con = mysqli_connect('localhost', 'root', '', 'belle_table');
    return $con;
}

// Connecte l'utilisateur si ses identifiants sont corrects
function connexion_utilisateur($login, $mdp){
    $con = sql_connect($login, $mdp);
    $req = "SELECT * FROM client WHERE adresse_mail = '$login'";
    $data = mysqli_query($con, $req);

    if(mysqli_num_rows($data) > 0) // Si l'utilisateur a été trouvé
    {
        $client = mysqli_fetch_assoc($data);
        $verif_mdp = password_verify($mdp, $client['mot_de_passe']);
        if($verif_mdp)
        {
            $_SESSION['login'] = $login;
            $_SESSION['lvl'] = $client['lvl'];
        }    
    }
}

function inscription_utilisateur($email, $mdp, $civilite, $nom, $prenom, $adresse, $ville, $cp, $tel){
    $con = sql_connect();
    $req = "INSERT INTO  client(adresse_mail, mot_de_passe, civilite, nom, prenom, adresse, ville, code_postal, telephone ) 
    VALUES('$email','$mdp','$civilite', '$nom', '$prenom', '$adresse', '$ville', '$cp', '$tel')";
    return mysqli_query($con, $req);
}

/* === Vérification Admin ===
Renvoie TRUE si l'utilisateur est admin, sinon FALSE */
function verif_admin() 
{
    if(isset($_SESSION['login']) && $_SESSION['lvl'] != 0)
        return true;
    else   
        return false;
}

/* === Vérifie si l'email d'un nouvel utilisateur est déjà prise ===
Renvoie TRUE si il y a doublon, sinon FALSE */
function verif_doublon_user($email)
{
    $con = sql_connect();
    $req = "SELECT * FROM client WHERE adresse_mail = '$email'";
    $data = mysqli_query($con, $req);
    $doublon = (mysqli_num_rows($data) > 0) ? true : false;
    return $doublon;
}

/* === Gestion des utilisateurs dans le panneau d'admin ===
Ajoute ou modifie un utilisateur en fonction du paramètre $mode ('ajout' / 'modif') 
Si on ajoute un utilisateur, on mettra $id à 0 lors de l'appel à la fonction */
function gestion_user($mode, $id, $email, $mdp, $civilite, $nom, $prenom, $adresse, $ville, $cp, $tel, $lvl) 
{
    if(!empty($email) && !empty($nom) && !empty($prenom))
    {
            if($mode == 'ajout') // Si on veut ajouter un utilisateur
            {
                if(!empty($mdp))
                {
                    $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                    $con  = sql_connect();
                    $req = "INSERT INTO client VALUES(null,'$email','$mdp','$civilite', '$nom', '$prenom', '$adresse', '$ville', '$cp', '$tel', '$lvl')";
        
                    mysqli_query($con,$req);
                } 
            } elseif($mode == 'modif') { // Si on veut modifier un utilisateur
                $con  = sql_connect();
                if(!empty($mdp)) {  // Si un mot de passe a été renseigné alors on le change

                    $mdp = password_hash($mdp, PASSWORD_DEFAULT); 
                    $req = "UPDATE `client` SET `adresse_mail` = '$email',
                    `mot_de_passe` = '$mdp',
                    `civilite` = '$civilite', 
                    `nom` = '$nom',
                    `prenom` = '$prenom',
                    `adresse` = '$adresse', 
                    `ville` = '$ville',
                    `code_postal` = '$cp', 
                    `telephone` = '$tel',
                    `lvl` = '$lvl' 
                    WHERE `id` = '$id'"; 

                } else { // Sinon on ne change pas le mot de passe

                    $req = "UPDATE `client` SET `adresse_mail` = '$email',
                    `civilite` = '$civilite', 
                    `nom` = '$nom',
                    `prenom` = '$prenom',
                    `adresse` = '$adresse', 
                    `ville` = '$ville',
                    `code_postal` = '$cp', 
                    `telephone` = '$tel',
                    `lvl` = '$lvl' 
                    WHERE `id` = '$id'";
                }

                    mysqli_query($con,$req);
            }
        
    }
}

// Ajoute une catégorie de produit
function ajouter_categorie($nom){
    $con = sql_connect();
    $req = "INSERT INTO categorie VALUES(null, '$nom')";
    mysqli_query($con, $req);
}

// Renvoie les catégories de produit
function recup_categories(){
    $con = sql_connect();
    $req = "SELECT * FROM categorie ORDER BY nom";
    return mysqli_query($con, $req);
}

// Renvoie la catégorie de l'id renseigné
function recup_categorie_par_id($id){
    $con = sql_connect();
    $req = "SELECT * FROM categorie WHERE id = $id";
    return mysqli_query($con, $req);
}

// Modifie la catégorie avec l'id renseigné
function modifier_categorie($id, $nom){
    $con = sql_connect();
    $req = "UPDATE categorie SET nom = '$nom'
    WHERE id = $id";
    mysqli_query($con, $req);
}
// Supprime une catégorie de produit
function supprimer_categorie($id){
    $con = sql_connect();
    $req = "DELETE FROM categorie WHERE id = $id";
    mysqli_query($con, $req);
}

// Renvoie les produits par catégorie
function recup_produits_par_cat($id_cat){
    $con = sql_connect();
    $req = "SELECT produit.id as produit_id, produit.nom as produit_nom, prix, marque.id as marque_id, marque.nom as marque_nom
    FROM produit, marque 
    WHERE produit.categorie_id = $id_cat 
    AND produit.marque_id = marque.id 
    ORDER BY produit.nom";
    return mysqli_query($con, $req);
}

// Ajoute un produit
function ajouter_produit($nom, $prix, $categorie, $marque){
    $con = sql_connect();
    $req = "INSERT INTO produit VALUES(null, '$nom', '', $prix, $categorie, $marque)";
    mysqli_query($con, $req);
}
// Modifie un produit
function modifier_produit($id, $nom, $prix, $categorie, $marque){
    $con = sql_connect();
    $req = "UPDATE produit SET nom = '$nom',
    prix = $prix,
    categorie_id = $categorie,
    marque_id = $marque
    WHERE id = $id";
    mysqli_query($con, $req);
}

// Supprime un produit
function supprimer_produit($id){
    $con = sql_connect();
    $req = "DELETE FROM produit WHERE id = $id";
    mysqli_query($con, $req);
    unlink("images/produits/$id.jpg");
}

// Ajoute une marque de produit
function ajouter_marque($nom){
    $con = sql_connect();
    $req = "INSERT INTO marque VALUES(null, '$nom')";
    mysqli_query($con, $req);
}

// Supprime une marque de produit
function supprimer_marque($id){
    $con = sql_connect();
    $req = "DELETE FROM marque WHERE id = $id";
    mysqli_query($con, $req);
}

// Renvoie les marques
function recup_marques(){
    $con = sql_connect();
    $req = "SELECT id, nom FROM marque ORDER BY nom";
    return mysqli_query($con, $req);
}

// Renvoie la marque de l'id renseigné
function recup_marque_par_id($id){
    $con = sql_connect();
    $req = "SELECT * FROM marque WHERE id = $id";
    return mysqli_query($con, $req);
}

// Modifie la marque de l'id renseigné
function modifier_marque($id, $nom){
    $con = sql_connect();
    $req = "UPDATE marque SET nom = '$nom'
    WHERE id = $id";
    mysqli_query($con, $req);
}

// Renvoie tous les produits classés par nom
function recup_produits(){
    $con = sql_connect();
    $req = "SELECT produit.id as produit_id, produit.nom as produit_nom, prix, marque.id as marque_id, marque.nom as marque_nom
    FROM produit, marque
    WHERE produit.marque_id = marque.id 
    ORDER BY produit.nom";

    return mysqli_query($con, $req); 
}

// Renvoie tous les produits pour la zone d'admin
function recup_admin_produits(){
    $con = sql_connect();
    $req = "SELECT produit.id as produit_id, produit.nom as produit_nom, prix, marque.id as marque_id, marque.nom as marque_nom, categorie.nom as categorie_nom
    FROM produit, marque, categorie
    WHERE produit.marque_id = marque.id
    AND produit.categorie_id = categorie.id
    ORDER BY produit.nom";

    return mysqli_query($con, $req);
}

// Renvoie un produit avec l'id renseigné
function recup_produit_par_id($id){
    $con = sql_connect();
    $req = "SELECT produit.id as produit_id, produit.nom as produit_nom, prix, marque.id as marque_id, marque.nom as marque_nom, categorie.nom as categorie_nom, categorie.id as categorie_id
    FROM produit, marque, categorie
    WHERE produit.marque_id = marque.id
    AND produit.categorie_id = categorie.id
    AND produit.id = $id";

    return mysqli_query($con, $req);
}

// Renvoie tous les clients classés par nom
function recup_clients(){
    $con = sql_connect();
    $req = "SELECT * FROM client ORDER BY nom";
    return mysqli_query($con, $req);
}

// Renvoie le client avec l'id renseigné
function recup_client_par_id($id_client){
    $con = sql_connect();
    $req = "SELECT * FROM client WHERE id = $id_client";
    return mysqli_query($con, $req);
}

// Renvoie toutes les salles
function recup_salles(){
    $con = sql_connect();
    $req = "SELECT * FROM salle";
    return mysqli_query($con, $req);
}

// Renvoie tous les messages privés reçus
function recup_messages(){
    $con = sql_connect();
    $req = "SELECT * FROM contact ORDER BY date_envoie DESC";
    return mysqli_query($con, $req);
}

// Supprime le message privé avec l'id renseigné
function supprimer_message($id_message){
    $con  = sql_connect();
	$req = "DELETE FROM contact WHERE id = $id_message";
	mysqli_query($con,$req);
}

// Supprime le client avec l'id renseigné
function supprimer_client($id_client){
    $con  = sql_connect();
	$req = "DELETE FROM client WHERE id = $id_client";
	mysqli_query($con,$req);
}

// Envoie un message privé
function envoyer_message($email, $pseudo, $message, $date_envoie){
    $con  = sql_connect();
    $req = "INSERT INTO  contact(adresse_mail, pseudo, message, date_envoie) VALUES('$email','$pseudo','$message','$date_envoie')";
    mysqli_query($con,$req);
}

?>