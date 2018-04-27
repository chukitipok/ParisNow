<?php
session_start();
require "conf.inc.php";
require "functions.php";
preventXSS($_POST);
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

if(isset($_SESSION["token"])){
	$result = getinfo("*");
	if($result["member_status"] == 2){
		$_SESSION["admin"] = TRUE;
		$_SESSION["moderateur"] = FALSE;
		$_SESSION["status"] = "Administrateur";
	}
	elseif($result["member_status"] == 1){
		$_SESSION["moderateur"] = TRUE;
		$_SESSION["admin"] = FALSE;
		$_SESSION["status"] = "Modérateur";
	}
	else{
		echo "fail";
		header("Location: index.php");
	}
	$_SESSION["name"] = $result["member_lastname"];
	$_SESSION["firstName"] = $result["member_firstname"];
}

else{
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Administration des utilisateurs</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/styleBO.css">
	<meta name="description" content="Partie back office du site">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

	<header>
		<section class="container-fluid">

				<img src="img/logo.png" class="logo">
				<div class="container-fluid title bOfficeTitle">
						<h1>Administration</h1>
				</div>
				<center><h5>Vous êtes : <?php echo($_SESSION["admin"])? "Administrateur.":"Modérateur." ?></h5></center>

				<nav class="navbar justify-content-center bOfficeNavigation">
					<a class="nav-link" href="bOffice - Users.php">Membres</a>
					<a class="nav-link" href="#">Evénements publics</a>
					<a class="nav-link" href="#">Evénements à valider</a>
						<div class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tickets</a>
							<div class="dropdown-menu dropmenu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item" href="bOffice - ticketsToDo.php">A traiter</a>
								<a class="dropdown-item" href="bOffice - ticketsOpen.php">Ouvert</a>
								<a class="dropdown-item" href="bOffice - ticketsClosed.php">Historique</a>
							</div>
						</div>

					<a class="nav-link" href="index.php">Quitter l'administration</a>
				</nav>
		</section>
	<hr>
	</header>