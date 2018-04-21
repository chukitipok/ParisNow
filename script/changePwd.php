<?php

    session_start();
    require "../conf.inc.php";
    require "../functions.php";

    $db = connectDB();
    $query = $db->prepare("SELECT member_password FROM member WHERE member_id = :id AND member_token = :token;");
    $query->execute([
        "id"=>$_SESSION["id"],
        "token"=>$_SESSION["token"]
    ]);
    $password = $query->fetch(PDO::FETCH_ASSOC);

    if (count($_POST) == 3){
        if( !empty($_POST["oldPwd"]) && !empty($_POST["newPwd"]) && !empty($_POST["confirmNewPwd"])) {

            $errorPwd = false;
            $listOfErrorsPwd = [];

            //confirmOldPwd = actualPwd
            if (!password_verify($_POST["oldPwd"], $password["member_password"])) {
                $error = true;
                $listeOfErrorsPwd[] = 1;
            }
            //pwd : min 8 max 20
            if (strlen($_POST["newPwd"]) < 8 || strlen($_POST["newPwd"]) > 20) {
                $errorPwd = true;
                $listeOfErrorsPwd[] = 2;
            }
            //pwdConfirm = pwd
            if ($_POST["newPwd"] != $_POST["confirmNewPwd"]) {
                $errorPwd = true;
                $listeOfErrorsPwd[] = 3;
            }
            if ($errorPwd) {
                $_SESSION["changePwd"] = false;
                $_SESSION["errorFormPwd"] = $listeOfErrorsPwd;
                $_SESSION["postFormPwd"] = $_POST;
                header("Location: ../userSettings.php");
            } else {
                $query = $db->prepare("UPDATE member SET member_password = :password 
                                                WHERE member_id = :id AND member_token = :token;");
                $newPwd = $_POST["newPwd"];
                $query->execute([
                    "password" => password_hash($newPwd, PASSWORD_DEFAULT),
                    "id" => $_SESSION["id"],
                    "token" => $_SESSION["token"]
                ]);
                header("Location: ../userSettings.php");
            }
        }else {
            $listeOfErrorsPwd[] = 4;
            $_SESSION["changePwd"] = false;
            $_SESSION["errorFormPwd"] = $listeOfErrorsPwd;
            $_SESSION["postFormPwd"] = $_POST;
            header("Location: ../userSettings.php");
        }
    }else{
        die("Tentative hack");
    }

?>