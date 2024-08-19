<?php
    session_start();
    if(!isset($_SESSION['login']))
        header("Location: Login/loginwithotp.php");
    if(time()-$_SESSION['login_time_stamp'] > 1800)  
    {
        session_unset();
        header("Location: Login/loginwithotp.php");
    }
    $get_issue = $_GET['issue']; 
    $pdfFilePath = 'PDF/Aikyam - '.$get_issue.'.pdf';
    if (file_exists($pdfFilePath)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Aikyam - '.$get_issue.'.pdf"');
        header('Content-Length: ' . filesize($pdfFilePath));
        readfile($pdfFilePath);
        exit;
    } else {
        echo "PDF file not found.";
    }
?>