<?php
session_start();
require_once('./db.php');
$dba = new DatabaseAccessor();

//if you're registering you should not be logged in
unset($_SESSION['email']);
unset($_SESSION['id']);
unset($_SESSION['isProf']);

//check that all fields submitted
if(isset($_POST['email'])){
    
    if(is_null($dba->isUser($_POST['email']))){
        header("Location: ./msg.php?m=21");
        exit;
    }
    
    if(!$dba->isOkToEmailPasswordReset($_POST['email'])){
        header("Location: ./msg.php?m=22");
        exit;
    }
    
    $emailCode = $dba->createVerificationCode();
    
    $hash = $dba->createHashedPassword($emailCode, '');
    
    $dba->refreshPasswordReset($_POST['email'], $hash);
    
    $to = $_POST['email'];
    $subject = "Terms Unknown: Reset Password Request";
    $message = "
        <html>
        <body>
            <p>A request to reset your password has been made</p>
            <p>Click the link to complete the password reset: 
                <a href='http://www.termsunknown.com/reset.php?v=" . $emailCode . "&e=" . $to . "'>Link</a>
            </p>
            <p>If you did not make this request, ignore this email.</p>
        </body>
        </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers = $headers . "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers = $headers . "From: <noreply@termsunknown.com>" . "\r\n";

    mail($to, $subject, $message, $headers);
}
else{
    header("Location: ./msg.php?m=1");
    exit;
}

header("Location: ./msg.php?m=24");
exit;

/*
//make sure that both submitted passwords are the same
if($_POST['pw'] == $_POST['repw']){
    //check that email is correct form
    if(true){
        //send mail
    }
    else{
        //not msu email message
        header("Location: ./msg.php?m=3");
        exit;
    }
}
else {
    header("Location: ./msg.php?m=2");
    exit;
}
*/
?>