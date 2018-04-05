<?php
session_start();
require "conf.inc.php";
require "functions.php";

if(isset($_SESSION["token"])){
	$connection = connectDB();

	$query = $connection->prepare("SELECT member_status,member_firstname,member_lastname FROM MEMBER where member_token= :token");

	$query->execute([
		"token"=>$_SESSION["token"]
	]);

	$result = $query->fetch(PDO::FETCH_ASSOC);
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
					<a class="nav-link active" href="bOffice - Users.php">Membres</a>
					<a class="nav-link active" href="#">Evénements publics</a>
					<a class="nav-link active" href="#">Evénements à valider</a>
					<a class="nav-link active" href="#">Tickets</a>
					<a class="nav-link active" href="index.php">Quitter l'administration</a>
				</nav>
		</section>
	<hr>
	</header>