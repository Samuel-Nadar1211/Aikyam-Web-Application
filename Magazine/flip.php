<?php
    session_start();
    if(!isset($_SESSION['login']))
        header("Location: loginwithotp.php");
    if(time()-$_SESSION['login_time_stamp'] > 1800)  
    {
        session_unset();
        header("Location: loginwithotp.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Book</title>
        <link rel="stylesheet" href="flip.css" />
        <script src="flip.js" defer="defer"></script>
        <link id='favicon' rel="shortcut icon" href="../Image/favicon.png" type="image/x-png">
    </head>
    <body onload="loadPage()">
        <button id="prev-btn">⇐</button>
        <div id="book">
        </div>
        <button id="next-btn">⇒</button>
    </body>
</html>