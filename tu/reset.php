<?php
require_once './base.php';

if($loggedIn || !isset($_REQUEST['e']) || !isset($_REQUEST['v'])){
    header("Location: ./index.php");
    exit;
}

$hash = $dba->createHashedPassword($_REQUEST['v'], '');
    
if(!$dba->checkPassResetCode($hash, $_REQUEST['e'])){
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
echo $htmlGen->createHeader("Reset Password");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    <form class="login_form" action="./reset-post.php?e=<?php echo $_REQUEST['e']; ?>" method="post">
        <div class="login-container">
            <p>Email: <?php echo $_REQUEST['e']; ?></p>

            <label>Password (at least 6 characters containing at least 1 number)</label>
            <input type="password" placeholder="Enter Password" name="pw" required>
            
            <label>Re-Enter Password</label>
            <input type="password" placeholder="Enter Password" name="repw" required>

            <button class="login_button" type="submit" name="login_button" value="reset">Reset Password</button>
        </div>
    </form>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>