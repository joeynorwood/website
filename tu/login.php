<?php
require_once('./base.php');

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
echo $htmlGen->createHeader("Login");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    <?php
        if(isset($_REQUEST['v']) && $_REQUEST['v'] == '1'){
            echo "<p class='page_top_msg'>Verification Successful, Please Log In</p>";
        }
    ?>
    <form class="login_form" action="./login-post.php" method="post">
        <div class="login-container">
            <label>Email</label>
            <input type="email" placeholder="Enter Email" name="email" required>
            <label>Password</label>
            <input type="password" placeholder="Enter Password" name="pw">

            <button class="login_button" type="submit" name="login_button" value="login">Login</button>
            <span><input type="checkbox" checked="checked"> Remember me</span>
            <a href="./register.php">Register</a>
            <a href="./forgot.php">Forgot Password?</a>
        </div>
    </form>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>