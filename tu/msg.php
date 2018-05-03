<?php
require_once('./base.php');

if(!isset($_REQUEST['m'])){
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
echo $htmlGen->createHeader("Message");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    <?php 
    switch($_REQUEST[m]) {
        case '1':
            echo "<h2>A field was left blank</h2>";
            break;
        case '2':
            echo "<h2></h2>";
            break;
        case '3':
            echo "<h2>email is not msu.edu</h2>";
            break;
        case '4':
            echo "<h2>email is already registered</h2>";
            break;
        case '5':
            echo "<h2>This email address has already been registered in the past 24 hours. Check your spam folder for the verification email.</h2>";
            break;
        case '6':
            echo "<h2>Check your email for the verification link.</h2>";
            break;
        case '7':
            echo "<h2>Passwords entered did not match.</h2>";
            break;
        case '8':
            echo "<h2>Password did not meet security requirements.</h2>";
            break;
        case '21':
            echo "<h2>Email is not registered</h2>";
            break;
        case '22':
            echo "<h2>You've tried to reset this password recently, check your spam folder for the email</h2>";
            break;
        case '24':
            echo "<h2>Email sent, check spam if you can't find it</h2>";
            break;
        case '25':
            echo "<h2>Password reset successful, proceed to login</h2>";
            break;
        default:
            echo "<h2>Unknown Message</h2>";
            break;
    }
    ?>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>