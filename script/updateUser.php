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
if (!empty($_POST["NOM"])
    && !empty($_POST["PRENOM"])
    && !empty($_POST["EMAIL"])
    && !empty($_POST["ADRESSE"])
    && !empty($_POST["CODE"])
){
    //Nettoyer les valeurs
    $_POST["PRENOM"] = ucfirst(trim(mb_strtolower($_POST["PRENOM"])));
    $_POST["NOM"] = trim(strtoupper($_POST["NOM"]));
    $_POST["EMAIL"] = trim(mb_strtolower($_POST["EMAIL"]));
    $_POST["ADRESSE"] = trim(mb_strtolower($_POST["ADRESSE"]));

    //firstname : min 2 max 32
    if( strlen($_POST["PRENOM"])<2 || strlen($_POST["PRENOM"])>32 ){
        $error = true;
//        $listeOfErrors[] = 2;
    }
    //lastname : min 2 max 50
    if( strlen($_POST["NOM"])<2 || strlen($_POST["NOM"])>50 ){
        $error = true;
//        $listeOfErrors[] = 3;
    }
    //email : format valide
    if( !filter_var($_POST["EMAIL"], FILTER_VALIDATE_EMAIL)   ){
        $error = true;
//        $listeOfErrors[] = 4;
    }else {//verifie que l'email n'existe pas déja
        $query = $db->prepare("SELECT 1 FROM member WHERE EMAIL = :email");
        $query->execute(["email" => $_POST["EMAIL"]]);
        $result = $query->fetch();
        if(!empty($result)){
            $error = true;
//            $listeOfErrors[] = 11;
        }
    }
    if ($error){
        die("ERRRRROOOOOORRRRRRR !!!!!");
    }else{
        $query = $db->prepare("UPDATE member 
                                        SET member_lastname = :lastname,
                                            member_firstname = :firstname,
                                            member_email = :email,
                                            member_zip_code = :zip_code
                                        WHERE member_id = :id AND member_token = :token;");
        $query->execute([
            "lastname"=>$_POST["NOM"],
            "firstname"=>$_POST["PRENOM"],
            "email"=>$_POST["EMAIL"],
            "zip_code"=>$_POST["CODE"],
            "id"=>$_SESSION["id"],
            "token"=>$_SESSION["token"],
        ]);
    }
    header("Location: ../user.php");
}