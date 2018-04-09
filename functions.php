<?php

require_once "conf.inc.php";

function connectDB(){
		try{
			$connection = new PDO(DBDRIVER.":host=".DBHOST.";dbname=".DBNAME.";charset=".CHARSET,DBUSER,DBPWD);
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