<?php
require_once './base.php';

//echo var_dump($_POST);
//array(8) { ["course"]=> string(2) "10" ["assignment_name"]=> string(3) "abc" ["amount_required"]=> string(1) "1" ["year"]=> string(4) "2017" ["month"]=> string(1) "1" ["day"]=> string(1) "1" ["time_select"]=> string(1) "1" ["am_pm_select"]=> string(2) "pm" }

$success = null;

if(!($loggedIn && ($isProf || $isTA) && isset($_POST['course']) && isset($_POST['assignment_name']) && isset($_POST['amount_required']) && isset($_POST['year']) && isset($_POST['month']) && isset($_POST['day']) && isset($_POST['time_select']) && isset($_POST['am_pm_select']))){
    header("Location: ./index.php?s=1");
    exit;
}

if(!($_POST['course'] != '' && $_POST['assignment_name'] != '' && $_POST['amount_required'] != '' && $_POST['year'] != '' && $_POST['month'] != '' && $_POST['day'] != '' && $_POST['time_select'] != '' && $_POST['am_pm_select'] != '')){
    header("Location: ./index.php?s=2");
    exit;
}

if($_POST['am_pm_select'] != 'pm' && $_POST['am_pm_select'] != 'am'){
    header("Location: ./index.php?s=3");
    exit;
}

if($_POST['year'] != date('Y', strtotime('+1 year')) && $_POST['year'] != date('Y')){
    header("Location: ./index.php?s=4");
    exit;
}

if($_POST['month'] > 12 || $_POST['month'] < 1){
    header("Location: ./index.php?s=5");
    exit;
}

if($_POST['day'] < 32 || $_POST['day'] > 0){
    $days = 0;
    switch($_POST['month']) {
        case '1':
            $days = 31;
            break;
        case '2':
            $days = 28;
            break;
        case '3':
            $days = 31;
            break;
        case '4':
            $days = 30;
            break;
        case '5':
            $days = 31;
            break;
        case '6':
            $days = 30;
            break;
        case '7':
            $days = 31;
            break;
        case '8':
            $days = 31;
            break;
        case '9':
            $days = 30;
            break;
        case '10':
            $days = 31;
            break;
        case '11':
            $days = 30;
            break;
        case '12':
            $days = 31;
            break;
    }
    
    if($_POST['day'] > $days){
        header("Location: ./index.php?s=6");
        exit;
    }
}
else{
    header("Location: ./index.php?s=7");
    exit;
}

if($_POST['time_select'] > 12 || $_POST['time_select'] < 1){
    header("Location: ./index.php?s=8");
    exit;
}

$hour = $_POST['time_select'];

if($_POST['am_pm_select'] == 'pm'){
    $hour = 12 + $_POST['time_select'];
}

$dt = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' ' . $hour . ':00:00';


if($dba->createAssignment($id, $_POST['course'], $_POST['assignment_name'], $dt, $_POST['amount_required'])){
    header("Location: ./createAssignment.php?s=1");
    exit;
}
else{
    header("Location: ./createAssignment.php?s=0");
    exit;
}

?>