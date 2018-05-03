<?php

session_start();
require_once './db.php';

$dba = new DatabaseAccessor();

//if we got to this page without posting the form, redirect back to login
if(!isset($_POST['email']) or !isset($_POST['pw'])){
    header('Location: ./login.php');
    exit;
}

//check if user exists and if so, get salt, otherwise get null
$salt = $dba->isUser($_POST['email']);

//does user exist?
if(!is_null($salt)){
    //create hashed password
    $hash = $dba->createHashedPassword($_POST['pw'], $salt);
    
    //try logging in
    $userID = $dba->login($_POST['email'], $hash);
    
    //check if login good
    if(!is_null($userID)){
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['id'] = $userID;
        $_SESSION['isProf'] = $dba->isProf($userID);
        $_SESSION['isTA'] = $dba->isTA($userID);
        $_SESSION['firstName'] = $dba->getFirstName($userID);
        $_SESSION['lastName'] = $dba->getLastName($userID);

    }
    else{
        //failed login means you're not logged in
        unset($_SESSION['email']);
        unset($_SESSION['id']);
        unset($_SESSION['isProf']);
        unset($_SESSION['isTA']);
        unset($_SESSION['firstName']);
        unset($_SESSION['lastName']);

    }
}
else{
    //failed login means you're not logged in
    unset($_SESSION['email']);
    unset($_SESSION['id']);
    unset($_SESSION['isProf']);
    unset($_SESSION['isTA']);
    unset($_SESSION['firstName']);
    unset($_SESSION['lastName']);

}

header('Location: ./index.php');
exit;

?>

