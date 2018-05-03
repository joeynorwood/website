<?php
require_once('./base.php');

if(!$loggedIn || !$isProf){
    header("Location: ./index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>

<div class="container">

    <?php
    echo $htmlGen->createHeader("Add TAs");
    echo $htmlGen->createNavigation($isProf, $isTA);
    ?>

    <div class='mainWindow' id='mainWindow'>
        <?php
        echo $htmlGen->createTAForm($dba->getAddTAData($id));
        ?>
        
    </div>
    
    <?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>