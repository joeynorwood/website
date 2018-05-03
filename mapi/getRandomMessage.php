<?php
require_once('./messagesDatabaseAccessor.php');

$mdba = new MessagesDatabaseAccessor();

$messageArray = $mdba->selectRandomMessage();

$messageJSON = json_encode($messageArray, JSON_FORCE_OBJECT);

echo $messageJSON;
?>