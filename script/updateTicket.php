<?php
session_start();
require "../conf.inc.php";
require "../functions.php";
preventXSS($_POST);
print_r($_POST);

if(isset($_POST["closeTicket"]) && !empty($_POST["closeTicket"])){
	$ticket = ticketInformation($_POST);

	if(empty($ticket)){
		$_SESSION["ticketError"] = true;
		header("Location: ../userTicket.php");
	}

	elseif(!empty($ticket) && $ticket["state"] == 1){
		$_SESSION["ticketAlreadyClose"] = true;
		header("Location: ../userTicket.php?ticket_id=".$_POST["closeTicket"]);
	}

	else{
		$time = getTimeForLog();
		$userInfo = getInfo("member_id, member_lastname, member_firstname");
		$connection = connectDB();
		$query = $connection->prepare("UPDATE ticket SET state= :status, ticket_content= :content, last_update= NOW() WHERE member= :member_id AND ticket_id= :id");
		$query->execute([
			"status"=>1,
			"content"=>$ticket["ticket_content"]."<br>".$userInfo["member_firstname"]." ".$userInfo["member_lastname"]." a fermé le ticket le ".$time."<br>",
			"member_id"=>$userInfo["member_id"],
			"id"=>$_POST["closeTicket"]
		]);

		header("Location: ../userTicket.php?ticket_id=".$_POST["closeTicket"]);
	}
}

elseif(isset($_POST["updateTicket"]) && isset($_POST["ticketId"])){
	$ticket = ticketInformation($_POST);
	if(empty($ticket)){
		$_SESSION["ticketError"] = true;
		header("Location: ../userTicket.php");
	}

	elseif(strlen($_POST["updateTicket"]) > 10 || strlen($_POST["updateTicket"]) < 1000){
		$time = getTimeForLog();
		$userInfo = getInfo("member_id, member_lastname, member_firstname");
		$connection = connectDB();
		$query = $connection->prepare("UPDATE ticket SET ticket_content= :content, last_update= NOW() WHERE member= :member_id AND ticket_id= :id");
		$query->execute([
			"content"=>$ticket["ticket_content"]."<br><u>".$userInfo["member_firstname"]." ".$userInfo["member_lastname"]." le ".$time." :</u> <br>".$_POST["updateTicket"]."<br>",
			"member_id"=>$userInfo["member_id"],
			"id"=>$_POST["ticketId"]
		]);
		
		header("Location: ../userTicket.php?ticket_id=".$_POST["ticketId"]);
	}

	else{
		$_SESSION["postForm"] = $_POST["updateTicket"];
		$_SESSION["errorUpdateTicket"] = true;
		echo strlen($_POST["updateTicket"]);
		//header("Location: ../userTicket.php?ticket_id=".$_POST["ticketId"]);
	}
}

elseif(isset($_POST["reopenTicket"])){
	$ticket = ticketInformation($_POST);
	if(empty($ticket)){
		$_SESSION["ticketError"] = true;
		header("Location: ../userTicket.php");
	}

	elseif(!empty($ticket) && $ticket["state"] == 0){
		$_SESSION["ticketAlreadyOpen"] = true;
		header("Location: ../userTicket.php?ticket_id=".$_POST["closeTicket"]);
	}

	else{
		$time = getTimeForLog();
		$userInfo = getInfo("member_id,member_firstname,member_lastname");
		$connection = connectDB();
		$query = $connection->prepare("UPDATE TICKET set state= :state, ticket_content= :content, last_update= NOW() WHERE member= :member_id AND ticket_id= :id");
		$query->execute([
			"state"=>0,
			"content"=>$ticket["ticket_content"]."<br>".$userInfo["member_firstname"]." ".$userInfo["member_lastname"]." a réouvert le ticket le".$time."<br>",
			"member_id"=>$userInfo["member_id"],
			"id"=>$_POST["reopenTicket"]
		]);

		header("Location: ../userTicket.php?ticket_id=".$_POST["reopenTicket"]);
	}
}

else{
	$_SESSION["ticketError"] = true;
	//header("Location: ../userTicket.php");
}