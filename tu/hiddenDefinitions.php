<?php
require_once('./base.php');

if(!$loggedIn || !($isProf || $isTA)){
    header("Location: ./index.php");
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
echo $htmlGen->createHeader('Hidden Definitions');
echo $htmlGen->createNavigation($isProf, $isTA);
?>
    
<div class='mainWindow' id='mainWindow'>
    <?php
        $result = $dba->getHiddenDefinitions($id);
        echo $htmlGen->createHiddenDefs($result);
    ?>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>