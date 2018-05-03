<?php
require_once './base.php';

$success = null;

if($loggedIn && $isProf && count($_POST) > 1 && count($_POST)%3 == 0){
    
    foreach($_POST as $name => $ta){
        $courseID = strtok($name, '_');
        
        $email = '';
        $eflag = false;
        for($i=0; $i<strlen($ta); $i++){
            if($ta[$i] == ')'){
                $eflag = false;
            }
            if($eflag){
                $email = $email . $ta[$i];
            }
            if($ta[$i] == '('){
                $eflag = true;
            }
        }
        
        $num = substr($name, strpos($name, '_') + 3, 1);
        
        $courses = $dba->getOwnedCourses($id);
        
        $conf = false;
        foreach($courses as $c){
            if($c['course_id'] == $courseID){
                $conf = true;
                break;
            }
        }
        
        if($conf){
            $taID = $dba->getIDforEmail($email);
            
            $dba->updateTA($courseID, $taID, $num);
        }
    }
}

header("Location: ./addTAs.php");
 
/*
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
    header("Location: ./addTAs.php");
    exit;
}
*/
?>