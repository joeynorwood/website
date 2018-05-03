<?php
require_once('./base.php');

if(!$loggedIn || !($isProf || $isTA)){
    header("Location: ./index.php");
    exit;
}

$course_id = null;
$assignment_id = null;
$student_id = null;

if(isset($_REQUEST['c'])){
    $myCourses = $dba->getOwnedCourses($id);

    foreach($myCourses as $cs){
        if($cs['course_id'] == $_REQUEST['c']){
            $course_id = $_REQUEST['c'];
            break;
        }
    }
    if(is_null($course_id)){
        //they tried to use URL parameters for a course that is not theirs
        header('Location: ./index.php');
        exit;
    }
}   

if(isset($_REQUEST['a'])){
    $myAssignments = $dba->getOwnedAssignments($id);

    foreach($myAssignments as $as){
        if($as['assignment_id'] == $_REQUEST['a']){
            $assignment_id = $_REQUEST['a'];
            break;
        }
    }
    if(is_null($assignment_id)){
        //they tried to use URL parameters for a course that is not theirs
        header('Location: ./index.php');
        exit;
    }
}

if(isset($_REQUEST['s'])){
    $myStudents = $dba->getOwnedStudents($id);

    foreach($myStudents as $st){
        if($st['user_id'] == $_REQUEST['s']){
            $student_id = $_REQUEST['s'];
            break;
        }
    }
    if(is_null($student_id)){
        //they tried to use URL parameters for a course that is not theirs
        header('Location: ./index.php');
        exit;
    }
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
echo $htmlGen->createHeader("Report");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
<?php 
    echo $htmlGen->createReport($dba->getReportData($id, $course_id, $assignment_id, $student_id)); 
?>
    
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>