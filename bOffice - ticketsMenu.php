<div class="row">
	<div class="col-4"></div>
	<div class="col-4">
		<nav class="navbar">
			<a href="bOffice - ticketsToDo.php" class="<?php echo (isset($navigation["/parisnow/bOffice - ticketsToDo.php"]))? "locationMenu": "" ?>"><button class="btn menuticket">A traiter</button></a>
			<a href="bOffice - ticketsOpen.php" class="<?php echo (isset($navigation["/parisnow/bOffice - ticketsOpen.php"]))? "locationMenu": "" ?>"><button class="btn menuticket">Tickets Ouverts</button></a>
			<a href="bOffice - ticketsClosed.php" class="<?php echo (isset($navigation["/parisnow/bOffice - ticketsClosed.php"]))? "locationMenu": "" ?>"><button class="btn menuticket">Tickets Fermés</button></a>
		</nav>
	</div>
</div>

<div class="row">
	<div class="col research">
		<form method="GET" class="form-inline">
			<div class="actionButton">
				<input type="text" class="form-control" id="searchId" placeholder="ID" name="ticket_id" value="">
			</div>
			<div>
				<button type="submit" class="btn button">Search</button>
			</div>
		</form>
	</div>
</div>
<?php 
if(isset($_SESSION["falseTicket"])){ // Condition that show an error if the user try to access a tickets that does not exist by the page bOffice - ticket?ticket_id="false_id_of_ticket" or an empty/not_set ticket_id
	echo "<h3>Ce ticket n'existe pas</h3>";
	unset($_SESSION["falseTicket"]);
}

if(isset($_GET["ticket_id"])){ // If the user make a search with the form above php code it will search the ticket corresponding to the id
	$connect = connectDB();
	$query = $connect->prepare("SELECT ticket_id,author_last_update, member_firstname, member_lastname,category_name,last_update,ticket_date,ticket_label,state,member FROM TICKET,MEMBER,T_CATEGORY where member = member_id AND category_id = t_category AND ticket_id= :ticket_id ORDER BY last_update DESC");
	$query->execute([
		"ticket_id"=>$_GET["ticket_id"]
	]);
	$tickets = $query->fetchAll(PDO::FETCH_ASSOC);
}

else{ // If no research are done it will fetch all tickets from the database.
	$connect = connectDB();
	$query = $connect->prepare("SELECT ticket_id,author_last_update, member_firstname, member_lastname,category_name,last_update,ticket_date,ticket_label,state,member FROM TICKET,MEMBER,T_CATEGORY where member = member_id AND category_id = t_category ORDER BY last_update DESC");
	$query->execute();
	$tickets = $query->fetchAll(PDO::FETCH_ASSOC);
} ?> 