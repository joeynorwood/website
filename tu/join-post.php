<?php
require_once './base.php';

if($loggedIn){
    foreach($_POST as $key => $value){
        if(is_numeric($key) && floor($key) == $key){
            $dba->joinCourse($id, $key);
        }
    }
    
    header("Location: ./profile.php");
    exit;
}
else{
    header("Location: ./index.php");
    exit;
}

?>