<?php
include "../conf.inc.php";
include "../functions.php";
session_start();
preventXSS($_POST);

if(count($_POST) == 3
	&& !empty($_POST["t_category"])
	&& !empty($_POST["ticket_label"])
	&& !empty($_POST["ticket_content"])
){
	$error=false;
	$listOfTicketError = [];
	if(!array_key_exists($_POST["t_category"], $categoryOfContact)){
		$error = true;
		$listOfTicketError[] = 1;
	}

	if(strlen($_POST["ticket_label"]) > 60 || strlen($_POST["ticket_label"]) < 4){
		$error = true;
		$listOfTicketError[] = 2;
	}

	$_POST["ticket_label"] = ucfirst(trim(mb_strtolower($_POST["ticket_label"])));
	$_POST["ticket_content"] = trim($_POST["ticket_content"]);

	if(strlen($_POST["ticket_content"]) > 1000 || strlen($_POST["ticket_content"]) < 10){
		$error=true;
		$listOfTicketError[] = 3;
	}

	if($error){
		$_SESSION["errorTicket"] = $listOfTicketError;
		$_SESSION["postForm"] = $_POST;
		header("Location: ../contact.php");
	}
	else{
		$alreadyExist = false;
		while(!isset($ticketId) || $alreadyExist){
			$alreadyExist = false;
			$ticketId = createTicketId();
			$connection = connectDB();
			$query = $connection->prepare("SELECT ticket_id from ticket");
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				foreach ($value as $key => $value) {
					if($ticketId == $value)
					$alreadyExist = true;
				}
			}
		}
		$time = getTimeForlog();
		$result = getInfo("member_id, member_firstname, member_lastname");
		echo $result["member_id"]."<br>";
		echo $ticketId."<br>";
		$connection = connectDB();
		$ticketContent = "<u>".$result["member_firstname"]." ".$result["member_lastname"]." le ".$time." :</u> <br>".$_POST["ticket_content"]."<br>";
		echo $ticketContent."<br>";
		$query = $connection->prepare("INSERT INTO ticket(ticket_id,t_category,ticket_label,ticket_content, member,ticket_date,state, last_update, author_last_update) VALUES(:id, :category, :label, :ticket_content, :member, NOW(), :state, NOW(), :author_last_update)");
		$query->execute([
			"id"=>$ticketId,
			"category"=>$_POST["t_category"],
			"label"=>$_POST["ticket_label"],
			"ticket_content"=>$ticketContent,
			"member"=> $result["member_id"],
			"state"=>0,
			"author_last_update"=>$result["member_id"]
		]);

		$_SESSION["ticketSubmitted"] = true;
		$_SESSION["ticketId"] = $ticketId;
		header("Location: ../contact.php");
	}
}

else{
	$_SESSION["illegalForm"] = true;
	header("Location: ../contact.php");
}