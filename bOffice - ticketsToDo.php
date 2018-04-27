<?php
require "bOffice - header.php";
include "bOffice - ticketsMenu.php";

$_SESSION["location"] = "bOffice - ticketsToDo.php";
?>

<table class='table'>
	<thead>
		<tr>
			<th>ID</th>
			<th>Catégorie</th>
			<th>Auteur</th>
			<th>Titre</th>
			<th>Mis à jour</th>
			<th>Création</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($tickets as $line => $ticket) {
			if(($ticket["state"] == 0 && ($ticket["author_last_update"] == $ticket["member"])) || $ticket["state"] == 3){
				echo "<td>".$ticket["ticket_id"]."</td>";
				echo "<td>".$ticket["category_name"]."</td>";
				echo "<td>".$ticket["member_firstname"]." ".$ticket["member_lastname"]."</td>";
				echo "<td>".$ticket["ticket_label"]."</td>";
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

<?php include "bOffice - footer.php"; ?>