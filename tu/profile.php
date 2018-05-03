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
echo $htmlGen->createHeader("My Profile");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    
<?php
if(isset($_REQUEST['c'])){
    echo $htmlGen->createAssignmentsForCourse($dba->getAssignmentInfoForCourse($id, $_REQUEST['c']));
}
else{
    if($isProf){
        echo "<p class='page_top_msg'>Clicking 'Close Course' will remove that course from all reports, but definitions will remain searchable.</p>";
    }
    echo $htmlGen->createMyCourses($dba->getCoursesForUserID($id));
}
?>
    
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>