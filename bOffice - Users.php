<?php
require "bOffice - header.php"; 
$time = time();
		$date = new DateTime("now", new DateTimeZone('Europe/Paris'));
		$date->setTimestamp($time);
		$actualDate = $date->format('d/m/y à H\h:i\m:s\s');

if(isset($_POST["cancelDelete"])){
	
	foreach ($_SESSION["cancelDelete"] as $key2 => $value2) {

		if(!isset($column_name) && (!isset($valuesInserted))){
			$columnName = $key2;
			$valuesInserted = $value2;
		}
		
		elseif($value2 =="")
			$value2 = Null;

		elseif(is_numeric($value2)){
			$columnName = $columnName.",".$key2;
			$valuesInserted = $valuesInserted.",".$value2;
		}

		else{
			$columnName = $columnName.",".$key2;
			$valuesInserted = $valuesInserted.",'".$value2."'";	
		}
	}
	$connection = connectDB();
	$query = $connection->prepare("INSERT INTO member(".$columnName.") VALUES(".$valuesInserted.")");
	$query->execute();	
}

if(isset($_POST["emailOfUserDelete"])){
	$connection = connectDB();

	$query = $connection->prepare("SELECT * FROM member where member_email= :email");
	$query->execute([
		"email"=>$_POST["emailOfUserDelete"]
	]);
	$result = $query->fetch(PDO::FETCH_ASSOC);
	$cancel = '<form method="POST"><input type="hidden" value="cancel" name="cancelDelete"><button type="submit">annuler</button></form>';
	$_SESSION["cancelDelete"] = $result;	
	if($result["member_status"] != 2 && $_SESSION["admin"]){
		$query = $connection->prepare("DELETE FROM member where member_email= :email");
		$query->execute([
			"email"=>$_POST["emailOfUserDelete"]
		]);
		$file = fopen('logDelete.txt', 'a+');
        fwrite($file, $_SESSION["status"]." : ".$_SESSION["name"]." ".$_SESSION["firstName"]." a supprimé le membre ".$result["member_lastname"]." ".$result["member_firstname"]." email : ".$result["member_email"]." le : ".$actualDate."\r\n");
        fclose($file);
		echo '<center><h2 class="succes">Action effectué : Le membre '.$result["member_lastname"].' '.$result["member_firstname"].' à bien été supprimé</h2>'.$cancel.'</center>';
	}
	else{
?>
		<center><h2 class="erreur">Erreur: Vous n'avez pas les droits pour effectuer cette action</h2><center>
<?php 
	}
}

if(isset($_POST["emailOfUserPromote"])){
	$connection = connectDB();

	$query = $connection->prepare("SELECT member_status,member_lastname,member_firstname,member_email FROM member where member_email= :email");
	$query->execute([
		"email"=>$_POST["emailOfUserPromote"]
	]);
	$result = $query->fetch(PDO::FETCH_ASSOC);	
	if($result["member_status"] == 0 && $_SESSION["admin"]){
		$query = $connection->prepare("UPDATE member set member_status = 1 where member_email= :email");
		$query->execute([
			"email"=>$_POST["emailOfUserPromote"]
		]);
		$file = fopen('logPromotion.txt', 'a+');
        fwrite($file, $_SESSION["status"]." : ".$_SESSION["name"]." ".$_SESSION["firstName"]." a promu le membre ".$result["member_lastname"]." ".$result["member_firstname"]." email : ".$result["member_email"]." le : ".$actualDate."\r\n");
        fclose($file);
		echo '<center><h2 class="succes">Action effectué : Le membre '.$result["member_lastname"].' '.$result["member_firstname"].' à bien été promu Modérateur</h2></center>';
	}
	else{
?>
		<center><h2 class="erreur">Erreur: Vous n'avez pas les droits pour effectuer cette action</h2><center>
<?php 
	}
}

if(isset($_POST["emailOfUserDemote"])){
	$connection = connectDB();

	$query = $connection->prepare("SELECT member_status,member_lastname,member_firstname,member_email FROM member where member_email= :email");
	$query->execute([
		"email"=>$_POST["emailOfUserDemote"]
	]);
	$result = $query->fetch(PDO::FETCH_ASSOC);	
	if($result["member_status"] == 1 && $_SESSION["admin"]){
		$query = $connection->prepare("UPDATE member set member_status = 0 where member_email= :email");
		$query->execute([
			"email"=>$_POST["emailOfUserDemote"]
		]);
		$file = fopen('logPromotion.txt', 'a+');
        fwrite($file, $_SESSION["status"]." : ".$_SESSION["name"]." ".$_SESSION["firstName"]." a déchu le membre ".$result["member_lastname"]." ".$result["member_firstname"]." email : ".$result["member_email"]." le : ".$actualDate."\r\n");
        fclose($file);
		echo '<center><h2 class="succes">Action effectué : Le membre '.$result["member_lastname"].' '.$result["member_firstname"].' à bien été déchu Membre</h2></center>';
	}
	else{
?>
		<center><h2 class="erreur">Erreur: Vous n'avez pas les droits pour effectuer cette action</h2><center>
<?php 
	}
}

if(isset($_POST["emailOfUserUnban"])){
	$connection = connectDB();

	$query = $connection->prepare("SELECT member_status,member_lastname,member_firstname,member_email FROM member where member_email= :email");
	$query->execute([
		"email"=>$_POST["emailOfUserUnban"]
	]);
	$result = $query->fetch(PDO::FETCH_ASSOC);	
	if($result["member_status"] == 3){
		$query = $connection->prepare("UPDATE member set member_status=0 where member_email= :email");
		$query->execute([
			"email"=>$_POST["emailOfUserUnban"]
		]);
		$file = fopen('logBan.txt', 'a+');
        fwrite($file, $_SESSION["status"]." : ".$_SESSION["name"]." ".$_SESSION["firstName"]." a débanni le membre ".$result["member_lastname"]." ".$result["member_firstname"]." email : ".$result["member_email"]." le : ".$actualDate."\r\n");
        fclose($file);
		echo '<center><h2 class="succes">Action effectué : Le membre '.$result["member_lastname"].' '.$result["member_firstname"].' à bien été débanni</h2></center>';
	}
	else{
?>
		<center><h2 class="erreur">Erreur: Vous n'avez pas les droits pour effectuer cette action</h2><center>
<?php 
	}
}

if(isset($_POST["emailOfUserBan"])){
	$connection = connectDB();

	$query = $connection->prepare("SELECT member_status,member_lastname,member_firstname,member_email FROM member where member_email= :email");
	$query->execute([
		"email"=>$_POST["emailOfUserBan"]
	]);
	$result = $query->fetch(PDO::FETCH_ASSOC);

	if(($result["member_status"] != 2 && $_SESSION["admin"]) || ($_SESSION["moderateur"] && $result["member_status"] == 0)){
		$query = $connection->prepare("UPDATE member set member_status=3 where member_email= :email");
		$query->execute([
			"email"=>$_POST["emailOfUserBan"]
		]);
		$file = fopen('logBan.txt', 'a+');
        fwrite($file, $_SESSION["status"]." : ".$_SESSION["name"]." ".$_SESSION["firstName"]." a banni le membre ".$result["member_lastname"]." ".$result["member_firstname"]." email : ".$result["member_email"]." le : ".$actualDate."\r\n");
        fclose($file);
		echo '<center><h2 class="succes">Action effectué : Le membre '.$result["member_lastname"].' '.$result["member_firstname"].' à bien été banni</h2></center>';
	}
	else{
?>
		<center><h2 class="erreur">Erreur: Vous n'avez pas les droits pour effectuer cette action</h2><center>
<?php 
	}
}

?>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th>Nom de famille</th>
					<th>Prénom</th>
					<th>Email</th>
					<th>Statut</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tr>
				<form method="POST">
					<td>
						<div class="container-fluid row">
							<div class="">
								<input type="text" class="form-control" id="searchLastname" placeholder="search" name="searchLastname" value="">
							</div>
						</div>
					</td>
					<td>
						<div class="container-fluid row">
							<div class="">
								<input type="text" class="form-control" id="searchFirstname" placeholder="search" name="searchFirstname" value="">
							</div>
						</div>
					</td>
					<td>
						<div class="container-fluid row">
							<div class="">
								<input type="text" class="form-control" id="searchEmail" placeholder="search" name="searchEmail" value="">
							</div>
						</div>
					</td>
					<td>
						<div class="container-fluid row">
							<div class="">
								<select name="searchStatus" class="form-control">
									<option value="All">Tous</option>
									<option value="0">Membre</option>
									<option value="1">Modérateur</option>
									<option value="2">Administrateur</option>
									<option value="3">Banni</option>
								</select>
							</div>
						</div>
					</td>
					<td>
						<div class="">
							<div class="">
								<button type="submit" class="btn btn-primary">Rechercher</button>
							</div>
						</div>
					</td>
				</form>
			</tr>
		<?php
		if(isset($_POST["searchStatus"]) && $_POST["searchStatus"] == "All")
			$_POST["searchStatus"] = Null;

		if(empty($_POST["searchLastname"]) && empty($_POST["searchFirstname"]) && empty($_POST["searchEmail"]) && isset($_POST["searchStatus"])){

			$connection = connectDB();

			$query = $connection->prepare("SELECT member_lastname,member_firstname,member_email,member_status FROM member WHERE member_status = :status");

			$query->execute([
				"status"=>$_POST["searchStatus"]
			]);
		}

		elseif(!empty($_POST["searchLastname"]) || !empty($_POST["searchFirstname"]) || !empty($_POST["searchEmail"]) || !empty($_POST["searchStatus"])){
			$connection = connectDB();	
			$query = $connection->prepare("SELECT member_lastname,member_firstname,member_email,member_status FROM member WHERE member_lastname= :lastName OR member_firstname= :firstName OR member_email= :email OR member_status= :status");

			$query->execute([
				"lastName"=>$_POST["searchLastname"],
				"firstName"=>$_POST["searchFirstname"],
				"email"=>$_POST["searchEmail"],
				"status"=>$_POST["searchStatus"]
			]);
		}

		else{
			$connection = connectDB();

			$query = $connection->prepare("SELECT member_lastname,member_firstname,member_email,member_status FROM member");

			$query->execute();
		}

		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as $value){
			echo "<tr>";
			foreach ($value as $key => $value2){
				if($key == "member_status"){
					echo "<td>";
					switch ($value2){
						case 0:
						echo "Membre";
						break;

						case 1:
						echo "Modérateur";
						break;

						case 2:
						echo "Administrateur";
						break;

						case 3:
						echo "Banni";
						break;

						default:
						echo "erreur - statut non existant";
					}
					echo "</td>";
					$statusOfMember = $value2;
				}

				else{
					echo "<td>".$value2."</td>";
				}

				if($key == "member_email"){
					$emailOfMember = $value2;
				}				
			}
		$beginButton = '<td><div class="row">';
		$endButton = '</div></td></tr>';

		$eraseButton = ($_SESSION["admin"])? '<div class="actionButton">
					<form method="POST">
						<input type="hidden" name="emailOfUserDelete" value='.$emailOfMember.'>
						<button type="submit">Supprimer</button>
					</form>
				</div>': "";
		if(($_SESSION["moderateur"] && $statusOfMember != 1 && $statusOfMember != 3) || ($_SESSION["admin"] && $statusOfMember != 2 && $statusOfMember !=3)){
				$banButton ='<div class="actionButton">
					<form method="POST">
						<input type="hidden" name="emailOfUserBan" value='.$emailOfMember.'>
						<button type="submit">Bannir</button>
					</form>
				</div>';
			}
		elseif ($statusOfMember == 3) {
			$banButton = '<div class="actionButton">
					<form method="POST">
						<input type="hidden" name="emailOfUserUnban" value='.$emailOfMember.'>
						<button type="submit">Débannir</button>
					</form>
				</div>';
			}
		else{
			$banButton = "";
		}

		if($statusOfMember == 0 && $_SESSION["admin"]){
			$rankButton = '<div class="actionButton">
					<form method="POST">
						<input type="hidden" name="emailOfUserPromote" value='.$emailOfMember.'>
						<button type="submit">Promouvoir</button>
					</form>
				</div>';
		}

		elseif($statusOfMember == 1 && $_SESSION["admin"]){
			$rankButton = '<div class="actionButton">
					<form method="POST">
						<input type="hidden" name="emailOfUserDemote" value='.$emailOfMember.'>
						<button type="submit">Destituer</button>
					</form>
				</div>';
		}
		
		else{
			$rankButton ="";
		}
		echo ($statusOfMember==2)? "<td></td></tr>": $beginButton.$banButton.$eraseButton.$rankButton.$endButton;
			}
		echo "</table>";
?>
	</div>

<?php 
include "bOffice - footer.php" ?>