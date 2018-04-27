<?php  
$navigation[$_SERVER["PHP_SELF"]] = 1;
?>

<div class="row">
	<div class="col-4"></div>
	<div class="col-4">
		<nav class="navbar">
			<a href="bOffice - ticketsToDo.php" class="<?php echo (isset($navigation["/parisnow/bOffice - ticketsToDo.php"]))? "locationMenu": "" ?>"><button class="btn">A traiter</button></a>
			<a href="bOffice - ticketsOpen.php" class="<?php echo (isset($navigation["/parisnow/bOffice - ticketsOpen.php"]))? "locationMenu": "" ?>"><button class="btn">Tickets Ouverts</button></a>
			<a href="bOffice - ticketsClosed.php" class="<?php echo (isset($navigation["/parisnow/bOffice - ticketsClosed.php"]))? "locationMenu": "" ?>"><button class="btn">Tickets Fermés</button></a>
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
if(isset($_SESSION["falseTicket"])){
	echo "<h3>Ce ticket n'existe pas</h3>";
	unset($_SESSION["falseTicket"]);
}

if(isset($_GET["ticket_id"])){
	$connect = connectDB();
	$query = $connect->prepare("SELECT ticket_id,author_last_update, member_firstname, member_lastname,category_name,last_update,ticket_date,ticket_label,state,member FROM TICKET,MEMBER,T_CATEGORY where member = member_id AND category_id = t_category AND ticket_id= :ticket_id ORDER BY last_update DESC");
	$query->execute([
		"ticket_id"=>$_GET["ticket_id"]
	]);
	$tickets = $query->fetchAll(PDO::FETCH_ASSOC);
}

else{
	$connect = connectDB();
	$query = $connect->prepare("SELECT ticket_id,author_last_update, member_firstname, member_lastname,category_name,last_update,ticket_date,ticket_label,state,member FROM TICKET,MEMBER,T_CATEGORY where member = member_id AND category_id = t_category ORDER BY last_update DESC");
	$query->execute();
	$tickets = $query->fetchAll(PDO::FETCH_ASSOC);
} ?> 