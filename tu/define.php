<?php
require_once('./base.php');

if(!$loggedIn){
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
    echo $htmlGen->createHeader("Define");
    echo $htmlGen->createNavigation($isProf, $isTA);
    ?>

    <div class='mainWindow' id='mainWindow'>
        <?php

        if(isset($_REQUEST['s'])){
            if($_REQUEST['s'] == '1'){
                echo "<p class='define-msg'>Definition submitted successfully.</p>";
            }
            elseif($_REQUEST['s'] == '-1'){
                echo "<p class='define-msg'>Submission failed: Fields were left blank.</p>";
            }
            elseif($_REQUEST['s'] == '0'){
                echo "<p class='define-msg'>Submission failed: An error occurred.</p>";
            }
        }

        echo $htmlGen->createDefineForm($dba->getCourseAssignments($id));

        ?>
    </div>
    
    <?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>