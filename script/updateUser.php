<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

//echo "<pre>";
//print_r( $_SESSION );
//echo "</pre>";
//die();
$error = false;

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
        if( strlen($_POST["newPwd"])<8 || strlen($_POST["newPwd"])>20 ){
            $error = true;
//            $listeOfErrors[] = 9;
        }
        //pwdConfirm = pwd
        if( $_POST["newPwd"] != $_POST["confirmNewPwd"] ){
            $error = true;
//            $listeOfErrors[] = 10;
        }
        if($error){
//            $_SESSION["errorForm"] = $listeOfErrors;
            $_SESSION["postForm"] = $_POST;
            header("Location: ../user.php");

        }else{
            $query = $db->prepare("UPDATE member SET member_password = :password 
                                            WHERE member_id = :id AND member_token = :token;");
            $newPwd = $_POST["newPwd"];
            $query->execute([
                "password"=>password_hash($newPwd, PASSWORD_DEFAULT),
                "id"=>$_SESSION["id"],
                "token"=>$_SESSION["token"]
            ]);

            header("Location: ../user.php");
        }
    }

}
