<?php

session_start();

unset($_SESSION['email']);
unset($_SESSION['id']);
unset($_SESSION['isProf']);
unset($_SESSION['isTA']);
unset($_SESSION['firstName']);
unset($_SESSION['lastName']);

header('Location: ./index.php');
exit;

?>