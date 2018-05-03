<?php
require_once './base.php';

if($loggedIn && ($isProf || $isTA)){
    foreach($_POST as $key => $value){
        if(is_numeric($key) && floor($key) == $key){
            $dba->verifyStudent($id, $key);
        }
    }
    
    header("Location: ./verifyStudents.php");
    exit;
}
else{
    header("Location: ./index.php");
    exit;
}

?>