<?php
require "bOffice - header.php";
include "bOffice - ticketsMenu.php";

$_SESSION["location"] = "bOffice - ticketsClosed.php";
?>

<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Catégorie</th>
			<th>Auteur</th>
			<th>Titre</th>
			<th>Auteur dernière MAJ</th>
			<th>Mis à jour</th>
			<th>Création</th>
			<th>Statut</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($tickets as $line => $ticket) {
			if($ticket["state"] == 1 || $ticket["state"] == 2){
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
				echo "<td>";
				switch ($ticket["state"]){
					case 1:
					echo "Fermé";
					break;

					case 2:
					echo "Fermé définitivement";
					break;

					default:
					echo "erreur";
				}
				echo "</td>";
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