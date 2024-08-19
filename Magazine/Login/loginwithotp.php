<?php
    session_start();
    if(isset($_SESSION['login']))
        header("Location: ../index.html");
?>

<html>

<head>
    <title>Login Page</title>
    <link id='favicon' rel="shortcut icon" href="../../Image/favicon.png" type="image/x-png">
    <link rel="stylesheet" href="logincss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body oncontextmenu="return false">
    <div class="bigcontainer">
        <div class="container">
            <div>
                <p class="title"> Login to your account! </p>
                <p style="color:rgb(85, 72, 72);"> <b> Welcome back!! You would have to login with your email to access
                        the Magazines </b> </p>
            </div>
            <form class="getEmail" action="send_otp.php" method="POST">
                <label for="email" class="emaillabel"> Enter your email id </label> <br> <br>
                <input type="text" placeholder="Your answer" name="email" id="email" onKeyUp="validateEmail()" required>
                <button id="requestbtn" class="requestbtn" type="submit" disabled> Request for OTP </button>
            </form>
        </div>
    </div>
    <script>
        function validateEmail() {
            var emailbox = document.getElementById("email");
            var emailid = emailbox.value;
            var reqbtn = document.getElementById("requestbtn");
            const regex = new RegExp('.@iiitt.ac.in$');
            if (regex.test(emailid)) {
                emailbox.style.color = "black";
                emailbox.style.borderBottomColor = "black";
                reqbtn.disabled = false;
            }
            else {
                emailbox.style.color = "red";
                emailbox.style.borderBottomColor = "red";
                reqbtn.disabled = true;
            }
        }
    </script>
</body>