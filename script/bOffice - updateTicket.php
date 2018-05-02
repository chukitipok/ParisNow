<?php
session_start();
require "../conf.inc.php";
require "../functions.php";
preventXSS($_POST);
print_r($_POST);

$listOfError = [];

if(isset($_POST["updateTicket"]) && isset($_POST["ticketId"])){ // This part handle if a new message is sent
	$ticket = ticketInformationBackOffice($_POST);
	if(empty($ticket)){
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["ticketId"]);
	}

	elseif($ticket["state"] == 2){
		$listOfError[] = 6;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["ticketId"]);
	}

	elseif($ticket["state"] == 0){
		$_POST["updateTicket"] = trim($_POST["updateTicket"]);
		if(strlen($_POST["updateTicket"]) == 0){
			$listOfError[] = 1;
			header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["ticketId"]);
		}
		else{
			$time = getTimeForLog();
			$userInfo = getInfo("member_id, member_lastname, member_firstname");
			$connection = connectDB();
			$query = $connection->prepare("UPDATE ticket SET ticket_content= :content, last_update= NOW(), author_last_update= :author_last_update WHERE ticket_id= :id");
			$query->execute([
				"content"=>$ticket["ticket_content"]."<br><u>".$userInfo["member_firstname"]." ".$userInfo["member_lastname"]." le ".$time." :</u> <br>".$_POST["updateTicket"]."<br>",
				"id"=>$_POST["ticketId"],
				"author_last_update"=>$userInfo["member_id"]
			]);

			header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["ticketId"]);
		}
	}

	elseif($ticket["state"] == 1){
		$listOfError[]= 2;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["ticketId"]);
	}

	else{
		$listOfError[] = 3;
		header("Location :../bOffice - ticket.php?ticket_id=".$_POST["ticketId"]);
	}
}

elseif(isset($_POST["reopenTicket"])){ // This part handle if a ticket is reopen
	$ticket = ticketInformationBackOffice($_POST);
	if(empty($ticket)){
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["reopenTicket"]);
	}

	elseif($ticket["state"] == 2){
		$listOfError[] = 6;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["reopenTicket"]);
	}

	elseif($ticket["state"] == 0){
		$listOfError[] = 4;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["reopenTicket"]);
	}

	else{
		$time = getTimeForLog();
		$userInfo = getInfo("member_id,member_firstname,member_lastname");
		$connection = connectDB();
		$query = $connection->prepare("UPDATE TICKET set state= :state, ticket_content= :content, last_update= NOW(), author_last_update= :author_last_update WHERE ticket_id= :id");
		$query->execute([
			"state"=>0,
			"content"=>$ticket["ticket_content"]."<br><i>".$userInfo["member_firstname"]." ".$userInfo["member_lastname"]." a réouvert le ticket le ".$time."<br></i>",
			"id"=>$_POST["reopenTicket"],
			"author_last_update"=>$userInfo["member_id"]
		]);

		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["reopenTicket"]);
	}
}

elseif(isset($_POST["closeTicket"])){ // This part handle if a ticket is close
	$ticket = ticketInformationBackOffice($_POST);
	if(empty($ticket)){
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["closeTicket"]);
	}

	elseif($ticket["state"] == 2){
		$listOfError[] = 6;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["closeTicket"]);
	}

	elseif($ticket["state"] == 1){
		$listOfError[] = 5;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["closeTicket"]);
	}

	else{
		$time = getTimeForLog();
		$userInfo = getInfo("member_id, member_lastname, member_firstname");
		$connection = connectDB();
		$query = $connection->prepare("UPDATE ticket SET state= :status, ticket_content= :content, last_update= NOW(), author_last_update= :author_last_update WHERE ticket_id= :id");
		$query->execute([
			"status"=>1,
			"content"=>$ticket["ticket_content"]."<br><i>".$userInfo["member_firstname"]." ".$userInfo["member_lastname"]." a fermé le ticket le ".$time."</i><br>",
			"id"=>$_POST["closeTicket"],
			"author_last_update"=>$userInfo["member_id"]
		]);

		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["closeTicket"]);
	}
}


elseif(isset($_POST["defCloseTicket"])){ // This part handle if a ticket is definitely close
	$ticket = ticketInformationBackOffice($_POST);
	if(empty($ticket)){
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["defCloseTicket"]);
	}

	elseif($ticket["state"] == 2){
		$listOfError[] = 6;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["defCloseTicket"]);
	}

	else{
		$time = getTimeForLog();
		$userInfo = getInfo("member_id, member_lastname, member_firstname");
		$connection = connectDB();
		$query = $connection->prepare("UPDATE ticket SET state= :status, ticket_content= :content, last_update= NOW(), author_last_update= :author_last_update WHERE ticket_id= :id");
		$query->execute([
			"status"=>2,
			"content"=>$ticket["ticket_content"]."<br><b><i>".$userInfo["member_firstname"]." ".$userInfo["member_lastname"]." a définitivement fermé ce ticket le ".$time."</i></b><br>",
			"id"=>$_POST["defCloseTicket"],
			"author_last_update"=>$userInfo["member_id"]
		]);

		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["defCloseTicket"]);
	}
}

elseif(isset($_POST["backToTreatment"])){ // This part is handle if a ticket is sent back to treatment (It will appears back in the part "A traiter")
	$ticket = ticketInformationBackOffice($_POST);
	if(empty($ticket)){
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["backToTreatment"]);
	}

	elseif($ticket["state"] == 2){
		$listOfError[] = 6;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["backToTreatment"]);
	}

	elseif($ticket["state"] == 3 || ($ticket["state"] == 0 && $ticket["author_last_update"] == $ticket["member"])){
		$listOfError[] = 7;
		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["backToTreatment"]);
	}

	else{
		$time = getTimeForLog();
		$userInfo = getInfo("member_id, member_lastname, member_firstname");
		$connection = connectDB();
		$query = $connection->prepare("UPDATE ticket SET state= :status, last_update= NOW(), author_last_update= :author_last_update WHERE ticket_id= :id");
		$query->execute([
			"status"=>3,
			"id"=>$_POST["backToTreatment"],
			"author_last_update"=>$userInfo["member_id"]
		]);

		header("Location: ../bOffice - ticket.php?ticket_id=".$_POST["backToTreatment"]);
	}
}

else{ // If no actions known by the script is sent then the user is sent back on his previous location with an error message
	$_SESSION["falseTicket"] = true;
	isset($_SESSION["location"])? Header("Location: ../".$_SESSION["location"]): Header("Location: ../bOffice - ticketsToDo.php");
}

$_SESSION["ticketError"] = $listOfError;