<?php

    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    require 'PHPMailer-master\src\PHPMailer.php';
    require 'PHPMailer-master\src\SMTP.php';

    $to_mail = $_POST['email'];
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['login'] = false;

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aikyam.production.team@gmail.com';
    $mail->Password = '****************';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('aikyam.production.team@gmail.com');
    $mail->addAddress($to_mail);
    $mail->Subject = 'Verification Code';

    $mail->isHTML(TRUE);
    $mail->Body = '<html>Please use the verification code below to sign in.<br><br><strong>' . $otp . '</strong><br><br>If you didn’t request this, you can ignore this email.<br>Don\'t share your OTP with anyone.<br><br>Thanks,<br>Aikyam Production Team</html>';


    if(!$mail->send())
    {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        header("Location: ../index.html");
    }
    else
    {
        echo 'Message has been sent';
        header("Location: otpverification.html");
    }
?>