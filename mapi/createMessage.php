<?php

require_once('./messagesDatabaseAccessor.php');

$mdba = new MessagesDatabaseAccessor();

$msg = '';

if(isset($_POST['message'])){
    $msg = $_POST['message'];
}

if($msg == '' || strlen($msg) < 10){
    throw new Exception('NO NONSENSE!!!!!');
}
else {
    $wdba->insertMessage($msg);
}


?>