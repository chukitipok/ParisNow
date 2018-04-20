<?php
require "header.php";
$time = getTimeForLog();

if(isConnected()){
	$userInfo = getInfo("member_id");

	if(isset($_SESSION["ticketError"]) && $_SESSION["ticketError"]){
		echo '<h4 class="smallTitle error">Erreur : Ce ticket n\'existe pas !</h4>';
		unset($_SESSION["ticketError"]);
	}

	elseif(isset($_SESSION["ticketAlreadyClose"]) && $_SESSION["ticketAlreadyClose"]){
		echo '<h4 class="smallTitle error">Erreur : Ce ticket est déjà fermé !</h4>';
		unset($_SESSION["ticketAlreadyClose"]);
	}

	elseif(isset($_SESSION["ticketAlreadyOpen"]) && $_SESSION["ticketAlreadyOpen"]){
		echo '<h4 class="smallTitle error">Erreur : Ce ticket est déjà ouvert !</h4>';
		unset($_SESSION["ticketAlreadyOpen"]);
	}

	if(isset($_GET["ticket_id"]) && is_numeric($_GET["ticket_id"]) && strlen($_GET["ticket_id"]) == 10 && $_GET["ticket_id"] > 0){ 
		$connection = connectDB();
		$query = $connection->prepare("SELECT * from ticket,t_category where member= :member_id AND ticket_id= :ticket_id AND ticket.t_category = category_id");
		$query->execute([
			"member_id"=>$userInfo["member_id"],
			"ticket_id"=>$_GET["ticket_id"]
		]);
		$result = $query->fetch(PDO::FETCH_ASSOC);
		if(empty($result)){
			unset($_GET["ticket_id"]);
			header("Location: userTicket.php");
		}

		elseif($result["state"] == 0){
			?>
			<div class="row">
				<div class="col-4">
				</div>
				<div class="col-4">	
					<div>
						<h5 class="smallTitle">Ticket n°<?php echo $result["ticket_id"] ?></h5>
						<p class="ticketTitle">Titre : <?php echo $result["ticket_label"] ?></p>
						<p>Catégorie : <?php echo $result["category_name"]?></p>
						<p><u>Historique</u><br><?php echo $result["ticket_content"]?></p>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-4">
				</div>
				<div class="col-4">
					<div>
						<form method="POST" action="script/updateTicket.php">
							<input  hidden name="ticketId" value=<?php echo $result["ticket_id"] ?>>
							<textarea class="form-control" name="updateTicket" rows="8"><?php echo (isset($_POST["postForm"]["updateTicket"]))? $_POST["updateTicket"]: "" ?></textarea>
							<?php
							if(isset($_SESSION["errorUpdateTicket"]) && $_SESSION["errorUpdateTicket"]){
								echo '<h6 class="error">Erreur : Votre commentaire doit faire entre 10 et 1000 caractères!</h6>';
								unset($_SESSION["errorUpdateTicket"]);
							}
							?>
							<button type="submit" class="btn btn-info">Envoyer</button>
						</form>

						<form method="POST" action="script/updateTicket.php">
							<input  hidden name="closeTicket" value=<?php echo $result["ticket_id"] ?>>
							<button type="submit" class="btn btn-danger">Fermer le ticket</button>
						</form>
					</div>
				</div>
			</div>
			<?php
		}
		elseif($result["state"] == 1){
			?>
			<div class="row">
				<div class="col-4">
				</div>
				<div class="col-4">	
					<div>
						<h5 class="smallTitle">Ticket n°<?php echo $result["ticket_id"] ?></h5>
						<p>Titre : <?php echo $result["ticket_label"] ?></p>
						<p>Catégorie : <?php echo $result["category_name"]?></p>
						<p><u>Historique</u><br><?php echo $result["ticket_content"]?></p>
					</div>

					<div class="form-group">
						<div class="row">
							<div>
								<form method="POST" action="script/updateTicket.php">
									<input  hidden name="reopenTicket" value=<?php echo $result["ticket_id"] ?>>
									<button type="submit" class="btn btn-danger">Réouvrir le ticket</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	elseif(!isset($_GET["ticket_id"])){
		$connection = connectDB();
		$query = $connection->prepare("SELECT * from ticket,t_category where member= :member_id AND t_category=category_id ORDER BY last_update DESC");
		$query->execute([
			"member_id"=>$userInfo["member_id"]
		]);

		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if(!empty($result)){
			$i = 0;
			?>
			<hr><center><h3 class='smallTitle'>Tickets ouverts</h3></center><hr>
			<div class="row">
				<?php
				foreach ($result as $key => $ticket) {
					if ($ticket["state"] == 0){
						$i++;
						echo ($i%4 == 0)? '<div class="w-100"></div><div class="col-3 ticket">':'<div class="offset-md-1 col-md-3 ticket">'
						?>

						<h5 class="ticketTitle"><?php echo $ticket["ticket_label"] ?> (<?php echo $ticket["category_name"]?>)</h5>
						<i><p>Dernière mise à jour : <?php echo $ticket["ticket_date"]?></p></i>
						<h6>Extrait :</h6>
						<div class="ticketExtract"><?php echo $ticket["ticket_content"] ?></div>
						<form method="get">
							<input hidden name="ticket_id" value=<?php echo $ticket["ticket_id"] ?>>
							<button type="submit" class="btn openTicket">Ouvrir</button>
						</form>
					</div>
					<?php
				}	
			}
			?>
		</div>
		<hr><center><h3 class='smallTitle'>Tickets fermés</h3></center><hr>
		<div class="row">
			<?php
			foreach ($result as $key => $ticket) {
				if ($ticket["state"] == 1){
					$i++;
					echo ($i%4 == 0)? '<div class="w-100"></div><div class="col-3 ticket">':'<div class="offset-md-1 col-md-3 ticket">'
					?>

					<h5 class="ticketTitle"><?php echo $ticket["ticket_label"] ?> (<?php echo $ticket["category_name"]?>)</h5>
					<i><p>Dernière mise à jour : <?php echo $ticket["ticket_date"]?></p></i>
					<h6>Extrait :</h6>
					<div class="ticketExtract"><?php echo $ticket["ticket_content"] ?></div>
					<form method="get">
						<input hidden name="ticket_id" value=<?php echo $ticket["ticket_id"] ?>>
						<button type="submit" class="btn openTicket">Ouvrir</button>
					</form>
				</div>
				<?php
			}	
		}
	}

	else{
	echo '<center><h4 class="smallTitle">Vous n\'avez aucun tickets</h4></center>';
}
}

else{
$_SESSION["ticketError"] = true;
echo header("Location: userTicket.php");
}

include "footer.php";
}
else{
$_SESSION["previousLocation"] = "userTicket.php";
$_SESSION["connexionNeeded"] = "Vous devez être connecter pour accéder à cette page";
include "signup.php";
}