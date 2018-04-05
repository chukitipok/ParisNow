<?php
	
    session_start();
	require "../conf.inc.php";
	require "../functions.php";
	
	//Vérifier que le formulaire soit complet
	if( count($_POST)  == 10
		&& isset($_POST["gender"])
		&& !empty($_POST["firstname"])
		&& !empty($_POST["lastname"])
		&& !empty($_POST["email"])
		&& !empty($_POST["birthday"])
		&& !empty($_POST["pwd"])
		&& !empty($_POST["pwdConfirm"])
		&& !empty($_POST["address"])
		&& !empty($_POST["zipcode"])
		&& !empty($_POST["cgu"])
	){
	
		$error = false;
		$listOfErrors = [];

		//Nettoyer les valeurs
		$_POST["firstname"] = ucfirst(trim(mb_strtolower($_POST["firstname"])));
		$_POST["lastname"] = trim(strtoupper($_POST["lastname"]));
		$_POST["email"] = trim(mb_strtolower($_POST["email"]));
		$_POST["birthday"] = trim($_POST["birthday"]);
		$_POST["address"] = trim(mb_strtolower($_POST["address"]));
		
		//vérifier les valeurs une par une
		//gender : soit 0, soit 1, soit 2
		if( !array_key_exists ( $_POST["gender"] , $listOfGender )  ){
			$error = true;
			$listeOfErrors[] = 1;
		}
		//firstname : min 2 max 32
		if( strlen($_POST["firstname"])<2 || strlen($_POST["firstname"])>32 ){
			$error = true;
            $listeOfErrors[] = 2;
		}
		//lastname : min 2 max 50
		if( strlen($_POST["lastname"])<2 || strlen($_POST["lastname"])>50 ){
			$error = true;
            $listeOfErrors[] = 3;
		}
		//email : format valide
		if( !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)   ){
			$error = true;
            $listeOfErrors[] = 4;
		}else{//verifie que l'email n'existe pas déja
            $connection = connectDB();
            $query = $connection->prepare("SELECT 1 FROM member WHERE member_email = :email");
            $query->execute(["email" => $_POST["email"]]);
            $result = $query->fetch();

            if(!empty($result)){
                $error = true;
                $listeOfErrors[] = 11;
            }
        }

		$dateFormat = false;
		if(strpos($_POST["birthday"], "/")){
			list($day,$month,$year) = explode("/", $_POST["birthday"]);
			$dateFormat = true;
		}else if(strpos($_POST["birthday"], "-")){
			list($year,$month,$day) = explode("-", $_POST["birthday"]);
			$dateFormat = true;
		}else{
			$error = true;
            $listeOfErrors[] = 5;
		}
		
		if($dateFormat){
			if( !is_numeric($month)  
				|| !is_numeric($day) 
		 		|| !is_numeric($year) 
		 		|| !checkdate($month, $day, $year) 
				){
				$error = true;
                $listeOfErrors[] = 6;
			}
			else{
				$today = time();
				$time18years = $today - 18*3600*24*365;
				$time100years =  $today - 100*3600*24*365;
				
				$birthday = mktime(0,0,0,$month, $day, $year);

				if( $time100years > $birthday ||  $time18years < $birthday){
					$error = true;
                    $listeOfErrors[] = 7;
				}
			}
		}

		//pwd : min 8 max 20
		if( strlen($_POST["pwd"])<8 || strlen($_POST["pwd"])>20 ){
			$error = true;
            $listeOfErrors[] = 9;
		}
		//pwdConfirm = pwd
		if( $_POST["pwd"] != $_POST["pwdConfirm"] ){
			$error = true;
            $listeOfErrors[] = 10;
		}

//		if(strtolower($_POST["captcha"]) != $_SESSION["captcha"]){
//            $error = true;
//            $listeOfErrors[] = 12;
//        }

		if($error){
			$_SESSION["errorForm"] = $listeOfErrors;
			$_SESSION["postForm"] = $_POST;
			header("Location: ../signup.php");

		}else{
			$query = $connection->prepare(
			    "INSERT INTO member ( 
                              gender, member_lastname,member_firstname,member_address,
                              member_zip_code, member_birthday, member_email,member_password,
                              member_status, account_creation) 
                          VALUES (:gender, :lastname, :firstname, :address, :zipcode,
                                  :birthday, :email, :password, :status, NOW()); ");
			$pwd = $_POST["pwd"];
			$query->execute( [
								"gender"=>$_POST["gender"],
								"lastname"=>$_POST["lastname"],
								"firstname"=>$_POST["firstname"],
								"address"=>$_POST["address"],
								"zipcode"=>$_POST["zipcode"],
								"birthday"=> $year."-".$month."-".$day,
                                "email"=>$_POST["email"],
								"password"=>password_hash($pwd, PASSWORD_DEFAULT),
                                "status"=>0,
							] );
            header("Location: ../index.php");
		}
	}else{
		die("Tentative de hack");
	}
