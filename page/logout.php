<?php

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $name = basename($url);
    session_start();
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