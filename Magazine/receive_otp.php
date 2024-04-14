<?php
    session_start();
    $generated_otp = $_SESSION['otp'];
    $received_otp = '';
    for ($i = 1; $i <= 6; $i++)
    {
        $digit = $_POST['input'.$i];
        $received_otp = $received_otp.$digit;
    }
    if ($generated_otp == $received_otp)
    {
        $_SESSION['login'] = true;
        $_SESSION['login_time_stamp'] = time();
        header("Location: index.html");
    }
    else
    {
        header("Location: otpverification.html");
    }
?>