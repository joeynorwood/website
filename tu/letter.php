<?php
require_once('./base.php');

    if(isset($_REQUEST['l']) && (strlen($_REQUEST['l']) == 1) && (strpos('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', $_REQUEST['l']) !== false)){
        $l = $_REQUEST['l'];
    }
    else{
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
echo $htmlGen->createHeader($l);
echo $htmlGen->createNavigation($isProf, $isTA);
?>
    
<div class='mainWindow' id='mainWindow'>
    <?php
        $result = $dba->getDefsForLetter($l);
        echo $htmlGen->createDefs($result, $isProf);
    ?>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>