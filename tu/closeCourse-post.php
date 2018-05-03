<?php
require_once './base.php';

if($loggedIn && $isProf && isset($_REQUEST['c'])){
    
    if($dba->canICloseCourse($id, $_REQUEST['c'])){
        $dba->closeCourse($_REQUEST['c']);
    }
}

header("Location: ./profile.php");
exit;


?>