<?php
    session_start();
    require "../conf.inc.php";
    require "../functions.php";

    $db = connectDB();

    if (count($_POST) == 5) {

        $errorInfo = false;
        $listOfErrorsInfo = [];

        //clean values
        $_POST["PRENOM"] = ucfirst(trim(mb_strtolower($_POST["PRENOM"])));
        $_POST["NOM"] = trim(strtoupper($_POST["NOM"]));
        $_POST["EMAIL"] = trim(mb_strtolower($_POST["EMAIL"]));
        $_POST["ADRESSE"] = trim(mb_strtoupper($_POST["ADRESSE"]));

        //values not empty
        if (empty($_POST["NOM"]) || empty($_POST["PRENOM"]) || empty($_POST["EMAIL"]) || empty($_POST["ADRESSE"]) || empty($_POST["CODE"])) {
            $errorInfo = true;
            $listOfErrorsInfo[] = 7;
        }
        //firstname : min 2 max 32
        if (strlen($_POST["PRENOM"]) < 2 || strlen($_POST["PRENOM"]) > 32 || is_numeric($_POST["PRENOM"])) {
            $errorInfo = true;
            $listOfErrorsInfo[] = 2;
        }
        //lastname : min 2 max 50
        if (strlen($_POST["NOM"]) < 2 || strlen($_POST["NOM"]) > 50 || is_numeric($_POST["NOM"])) {
            $errorInfo = true;
            $listOfErrorsInfo[] = 1;
        }
        //email : format valide
        if (!filter_var($_POST["EMAIL"], FILTER_VALIDATE_EMAIL)) {
            $errorInfo = true;
            $listOfErrorsInfo[] = 3;
        } else {//verifie que l'email n'existe pas déja
            $query = $db->prepare("SELECT 1 FROM member WHERE EMAIL = :email");
            $query->execute(["email" => $_POST["EMAIL"]]);
            $result = $query->fetch();
            if (!empty($result)) {
                $errorInfo = true;
                $listOfErrorsInfo[] = 6;
            }
        }
        if (!is_numeric($_POST["CODE"]) || strlen($_POST["CODE"]) != 5){
            $errorInfo = true;
            $listOfErrorsInfo[] = 5;
        }
        if ($errorInfo) {
            $_SESSION["update"] = false;
            $_SESSION["errorFormInfo"] = $listOfErrorsInfo;
            $_SESSION["postFormInfo"] = $_POST;
            header("Location: ../userSettings.php");
        } else {
            $query = $db->prepare("UPDATE member 
                                        SET member_lastname = :lastname,
                                            member_firstname = :firstname,
                                            member_email = :email,
                                            member_zip_code = :zip_code
                                        WHERE member_id = :id AND member_token = :token;");
            $query->execute([
                "lastname" => $_POST["NOM"],
                "firstname" => $_POST["PRENOM"],
                "email" => $_POST["EMAIL"],
                "zip_code" => $_POST["CODE"],
                "id" => $_SESSION["id"],
                "token" => $_SESSION["token"],
            ]);
            header("Location: ../userSettings.php");
        }
    }else {
        die("Tentative de hack");
    }
