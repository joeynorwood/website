<?php
require_once('./base.php');

if($dba->verify($_REQUEST['e'], $_REQUEST['v'])){
    header("Location: ./login.php?v=1");
    exit;
}

header("Location: ./index.php");
exit;

?>