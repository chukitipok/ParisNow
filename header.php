<?php
session_start();
require_once "conf.inc.php";
require_once "functions.php";

preventXSS($_POST);

if (isset($_POST["disconnect"]) && $_POST["disconnect"] == "disconnect") {
    session_unset();
    session_destroy();
    header("Location: index.php");
}
unset($_POST["disconnect"]);

if (count($_POST) == 2 && isset($_POST["emailConnect"]) && isset($_POST["pwdConnect"])) {
    $_SESSION["emailConnect"] = strtolower($_POST["emailConnect"]);
    $_SESSION["pwdConnect"] = $_POST["pwdConnect"];
    connectUser();

}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ParisNow</title>
    <meta name="description" content="ceci est ma premiere page WEB">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>

    <script src="js/jquery-3.2.1.min.js"></script>

</head>
<body>
<div class="container-fluid">
    <header>
        <div id="logo">
            <a class="nav-link" href="index.php"><img src="img/logo.png" alt="logo paris now"></a>
        </div>
    </header>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Accueil <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Toto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">Art&Culture</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">ParisByNight</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">Nature&Bien-être</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">Forum</a>
                </li>

                <?php
                if (!empty($_SESSION["token"])) {
                    $db = connectDB();
                    $query = $db->prepare("SELECT m_status_label FROM m_status
                                                    WHERE m_status_id = (SELECT member_status FROM member WHERE member_id = :id AND member_token= :token);");
                    $query->execute([
                        "id" => $_SESSION["id"],
                        "token" => $_SESSION["token"]
                    ]);
                    $result = $query->fetch(PDO::FETCH_ASSOC);

                    if ($result["m_status_label"] == "Admin" or $result["m_status_label"] == "Moderator") { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="bOffice - Users.php">Admin</a>
                        </li>
                        <?php
                    }
                }
                if (!isConnected()) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">S'inscrire</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formConnexion">
                            Connexion
                        </button>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Bonjour <?php
                                    $db = connectDB();
                                    $query = $db->prepare("SELECT member_firstname FROM member 
                                                                    WHERE member_id = :id AND member_token = :token;");
                                    $query->execute([
                                        "id"=>$_SESSION["id"],
                                        "token"=>$_SESSION["token"]
                                    ]);
                                    $result = $query->fetch();
                                    echo $result["member_firstname"];
                                 ?>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Mes événements (TODO)</a>
                                <a class="dropdown-item" href="userSettings.php">Mon profil</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST">
                                    <button action="submit" class="btn dropdown-item" name="disconnect"
                                            value="disconnect">Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>

                    </li>
                <?php } ?>
            </ul>

        </div>
    </nav>

    <!-- POP-UP CONNECT ACCOUNT -->
    <div class="modal fade" id="formConnexion" tabindex="-1" role="dialog" aria-labelledby="formConnexionTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formConnexionTitle">Connexion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="emailLogin">Votre email</label>
                            <input type="email" class="form-control" id="emailLogin" aria-describedby="emailHelp"
                                   placeholder="test@domain.fr" name="emailConnect">
                        </div>
                        <div class="form-group">
                            <label for="pwdLogin">Mot de passe</label>
                            <input type="password" class="form-control" id="pwdLogin" name="pwdConnect">
                        </div>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                        <span>Mot de passe oublié?</span>
                    </form>
                </div>
            </div>
        </div>
    </div>



