<?php

require_once "conf.inc.php";

function connectDB(){
  try{
     $connection = new PDO(DBDRIVER.":host=".DBHOST.";dbname=".DBNAME.";charset=".CHARSET,DBUSER,DBPWD);
     $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }
 catch(Exception $e){
     die("Erreur SQL :".$e->getMessage());	
 }	
 return $connection;
}

function isConnected(){
    if(isset($_SESSION["auth"]) && $_SESSION["auth"]){
        return true;
    }
    return false;
}

function createToken(){
    return md5(uniqid(rand(), true));
}

function preventXSS(){
	foreach ($_POST as $key => $value) {
       $_POST[$key] = htmlspecialchars($_POST[$key]);
   }
   return $_POST;
}

/*function findLocation(){
	$directory = explode(chr(92), dirname($_SESSION["previousLocation"]));
	if($directory[(count($directory)-1)] == "Projet Annuel")
		return $_SESSION["previousLocation"];
	else {
		$_SESSION["previousLocation"] = "../".$_SESSION["previousLocation"];
		return findLocation();
	}
}*/

function Location(){
	if(isset($_SESSION["previousLocation"]) && !empty($_SESSION["previousLocation"])){
        $newLocation = $_SESSION["previousLocation"];
        unset($_SESSION["previousLocation"]);

        if(isset($_SESSION["signUp"])){
           unset($_SESSION["signUp"]);
           return header("Location: ../".$newLocation);
       }
       else{
          return header("Location: ".$newLocation);
      }
  }
  else{
   if(isset($_SESSION["signUp"]) && $_SESSION["signUp"]){
      unset($_SESSION["signUp"]);
      return header("Location: ../index.php");
  }
  elseif(isset($_SESSION["signUp"]) && !$_SESSION["signUp"]){
      return header("Location: ../signup.php");
  }
  else
      return header("Location: index.php");
}
}

function connectUser()
{
    $connection = connectDB();
    $query = $connection->prepare("SELECT * FROM member WHERE member_email = :email;");
    $query->execute([
        "email" => $_SESSION["emailConnect"]
    ]);
    $result = $query->fetch();
    if ($result["member_status"] == 3) {
        session_unset();
        session_destroy();
        echo "Ce compte est bannit";
    } elseif (password_verify($_SESSION["pwdConnect"], $result["member_password"])) {
        $_SESSION["auth"] = true;
        $_SESSION["id"] = $result["member_id"];
        $_SESSION["token"] = createToken();
        $query = $connection->prepare("UPDATE member SET member_token = :token WHERE member_id = :id;");
        $query->execute([
            "token" => $_SESSION["token"],
            "id" => $_SESSION["id"]
        ]);
        unset($_SESSION["pwdConnect"]);
        unset($_SESSION["emailConnect"]);
        return location();
    }else{
     echo "NOK";
     $file = fopen('log.txt', 'a+');
     fwrite($file, $_POST["emailConnect"] . " -> " . $_POST["pwdConnect"] . "\r\n");
     fclose($file);
 }
}

function getInfo($column){
    if(isConnected()){
        if(isset($column)){
            $connection = connectDB();
            $query = $connection->prepare("SELECT ".$column." FROM member WHERE member_id = :id AND member_token = :token;");
            $query->execute([
                "id"=> $_SESSION["id"],
                "token" => $_SESSION["token"]
            ]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }
}

function createTicketId(){
    $id = rand(1000000000, 9999999999);
    return $id;
} 

function unsetAdmin(){
    if(isset($_SESSION["admin"]))
        unset($_SESSION["admin"]);
    if(isset($_SESSION["moderateur"]))
        unset($_SESSION["moderateur"]);
    if(isset($_SESSION["status"]))
        unset($_SESSION["status"]);
    if(isset($_SESSION["name"]))
        unset($_SESSION["name"]);
    if(isset($_SESSION["firstName"]))
        unset($_SESSION["firstName"]);
}

function ticketID(){
    if(isset($_POST["closeTicket"])){
        $ticketID = $_POST["closeTicket"];
    }

    elseif(isset($_POST["ticketId"])){
        $ticketID = $_POST["ticketId"];
    }

    elseif(isset($_POST["reopenTicket"])){
        $ticketID = $_POST["reopenTicket"];
    }

    elseif(isset($_POST["defCloseTicket"])){
        $ticketID = $_POST["defCloseTicket"];
    }

    elseif(isset($_POST["backToTreatment"])){
        $ticketID = $_POST["backToTreatment"];
    }

    else{
        return;
    }

    return $ticketID;
}

function ticketInformation(){
    $ticketID = ticketID($_POST);
    $userInfo = getInfo("member_id");
    $connection = connectDB();
    $query = $connection->prepare("SELECT * FROM ticket WHERE member= :member_id AND ticket_id= :id");
    $query->execute([
        "member_id"=>$userInfo["member_id"],
        "id"=>$ticketID
    ]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function ticketInformationBackOffice(){
    $ticketID = ticketID($_POST);
    $userInfo = getInfo("member_id");
    $connection = connectDB();
    $query = $connection->prepare("SELECT * FROM ticket WHERE ticket_id= :id");
    $query->execute([
        "id"=>$ticketID
    ]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function getTimeForLog(){
    $time = time();
    $date = new DateTime("now", new DateTimeZone('Europe/Paris'));
    $date->setTimestamp($time);
    $actualDate = $date->format('d/m/Y à H\h:i\m:s\s');
    return $actualDate;
}

function verif_alpha($str){
    preg_match("/([^A-Za-z])/",$str,$result);
//On cherche tt les caractères autre que [A-z]
    if(!empty($result)){//si on trouve des caractère autre que A-z
        return false;
    }
    return true;
}