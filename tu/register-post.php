<?php
session_start();
require_once('./db.php');
$dba = new DatabaseAccessor();

//if you're registering you should not be logged in
unset($_SESSION['email']);
unset($_SESSION['id']);
unset($_SESSION['isProf']);
unset($_SESSION['firstname']);
unset($_SESSION['lastname']);

//check that all fields submitted
if(isset($_POST['pw']) and isset($_POST['repw']) and isset($_POST['email']) and isset($_POST['firstname']) and isset($_POST['lastname'])
  and strlen($_POST['firstname']) > 1 and strlen($_POST['lastname']) > 1){
    
    if($_POST['pw'] != $_POST['repw']){
        header("Location: ./msg.php?m=7");
        exit;
    }
    
    if(strlen($_POST['pw']) < 6 || !preg_match('/[A-Za-z]/', $_POST['pw']) || !preg_match('/[0-9]/', $_POST['pw'])){
        header("Location: ./msg.php?m=8");
        exit;
    }
    
    if(is_null($dba->isUser($_POST['email']))){
        $verification = $dba->createAccount($_POST['email'], $_POST['pw'], $_POST['firstname'], $_POST['lastname']);
        
        if(is_null($verification)){
            header("Location: ./msg.php?m=5");
            exit;
        }
        
        $to = $_POST['email'];
        $subject = "Account created with Terms Unknown";

        $message = "
        <html>
            <head>
                <title>Account created with Terms Unknown</title>
            </head>
        <body>
            <p>Click the link to complete registration: 
                <a href='http://www.termsunknown.com/verify.php?v=" . $verification . "&e=" . $to . "'>Link</a>
            </p>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers = $headers . "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers = $headers . "From: <noreply@termsunknown.com>" . "\r\n";

        mail($to, $subject, $message, $headers);
    }
    else{
        header("Location: ./msg.php?m=4");
        exit;
    }
}
else{
    header("Location: ./msg.php?m=1");
    exit;
}

header("Location: ./msg.php?m=6");
exit;

?>