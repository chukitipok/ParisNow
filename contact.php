<?php
require "header.php";

if(isConnected()){

	if(isset($_SESSION["ticketSubmitted"]) && $_SESSION["ticketSubmitted"]){
		echo '<center><h3 class="smallTitle success">Votre ticket n°'.$_SESSION["ticketId"].' à bien été envoyer !</h3></center>';
		echo '<center><h5>Vous pouvez y accéder en allant dans "Gestion de compte", puis "Suivis de mes tickets".<br>Ou en cliquant directement <a href="userTicket.php">ici</a></h5></center>';
		unset($_SESSION["ticketSubmitted"]);
		unset($_SESSION["ticketId"]);
	}

	elseif(isset($_SESSION["illegalForm"]) && $_SESSION["illegalForm"]){
		echo '<center><h3 class="smallTitle error">Erreur : Formulaire incorrect.</h3></center>';
		unset($_SESSION["illegalForm"]);
	}

	else{
		echo '<center><h3 class="smallTitle">Nous envoyer un ticket</h3></center>';
	}
	?>
	<div class="row">

		<div class="col-4">
		</div>

		<div class="col-4">
		<form method="POST" action="script/newTicket.php">
			<div class="form-group">
				<label>Catégorie du ticket</label>
				<select class="form-control" name=t_category>
					<?php foreach ($categoryOfContact as $key => $value) {
						echo "<option value=".$key." ";
						echo (isset($_SESSION["postForm"]["t_category"]) && $key == $_SESSION["postForm"]["t_category"])? 'selected="selected"':"";	
						echo ">".$value."</option>";
					}
					?>
				</select>
				<?php
				if(isset($_SESSION["errorTicket"])){
					foreach ($_SESSION["errorTicket"] as $key => $value) {
						echo ($value == 1)? '<h6 class="form-text error">'.$listOfTicketError[1].'</h6>':"";
					}
				}
				?>
			</div>

			<div class="form-group">
				<label>Titre du ticket</label>
				<input name="ticket_label" type="text" required="required" class="form-control" value=<?php echo (isset($_SESSION["postForm"]["ticket_label"]))? $_SESSION["postForm"]["ticket_label"]:""; ?>>
				<?php
				if(isset($_SESSION["errorTicket"])){
					foreach ($_SESSION["errorTicket"] as $key => $value) {
						echo ($value == 2)? '<h6 class="form-text error">'.$listOfTicketError[2].'</h6>':"";
					}
				}
				?>
				<small class="form-text text-muted">Max 60 caractères</small>
			</div>
		</div>
	</div>
	<div class="row">

		<div class="col-4">
		</div>

		<div class="col-4">
			<div class="form-group">
				<label>Description du ticket</label>
				<textarea class="form-control" name="ticket_content" rows="10"><?php echo (isset($_SESSION["postForm"]["ticket_content"]))? $_SESSION["postForm"]["ticket_content"]: ""?></textarea>
				<?php
				if(isset($_SESSION["errorTicket"])){
					foreach ($_SESSION["errorTicket"] as $key => $value) {
						echo ($value == 3)? '<h6 class="form-text error">'.$listOfTicketError[3].'</h6>':"";
					}
				}
				?>
			<button type="submit" class="btn btn-info">Envoyer</button>
			</div>
		</div>
		</form>
	</div>

	<?php
	unset($_SESSION["postForm"]);
	unset($_SESSION["errorTicket"]);
	include "footer.php";
}

else{
	$_SESSION["previousLocation"] = "contact.php";
    $_SESSION["connexionNeeded"] = "Vous devez être connecter pour accéder à cette page";
    include "signup.php";
}


?>