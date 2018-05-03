<?php
require_once './base.php';

if($loggedIn && ($isProf || $isTA) && isset($_REQUEST['h'])){
    
    $dba->hideDefinition($id, $_REQUEST['h']);
    
    header("Location: ./hiddenDefinitions.php");
    exit;
}
else{
    header("Location: ./index.php");
    exit;
}

?>