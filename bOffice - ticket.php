<?php
require "bOffice - header.php";

if(isset($_GET["ticket_id"]) && !empty($_GET["ticket_id"])){ // This condition will handle the $_GET["ticket_id"] to search if the ticket exists in database.
	$connect = connectDB();
	$query=$connect->prepare("SELECT category_name,member_lastname,member_firstname,ticket.* FROM ticket,member,t_category where ticket_id= :id AND member_id=member AND t_category = category_id");
	$query->execute([
		"id"=>$_GET["ticket_id"]
	]);
	$ticket = $query->fetch(PDO::FETCH_ASSOC);

	if(empty($ticket)){ // If the ticket is empty (= ticket does not exist), then the user is sent on the previous location or by default on ticketsToDo with an error message.
		$_SESSION["falseTicket"] = true;
		isset($_SESSION["location"])? Header("Location: ".$_SESSION["location"]) : Header("Location: bOffice - ticketsTodo.php");
	}

	$query = $connect->prepare("SELECT member_lastname, member_firstname FROM ticket,member where :author_last_update = member_id");
	$query->execute([
		"author_last_update"=> $ticket["author_last_update"]
	]);
	$lastUpdate = $query->fetch(PDO::FETCH_ASSOC);
	?>
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-3">
			<a href="<?php echo isset($_SESSION["location"])? $_SESSION["location"] :  "ticketsToDo.php"; ?>"><button class="btn btn-info backToTicketButton">Retour aux tickets</button></a>
			<div>
				<table class="table">
					<hr>
					<center><h6 style="margin-bottom: : 0px;">Informations du ticket</h6></center>
					<hr>

					<tr>
						<td>ID :</td>
						<td><?php echo $ticket["ticket_id"] ?></td>
					</tr>
					<tr>
						<td>Statut :</td>
						<td>
							<?php 
							switch ($ticket["state"]){
								case 3:
								case 0:
								echo "Ouvert";
								break;

								case 1:
								echo "Fermé";
								break;

								case 2:
								echo "Définitivement fermé";
								break;

								default:
								echo "Erreur";
								break;
							} 
							?>						
						</td>
					</tr>

					<tr>
						<td>Catégorie :</td>
						<td><?php echo $ticket["category_name"] ?></td>
					</tr>

					<tr>
						<td>Auteur :</td>
						<td><?php echo $ticket["member_firstname"]." ".$ticket["member_lastname"] ?></td>
					</tr>

					<tr>
						<td>Auteur dernière MAJ :</td>
						<td><?php echo $lastUpdate["member_firstname"]." ".$lastUpdate["member_lastname"] ?></td>
					</tr>

					<tr>
						<td>Dernière mise à jour :</td>
						<td><?php echo $ticket["last_update"] ?></td>
					</tr>

					<tr>
						<td>Date de création :</td>
						<td><?php echo $ticket["ticket_date"] ?></td>
					</tr>

				</table>
			</div>
		</div>

		<div class="col-md-5">
			<hr>
			<center><h6>Titre : <?php echo $ticket["ticket_label"] ?></h6></center>
			<hr>
			<?php 
			if(isset($_SESSION["ticketError"])){ // This loop show all error messages caming from user actions
				foreach ($_SESSION["ticketError"] as $key => $value) {
					echo "<h5>".$ticketErrorBackOffice[$value]."</h5>";
				}
				unset($_SESSION["ticketError"]);
			}
			?>
			<table class="table">
				<tr>
					<td><?php echo $ticket["ticket_content"] ?></td>
				</tr>
				<?php if($ticket["state"] != 1 && $ticket["state"] !=2){ ?>
				<tr>
					<td>
						<form method="POST" action="script/bOffice - updateTicket.php">
							<input hidden name="ticketId" value=<?php echo $ticket["ticket_id"] ?>>
							<textarea name="updateTicket" class="form-control"></textarea>
							<button type="submit" class="btn btn-info answerButton">Répondre</button>
						</form>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>

		<div class="col-md-2 actions">
			<div>
				<hr>
				<center><h6>Actions</h6></center> <!-- This part show all buttons dependings on ticket state -->
				<hr>
				<table class="table">
					<tr>
						<div class="actionButton">
							<td>
								<div class="row">
									<div class="actionButton">
										<form method="POST" action="script/bOffice - updateTicket.php">
											<input hidden name=<?php 
											echo ($ticket["state"] != 2)? ($ticket["state"] == 0 || $ticket["state"] == 3)? '"closeTicket"': '"reopenTicket"': ""; ?> value="<?php echo $ticket["ticket_id"] ?>">
											<button type="submit" class="button btn" <?php echo ($ticket["state"] == 2)? "disabled":""; echo ">"; echo ($ticket["state"] == 0 || $ticket["state"] == 3)? "Fermé":"Réouvrir" ?></button>
										</form>
									</div>
									<?php if($ticket["state"] == 0 && ($ticket["author_last_update"] != $ticket["member"])){
										?>
										<div class="actionButton">
											<form method="POST" action="script/bOffice - updateTicket.php">
												<input hidden name="backToTreatment" value="<?php echo $ticket["ticket_id"] ?>">
												<button type="submit" class="button btn">Retour en traitement</button>
											</form>
										</div>
										<?php } ?>
									</td>
								</div>
							</tr>
							<?php 
							if($ticket["state"] != 2){
								?>
								<tr>
									<td class="align-middle">
										<form method="POST" action="script/bOffice - updateTicket.php">
											<input hidden name="defCloseTicket" value="<?php echo $ticket["ticket_id"] ?>">
											<button type="submit" class="btn btn-danger">Fermé definitivement</button>
										</form>
									</td>
								</tr>
								<?php
							}
							?>		
						</table>
					</div>
				</div>
			</div>
			<?php
		}

		else{
			$_SESSION["falseTicket"] = true;
			isset($_SESSION["location"])? Header("Location: ".$_SESSION["location"]):Header("location: bOffice - ticketsTodo.php");
		}

		include "bOffice - footer.php";