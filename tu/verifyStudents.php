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
echo $htmlGen->createHeader("Verify Students");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    
<?php
//echo $htmlGen->createProfessorCourseSelector($dba->getOwnedCourses($id));
echo $htmlGen->createStudentsToVerify($dba->getStudentsToVerify($id));
?>
    
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>