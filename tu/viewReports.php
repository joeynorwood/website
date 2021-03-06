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
echo $htmlGen->createHeader("Reports");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    <?php
        echo $htmlGen->createReportCourseList($dba->getOwnedCourses($id));
        
    ?>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>