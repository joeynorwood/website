<?php
require_once './base.php';

$success = null;

if($loggedIn && $isProf && isset($_POST['course_name']) && isset($_POST['course_description'])){
    
    if($_POST['course_name'] != '' && $_POST['course_description'] != ''){
        
        if($dba->createCourse($_POST['course_name'], $_POST['course_description'], $id)){
            $success = '1';
        }else{
            $success = '0';
        }
    }
    else{
        $success = '-1';
    }
}
if(is_null($success)){
    header("Location: ./index.php");
    exit;
}
else{
    header("Location: ./createCourse.php?s=" . $success);
    exit;
}

?>