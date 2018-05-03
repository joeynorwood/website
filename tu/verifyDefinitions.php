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
echo $htmlGen->createHeader("Verify Definitions");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    
<?php
//echo $htmlGen->createProfessorCourseSelector($dba->getOwnedCourses($id));
echo "<p class='page_top_msg'>Unverified definitions will be removed from this list after 10 days.</p>";
echo $htmlGen->createDefinitionsToVerify($dba->getDefinitionsToVerify($id));
?>
    
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>