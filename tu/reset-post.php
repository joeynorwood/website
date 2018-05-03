<?php
session_start();
require_once('./db.php');
$dba = new DatabaseAccessor();

//if you're registering you should not be logged in
unset($_SESSION['email']);
unset($_SESSION['id']);
unset($_SESSION['isProf']);

//check that all fields submitted
if(isset($_POST['pw']) and isset($_POST['repw']) and isset($_REQUEST['e'])){
    
    if($_POST['pw'] != $_POST['repw']){
        header("Location: ./msg.php?m=7");
        exit;
    }
    
    if(strlen($_POST['pw']) < 6 || !preg_match('/[A-Za-z]/', $_POST['pw']) || !preg_match('/[0-9]/', $_POST['pw'])){
        header("Location: ./msg.php?m=8");
        exit;
    }
    
    if(is_null($dba->isUser($_REQUEST['e']))){
        header("Location: ./index.php");
        exit;
    }
    
    $newSalt = $dba->createSalt();
    $newPass = $dba->createHashedPassword($_POST['pw'], $newSalt);
    
    $dba->updatePassword($_REQUEST['e'], $newPass, $newSalt);
}
else{
    header("Location: ./msg.php?m=1");
    exit;
}

header("Location: ./msg.php?m=25");
exit;

/*
//make sure that both submitted passwords are the same
if($_POST['pw'] == $_POST['repw']){
    //check that email is correct form
    if(true){
        //send mail
    }
    else{
        //not msu email message
        header("Location: ./msg.php?m=3");
        exit;
    }
}
else {
    header("Location: ./msg.php?m=2");
    exit;
}
*/
?>