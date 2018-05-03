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
echo $htmlGen->createHeader("Register");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    <form class="login_form" action="/register-post.php" method="post">
        <div class="login-container">
            <label>Email</label>
            <input type="email" placeholder="Enter Email" name="email" required>
            <label>First Name</label>
            <input type="text" placeholder="Enter First Name" name="firstname" required>
            <label>Last Name</label>
            <input type="text" placeholder="Enter Last Name" name="lastname" required>
            <label>Password (at least 6 characters containing at least 1 number)</label>
            <input type="password" placeholder="Enter Password" name="pw" required>
            <label>Re-Enter Password</label>
            <input type="password" placeholder="Enter Password" name="repw" required>

            <button class="login_button" type="submit" name="login_button" value="create">Create Account</button>
            <a href="./login.php">Back to Login</a>
        </div>
    </form>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>