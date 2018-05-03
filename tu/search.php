<?php
require_once('./base.php');

if(!isset($_POST['s']) || !isset($_POST['c'])){
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
echo $htmlGen->createHeader('Search');
echo $htmlGen->createNavigation($isProf, $isTA);
?>
    
<div class='mainWindow' id='mainWindow'>
    <?php
        $result = $dba->search($_POST['s'], $_POST['c'], $id);
        echo $htmlGen->createDefs($result, $isProf, $isTA);
    ?>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>