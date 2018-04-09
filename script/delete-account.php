<?php
session_start();
require "../conf.inc.php";
require "../functions.php";


$db = connectDB();
$query = $db->prepare("DELETE FROM member WHERE member_id = :id AND member_token = :token;");
$query->execute([
    "id" => $_SESSION["id"],
    "token"=> $_SESSION["token"]
]);

session_unset();
session_destroy();

header("Location: ../index.php");

?>