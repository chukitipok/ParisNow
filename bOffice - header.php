<?php
session_start();
require "conf.inc.php";
require "functions.php";
preventXSS($_POST); // Function preventing xss injection

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

if(isset($_SESSION["token"])){ // Condition that handle access to the back office, to know if the user have the rights to access the requested page.
	$result = getinfo("*");
	if($result["member_status"] == 2){ // "If" condition sets $_SESSIONs that are used later to handle rights of moderator and administrator differently
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
	$_SESSION["name"] = $result["member_lastname"]; // These $_SESSIONs are used for logs.
	$_SESSION["firstName"] = $result["member_firstname"];
}

else{
	header("Location: index.php");
}

switch($_SERVER["PHP_SELF"]){ // This switch handle where the user to make active links of the page where he is. It also handle the subpage on the ticket part

	case "/parisnow/bOffice - ticketsToDo.php":
	case "/parisnow/bOffice - ticketsOpen.php":
	case "/parisnow/bOffice - ticketsClosed.php":
	$navigation[$_SERVER["PHP_SELF"]] = 1;
	$navigation["ticket"] = 1;
	break;

	default:
	$navigation[$_SERVER["PHP_SELF"]] = 1;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Administration des utilisateurs</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/styleBO.css">
	<meta name="description" content="Partie back office de ParisNow">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

	<header>
		<section class="container-fluid">
			<div class="row">
				<div class="col-3">
					<img src="img/logo.png" class="img-fluid">
				</div>
				<div class="col-4 offset-1">
					<h1 class="title">Administration</h1>
				</div>
				<div class="col-3 offset-1">
					<img src="img/logo.png" class="img-fluid">
				</div>
			</div>
			<h5 style="text-align:center;">Vous êtes : <?php echo($_SESSION["admin"])? "Administrateur.":"Modérateur." ?></h5> <!-- Show the rank of the member -->

			<nav class="navbar justify-content-center bOfficeNavigation">
				<a class="nav-link" href="bOffice - Users.php"><button class="btn buttonHeader <?php echo (isset($navigation["/parisnow/bOffice - Users.php"]))? "activeHeader":"" ?>">Membres</button></a>
				<a class="nav-link" href="#"><button class="btn buttonHeader <?php echo (isset($navigation["/parisnow/bOffice - "]))? "activeHeader":"" ?>">Evénements publics</button></a>
				<a class="nav-link" href="#"><button class="btn buttonHeader <?php echo (isset($navigation["/parisnow/bOffice - "]))? "activeHeader":"" ?>">Evénements à valider</button></a>
				<div class="nav-item dropdown nav-link">
					<a role="button" class="btn buttonHeader nav-link dropdown-toggle arrowDrop <?php echo (isset($navigation["ticket"]))? "activeHeader":"" ?>" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tickets</a>
					<div class="dropdown-menu dropmenu" aria-labelledby="navbarDropdownMenuLink">
						<a class="dropdown-item" href="bOffice - ticketsToDo.php">A traiter</a>
						<a class="dropdown-item" href="bOffice - ticketsOpen.php">Ouvert</a>
						<a class="dropdown-item" href="bOffice - ticketsClosed.php">Historique</a>
					</div>
				</div>

				<a class="nav-link" href="index.php"><button class="btn buttonHeader">Quitter l'administration</button></a>
			</nav>
		</section>
		<hr>
	</header>
