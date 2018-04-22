<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

//echo "<pre>";
//print_r( $_SESSION );
//echo "</pre>";
//die();

    if (count($_POST) == 5) {
        //values not empty
        if (!empty($_POST["lastname"])
            && !empty($_POST["firstname"])
            && !empty($_POST["email"])
            && !empty($_POST["address"])
            && !empty($_POST["zipcode"]))
        {
            $errorInfo = false;
            $listOfErrorsInfo = [];
//            $regexAddress = "#^[1-9][0-9]?[0-9]?*[a-z]$#i";
//            $address = $_POST["address"];

            //clean values
            $_POST["firstname"] = ucfirst(trim(mb_strtolower($_POST["firstname"])));
            $_POST["lastname"] = trim(strtoupper($_POST["lastname"]));
            $_POST["email"] = trim(mb_strtolower($_POST["email"]));
            $_POST["address"] = trim(mb_strtoupper($_POST["address"]));

            if (!verif_alpha($_POST["lastname"])){
                $errorInfo = true;
                $listOfErrorsInfo[] = 8;
            }
            if (!verif_alpha($_POST["firstname"])){
                $errorInfo = true;
                $listOfErrorsInfo[] = 9;
            }
            //lastname : min 2 max 50
            if (strlen($_POST["lastname"]) < 2 || strlen($_POST["lastname"]) > 50 || is_numeric($_POST["lastname"])) {
                $errorInfo = true;
                $listOfErrorsInfo[] = 1;
            }
            //firstname : min 2 max 32
            if (strlen($_POST["firstname"]) < 2 || strlen($_POST["firstname"]) > 32 || is_numeric($_POST["firstname"])) {
                $errorInfo = true;
                $listOfErrorsInfo[] = 2;
            }
            //email : format valide
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $errorInfo = true;
                $listOfErrorsInfo[] = 3;
            } else {//verifie que l'email n'existe pas déja
                $query = $db->prepare("SELECT 1 FROM member WHERE email = :email");
                $query->execute(["email" => $_POST["email"]]);
                $result = $query->fetch();
                if (!empty($result)) {
                    $errorInfo = true;
                    $listOfErrorsInfo[] = 6;
                }
            }
//            if (preg_match($regexAddress, $address) != 1){
//                $errorInfo = true;
//                $listOfErrorsInfo["address"] = 4;
//
//            }
            if (!is_numeric($_POST["zipcode"]) || strlen($_POST["zipcode"]) != 5) {
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
                                                member_address = :address,
                                                member_zip_code = :zipcode
                                            WHERE member_id = :id AND member_token = :token;");
                $query->execute([
                    "lastname" => $_POST["lastname"],
                    "firstname" => $_POST["firstname"],
                    "email" => $_POST["email"],
                    "address" => $_POST["address"],
                    "zipcode" => $_POST["zipcode"],
                    "id" => $_SESSION["id"],
                    "token" => $_SESSION["token"],
                ]);
                header("Location: ../userSettings.php");
            }
        }else{
            $listeOfErrorsInfo[] = 7;
            $_SESSION["update"] = false;
            $_SESSION["errorFormInfo"] = $listeOfErrorsInfo;
            $_SESSION["postFormInfo"] = $_POST;
            header("Location: ../userSettings.php");
        }
    }else{
      die("Tentative de hack");
    }
