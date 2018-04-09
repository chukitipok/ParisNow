<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

//echo "<pre>";
//print_r( $_SESSION );
//echo "</pre>";
//die();


if (isset($_POST["deleteAccount"])){
    echo "toto";
    $db = connectDB();
    $query = $db->prepare("DELETE FROM member WHERE member_id = :id AND member_token = :token;");
    $query->execute([
       "id"=>$_SESSION["id"]
    ]);
}