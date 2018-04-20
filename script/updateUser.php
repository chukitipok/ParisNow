<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

//echo "<pre>";
//print_r( $_SESSION );
//echo "</pre>";
//die();

$db = connectDB();
$query = $db->prepare("SELECT member_password FROM member WHERE member_id = :id AND member_token = :token;");
$query->execute([
    "id"=>$_SESSION["id"],
    "token"=>$_SESSION["token"]
]);
$password = $query->fetch(PDO::FETCH_ASSOC);

if (!empty($_POST["oldPwd"]) && !empty($_POST["newPwd"]) && !empty($_POST["confirmNewPwd"])) {
    if (password_verify($_POST["oldPwd"], $password["member_password"])){
        //pwd : min 8 max 20
        if( strlen($_POST["pwd"])<8 || strlen($_POST["pwd"])>20 ){
            $error = true;
//            $listeOfErrors[] = 9;
        }
        //pwdConfirm = pwd
        if( $_POST["newPwd"] != $_POST["confirmNewPwd"] ){
            $error = true;
//            $listeOfErrors[] = 10;
        }
        if($error){
            $_SESSION["errorForm"] = $listeOfErrors;
            $_SESSION["postForm"] = $_POST;
            header("Location: ../user.php");

        }else{
 
        }
    }
}
