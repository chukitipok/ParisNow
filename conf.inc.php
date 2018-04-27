<?php

$listOfGender = [ 1=>"Monsieur", 2=>"Madame", 3=>"Autre"];
$defaultGender = 1;

define("DBNAME","parisnow");
define("CHARSET","utf8");
define("DBUSER","root");
define("DBPWD","");
define("DBHOST","localhost");
define("DBDRIVER","mysql");

$listOfErrors = [
    1=>"Le genre n'est pas correct",
    2=>"Le prénom doit faire plus de 2 caractères",
    3=>"Le nom doit faire plus de 2 caractères",
    4=>"L'email n'est pas valide",
    5=>"Le format de la date d'anniversaire n'est pas correct",
    6=>"La date d'anniversaire n'existe pas",
    7=>"Vous devez avoir entre 18 et 100 ans",
    8=>"Le pays n'est pas correct",
    9=>"Le mot de passe doit faire entre 8 et 20 caractères",
    10=>"Le mot de passe de confirmation ne correspond pas",
    11=>"L'email existe déjà",
    12=>"Tous les champs doivent être remplis"
];

$listOfErrorsInfo = [
    1=>"Le nom doit faire plus de 2 caractères",
    2=>"Le prénom doit faire plus de 2 caractères",
    3=>"L'email n'est pas valide",
    4=>"L'adresse n'est pas valide",
    5=>"Le code postal n'est pas valide",
    6=>"L'email existe déjà",
    7=>"Tous les champs doivent être remplis",
    8=>"Le nom est invalide",
    9=>"Le prénom est invalide"
];

$listOfErrorsPwd = [
    1=>"Le mot de passe ne correspond pas au mot de passe actuel",
    2=>"Le mot de passe doit faire entre 8 et 20 caractères",
    3=>"Le mot de passe de confirmation ne correspond pas",
    4=>"Tous les champs doivent être remplis"
];

$categoryOfContact = [
    1=>"Idée",
    2=>"Problème",
    3=>"Commercial"
];

$listOfTicketError = [
    1=>"La catégorie n'est pas valide.",
    2=>"Le titre doit faire entre 4 et 60 caractères.",
    3=>"Le contenu du ticket doit faire entre 10 et 1 000 caractères.",
];

$ticketErrorBackOffice =[
    1=>"Ce ticket est fermé.", 
    2=>"Ce ticket est définitivement fermé.",
    3=>"Ce ticket est déjà ouvert.",
    4=>"Le message est vide.",
    5=>"Ce ticket est déjà fermé.",
    6=>"Ce ticket est définitivement fermé, aucune action n'est possible dessus.",
    7=>"Ce ticket est déjà en traitement"
];