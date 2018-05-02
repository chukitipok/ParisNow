<?php
require "bOffice - header.php";
include "bOffice - ticketsMenu.php";

$_SESSION["location"] = "bOffice - ticketsOpen.php"; //This session is used for the value of the "href" of the <a> balise named "Retour aux tickets" in the bOffice - ticket.php page.
?>

<table class='table table-striped'>
	<thead>
		<tr>
			<th>ID</th>
			<th>Catégorie</th>
			<th>Auteur</th>
			<th>Titre</th>
			<th>Auteur dernière MAJ</th>
			<th>Mis à jour</th>
			<th>Création</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($tickets as $line => $ticket) {
			if($ticket["state"] == 0 && ($ticket["author_last_update"] != $ticket["member"])){ // It show all tickets that are not closed but that the staff have already treated. So tickets were the author of last update is not the creator. 
				$query = $connect->prepare("SELECT member_lastname, member_firstname FROM ticket,member where :author_last_update = member_id");
				$query->execute([
					"author_last_update"=> $ticket["author_last_update"]
				]);
				$lastUpdate = $query->fetch(PDO::FETCH_ASSOC);
				echo "<td>".$ticket["ticket_id"]."</td>";
				echo "<td>".$ticket["category_name"]."</td>";
				echo "<td>".$ticket["member_firstname"]." ".$ticket["member_lastname"]."</td>";
				echo "<td>".$ticket["ticket_label"]."</td>";
				echo "<td>".$lastUpdate["member_firstname"]." ".$lastUpdate["member_lastname"]."</td>";
				echo "<td>".$ticket["last_update"]."</td>";
				echo "<td>".$ticket["ticket_date"]."</td>";
				?>
				<td>
					<form method="POST" action="bOffice - ticket.php?ticket_id=<?php echo $ticket["ticket_id"] ?>">
						<button type="submit" class="btn openButton">Ouvrir</button>
					</form>
				</td>
				<?php
			}
			echo "</tr>";
		}
		?>
	</tbody>
</table>

<?php include "bOffice - footer.php" ?>