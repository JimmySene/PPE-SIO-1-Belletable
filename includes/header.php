<!DOCTYPE html>
<?php session_start(); ?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Belle table - <?php echo $titrePage ?></title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css" /> <!-- BOOTSTRAP V4 -->
    <!-- <link rel="stylesheet" href="css/style.css" /> -->
    <script src="js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    
</head>
<body >
    <header>
    
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    
            <a href="index.php" class="navbar-brand">BELLE TABLE</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a href="index.php" class="nav-link" >Présentation</a></li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Services </a>
                    <div class="dropdown-menu">
                        <a href="produits.php" class="dropdown-item">Produits</a>
                        <a href="salles.php" class="dropdown-item">Réservation de salles</a>
                    </div>
                </li>
                <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="forum.php" class="nav-link">Forum</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                </ul>
            </div>
       
    
            <p id="menu_login" class=""><?php if(isset($_SESSION['login'])) { if($_SESSION['lvl'] != 0) { // Si l'utilisateur est admin affiche le lien du panneau d'admin ?> 
                <a href="admin.php" class="btn btn-primary">Panneau admin</a> <?php } ?> <a href="deconnexion.php" class="btn btn-primary">Déconnexion</a> <?php } else { ?>
                <a href="login.php" class="btn btn-primary">Connexion</a> <a href="inscription.php" class="btn btn-primary">Inscription</a><?php } ?>
            </p>
    
    </nav>
    
    </header>
    <main  class="container" style="margin-top : 20px">
       
        