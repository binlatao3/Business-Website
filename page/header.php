<?php

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);

    
    if(isset($_SESSION['username']))
    {
        $user = user($_SESSION['username']);
        $nameuser = '';
        if($_SESSION['username'] === 'default')
        {
            $nameuser = 'Default';
        }
        else
        {
            $nameuser = $user['fullname'];
        }
    }
    if($name === 'index.php')
    {
        $logout = "./page/logout.php";
        $imgurl = './images/';
        $profile = "./page/profile.php";
    }
    else
    {
        $logout = "logout.php";
        $imgurl = '../images/';
        $profile = "./profile.php";
    }
    $username = $_SESSION['username'];
?>

<div class="header-sidebar">
    <span>Members</span>
    <svg class="show-sidebar-ipad" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px" viewBox="0 0 24 24" version="1.1">
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <rect x="0" y="0" width="24" height="24"/>
            <path class="icon-showsidebar-vector-first" d="M2 11.5C2 12.3284 2.67157 13 3.5 13H20.5C21.3284 13 22 12.3284 22 11.5V11.5C22 10.6716 21.3284 10 20.5 10H3.5C2.67157 10 2 10.6716 2 11.5V11.5Z" fill="black"/>
            <path class="icon-showsidebar-vector-last" opacity="1" fill-rule="evenodd" clip-rule="evenodd" d="M9.5 20C8.67157 20 8 19.3284 8 18.5C8 17.6716 8.67157 17 9.5 17H20.5C21.3284 17 22 17.6716 22 18.5C22 19.3284 21.3284 20 20.5 20H9.5ZM15.5 6C14.6716 6 14 5.32843 14 4.5C14 3.67157 14.6716 3 15.5 3H20.5C21.3284 3 22 3.67157 22 4.5C22 5.32843 21.3284 6 20.5 6H15.5Z" fill="black"/>
        </g>
    </svg>
</div>
<div id="header-main" class="js-header header">
                <div id="header-menu-main" class="js-header-menu header-menu">
                    <div class="menu-search js-hide-header" >
                        <span></span>
                    </div>
                    <ul class="menu-itmes js-menu-itmes">
                        <li class="items-icon items-icon-hover js-items-icon dis-flex-avartar-header" >
                            <span class="icon-name">
                                <?php 
                                    if($username != 'default')
                                    {
                                        if(user($username)['img'])
                                            $img = '../images/'.user($username)['img'];
                                        else
                                            $img = "../images/default.png";
                                    }
                                    else
                                        $img = "../images/default.png";
                                ?>
                                <img src="<?= $imgurl.$img?>"alt="">
                                <span>
                                    <?=$nameuser?>
                                    <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="20" height="20">
                                        <path class="icon-arrow-vector-only" style="font-size: 12px;" d="M18.71,8.21a1,1,0,0,0-1.42,0l-4.58,4.58a1,1,0,0,1-1.42,0L6.71,8.21a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l4.59,4.59a3,3,0,0,0,4.24,0l4.59-4.59A1,1,0,0,0,18.71,8.21Z"/>
                                    </svg>
                                </span>
                            </span>
                            <ul class="icon-setting-pic js-icon-setting-show bd-rd-rem">
                                <li>
                                    <a href="<?=$profile?>">
                                        <i class="far fa-user-circle setting-icon"></i>                                    
                                        <span>Profile setting</span>
                                    </a>
                                </li>
                                <li >
                                    <a href= "<?= $logout ?>">
                                        <i class="fas fa-sign-out-alt"></i>                               
                                        <span>Log out</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
