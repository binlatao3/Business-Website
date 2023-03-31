<?php
    ob_start();
    require_once "./page/connectDB.php";

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $name = basename($url);

    active_account($_SESSION['username']);
    update_password($_SESSION['username'],$password);
    echo "
        <script type=\"text/javascript\">
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.modal-password').style.display = 'none'
        });
        </script>
    ";

    session_destroy();
    if($name === 'index.php')
    {
        header("Location: ./page/login.php");
        exit;
    }
    else
    {
        header("Location: login.php");
        exit;
    }
?>