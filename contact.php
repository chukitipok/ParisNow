<?php
require "header.php";
preventXSS($_POST);
if(isConnected()){
?>
	<div class="container-fluid col-3">
		<h3>Nous envoyer un ticket</h3>
		<form method="POST">


			<div class="row">
				<label>Catégorie du ticket</label>
			</div>
				<select>
					<?php foreach ($categoryOfContact as $key => $value) {
						echo "<option name='t_category'>".$value."</option>";
					}
					?>
				</select>

			<div class="row">
				<label>Titre du ticket</label>
			</div>
			<div class="row">
				<input name="ticket_label" type="text">
			</div>

			<div class="row">
				<label>Description du ticket</label>
			</div>
			<div class="row">
				<input name="ticket_content" type="text">
			</div>

			<div class="row">
				<input type="submit" value="Envoyer"></input>
			</div>
		</form>
	</div>
<?php
}

else{
	$_SESSION["previousLocation"] = "contact.php";
	$_SESSION["connexionNeeded"] = "Vous devez être connecter pour accéder à cette page";
	include "signup.php";
}
include_once "footer.php";
?>
