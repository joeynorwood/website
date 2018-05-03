<?php
require_once './base.php';

$success = null;

if($loggedIn && isset($_POST['term']) && isset($_POST['assignment']) && isset($_POST['definition'])){
    
    if($_POST['term'] != '' && $_POST['assignment'] != '' && $_POST['definition'] != ''){
        
        $res = $dba->defineTerm($_POST['term'], $_POST['definition'], $_POST['assignment'], $id);
        
        if(!is_null($res) && $res){
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
    header("Location: ./define.php?s=" . $success);
    exit;
}

?>