<?php
require_once('./db.php');
require_once('./htmlGenerator.php');

session_start();

$loggedIn = false;
$isProf = false;
$isTA = false;
$email = null;
$id = null;
$courses = null;

$dba = new DatabaseAccessor();

if(isset($_SESSION['email']) and isset($_SESSION['id']) and isset($_SESSION['isProf']) and isset($_SESSION['isTA']) and isset($_SESSION['firstName']) and isset($_SESSION['lastName'])){
    $loggedIn = true;
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $isProf = $_SESSION['isProf'];
    $isTA = $_SESSION['isTA'];
    $first = $_SESSION['firstName'];
    $last = $_SESSION['lastName'];
    $courses = $dba->getCoursesForUserID($id);
}

$htmlGen = new htmlGenerator($loggedIn, $id, $email, $courses, $first, $last); 
    
?>