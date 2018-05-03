<?php
require_once('./base.php');
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>

<div class="container">

<?php
echo $htmlGen->createHeader("Terms Unknown");
echo $htmlGen->createNavigation($isProf, $isTA);
?>

<div class='mainWindow' id='mainWindow'>
    <div id='terms_pic_div'>
        <img id='terms_pic' src='./terms.jpg' alt='image of scientific terms'>
    </div>
</div>
    
<?php echo $htmlGen->createFooter(); ?>

</div>

</body>
</html>