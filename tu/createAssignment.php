<?php
require_once('./base.php');

if(!$loggedIn || !($isProf || $isTA)){
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
    echo $htmlGen->createHeader("Create a New Assignment");
    echo $htmlGen->createNavigation($isProf, $isTA);
    ?>

    <div class='mainWindow' id='mainWindow'>
        <?php
        if(isset($_REQUEST['s'])){
            if($_REQUEST['s'] == '1'){
                echo "<p class='define-msg'>Assignment created successfully.</p>";
            }
            elseif($_REQUEST['s'] == '0'){
                echo "<p class='define-msg'>Assignment creation failed: An error occurred.</p>";
            }
        }
        echo $htmlGen->createAssignmentCreationForm();
        ?>
        
    </div>
    
    <?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>