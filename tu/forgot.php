<?php
require_once './base.php';

if($loggedIn){
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
echo $htmlGen->createHeader("Forgot Password");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    <form class="login_form" action="./forgot-post.php" method="post">
        <div class="login-container">
            <label>Email</label>
            <input type="email" placeholder="Enter Email" name="email" required>

            <button class="login_button" type="submit" name="login_button" value="reset">Send Password Reset Email</button>
            <a href="./login.php">Back to Login</a>
        </div>
    </form>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>