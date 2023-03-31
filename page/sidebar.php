<?php
    require_once "connectDB.php"; 

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $name = basename($url);
    $linklist = 'page/listuser.php';
    $linkadd = 'page/adduser.php';
    $linkindex = './index.php';
    $linklistpb = 'page/listphongban.php';
    $linkaddpb = 'page/addphongban.php';
    $linklisttasknv = 'page/tasknhanvien.php';
    $linklisttasktp = 'page/listtask.php';
    $linklistaddtask = 'page/addtask.php';
    $linklistsongaynghi = 'page/songaynghi.php';
    $linklistdanhsachnn = 'page/duyetngaynghi.php';
    $linklistlichsunop = 'page/listngaynghi.php';
    $user = user($_SESSION['username']);

    if($name !== "index.php")
    {
        $linklist = './listuser.php';
        $linkadd = './adduser.php';
        $linkindex = '../index.php';
        $linklistpb = './listphongban.php';
        $linkaddpb = './addphongban.php';
        $linklisttasknv = './tasknhanvien.php';
        $linklisttasktp = './listtask.php';
        $linklistaddtask = './addtask.php';
        $linklistsongaynghi = './songaynghi.php';
        $linklistdanhsachnn = './duyetngaynghi.php';
        $linklistlichsunop = './listngaynghi.php';
    }

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
<div class="coating-sidebar">
    <div class="sidebar-menu">
        <div class="menu-logo">
            <a href="<?=$linkindex?>">
            <span class="logo-name">Members</span>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="hideSidebar two-rotate-right">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                    <path class="icon-twoarrow-vector-last" d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)"></path>
                    <path class="icon-twoarrow-vector-first"  d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="1" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)"></path>
                </g>
            </svg>
        </div>
        <div class="nav-menu">
            <ul class="menu-lists">
                <li class="list animation-submenu avatar-sidebar-user">
                    <div class="list-user" href="<?=$profile?>">
                        <div class="icon-listuser-vector-only">
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
                            <div class="profile-avatar-sidebar">
                                <img src="<?= $imgurl.$img?>" alt="">
                            </div>
                            <div class="icon-avatar-sidebar">
                                <div class="infor-name-profile">
                                    
                                    <span><?=$nameuser?></span>
                                    <span>
                                        <?php
                                        if($username != 'default')
                                        {
                                            if(user($_SESSION['username'])['role'] == 0 ){
                                                echo 'Giám đốc';
                                            }else if(user($_SESSION['username'])['role'] == 1 ){
                                                echo 'Trưởng phòng';
                                            }else if(user($_SESSION['username'])['role'] == 2 ){
                                                echo 'Nhân viên';
                                            }
                                        }
                                        else
                                            echo 'Default'
                                    ?></span>
                                </div>
                                <div>
                                    <a href="<?=$profile?>">
                                        <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path class="icon-setting-vector-last" opacity="0.5" d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z" fill="black"></path>
                                            <path class="icon-setting-vector-last" d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z" fill="black"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </d>
                </li>
                <li class="list-items animation-submenu mr-top-sider">
                    <a href="<?=$linkindex?>">
                        <div class="icon-listuser-vector-only">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path class="icon-dashboard-vector-first" d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
                                    <path class="icon-dashboard-vector-last" d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="1"></path>
                                </g>
                            </svg>
                            <span class="nav-list-title">Trang chủ</span>
                        </div>
                    </a>
                </li>
                <li class="list-items animation-submenu list-items-user">
                    <a class="list-user animation-nav click-user">
                        <div class="icon-listuser-vector-only">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path class="icon-listuser-vector-first" d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="1"/>
                                    <path class="icon-listuser-vector-last" d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                </g>
                            </svg>
                            <span class="nav-list-title">Quản lý nhân viên</span>
                        </div>
                        <div class="arrow-listuser">
                            <i class="fas fa-chevron-right arrow-right-user"></i>
                        </div>
                    </a>
                    <ul class="submenu-listuser submenu-listuser-active">
                        <li>
                            <a href="./<?=$linkadd?>">
                                <i class="fas fa-circle"></i>
                                <span>Thêm nhân viên</span>
                            </a>
                        </li>
                        <li>
                            <a href="./<?=$linklist?>">
                                <i class="fas fa-circle"></i>
                                <span>Danh sách nhân viên</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="list-items animation-submenu list-items-task">
                    <a class="list-user animation-nav click-task">
                        <div class="icon-listuser-vector-only">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect  x="0" y="0" width="24" height="24"></rect>
                                    <path class="icon-listtask-vector-first" d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"></path>
                                    <rect class="icon-listtask-vector-last" fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                                </g>
                            </svg>
                            <span class="nav-list-title">Quản lý nhiệm vụ</span>
                        </div>
                        <div class="arrow-listuser">
                            <i class="fas fa-chevron-right arrow-right-task"></i>
                        </div>
                    </a>
                    <ul class="submenu-listuser">
                        <li class = "danhsachtasknv">
                            <a href="./<?=$linklisttasknv?>">
                                <i class="fas fa-circle"></i>
                                <span>Danh sách nhiệm vụ</span>
                            </a>
                        </li>
                        <li class = "danhsachtasktp">
                            <a href="./<?=$linklisttasktp?>">
                                <i class="fas fa-circle"></i>
                                <span>Danh sách nhiệm vụ</span>
                            </a>
                        </li>
                        <li class = "themtask">
                            <a href="./<?=$linklistaddtask?>">
                                <i class="fas fa-circle"></i>
                                <span>Thêm nhiệm vụ</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="list-items animation-submenu list-items-pb">
                    <a  class="list-user animation-nav click-pb">
                        <div class="icon-listuser-vector-only">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path class="icon-listuser-vector-last" d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z" fill="#000000"></path>
                                    <rect class="icon-listuser-vector-first" fill="#000000" opacity="1" x="2" y="4" width="5" height="16" rx="1"></rect>
                                </g>
                            </svg>
                            <span class="nav-list-title">Quản lý phòng ban</span>
                        </div>
                        <div class="arrow-listuser">
                            <i class="fas fa-chevron-right arrow-right-pb"></i>
                        </div>
                    </a>
                    <ul class="submenu-listuser submenu-listuser-active ">
                        <li>
                            <a href="./<?=$linkaddpb?>">
                                <i class="fas fa-circle"></i>
                                <span>Thêm phòng ban</span>
                            </a>
                        </li>
                        <li>
                            <a href="./<?=$linklistpb?>">
                                <i class="fas fa-circle"></i>
                                <span>Danh sách phòng ban</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="list-items animation-submenu list-items-ngaynghi">
                    <a  class="list-user animation-nav click-ngaynghi">
                        <div class="icon-listuser-vector-only">
                            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24px" height="24px" >
                                <path class="cls-1 icon-listuser-vector-last" d="M364.8,25.6a12.8,12.8,0,1,0-25.6,0V51.2h25.6Z"/>
                                <path class="cls-1 icon-listuser-vector-last" d="M300.8,25.6a12.8,12.8,0,1,0-25.6,0V51.2h25.6Z"/>
                                <path class="cls-1 icon-listuser-vector-last" d="M236.8,25.6a12.8,12.8,0,1,0-25.6,0V51.2h25.6Z"/>
                                <path class="cls-1 icon-listuser-vector-last" d="M428.8,25.6a12.8,12.8,0,1,0-25.6,0V51.2h25.6Z"/>
                                <path class="cls-1 icon-listuser-vector-last" d="M467.2,51.2H428.8V76.8a12.8,12.8,0,1,1-25.6,0V51.2H364.8V76.8a12.8,12.8,0,1,1-25.6,0V51.2H300.8V76.8a12.8,12.8,0,1,1-25.6,0V51.2H236.8V76.8a12.8,12.8,0,0,1-25.6,0V51.2H172.8V76.8a12.8,12.8,0,0,1-25.6,0V51.2H108.8V76.8a12.8,12.8,0,0,1-25.6,0V51.2H44.8A44.85,44.85,0,0,0,0,96v76.8H512V96A44.85,44.85,0,0,0,467.2,51.2Z"/>
                                <path class="cls-1 icon-listuser-vector-last" d="M172.8,25.6a12.8,12.8,0,1,0-25.6,0V51.2h25.6Z"/>
                                <path class="cls-1 icon-listuser-vector-last" d="M108.8,25.6a12.8,12.8,0,0,0-25.6,0V51.2h25.6Z"/>
                                <path class="cls-1 icon-listuser-vector-last" d="M0,454.4a44.85,44.85,0,0,0,44.8,44.8H467.2A44.85,44.85,0,0,0,512,454.4v-256H0ZM279.14,279.22q3.24-4,9.91-4H312.2a24.78,24.78,0,0,1,5,.51,11,11,0,0,1,4.56,2,11.43,11.43,0,0,1,3.33,4.23,15.94,15.94,0,0,1,1.32,6.95v93.32c0,4.86-1.35,8.49-4,10.92a16,16,0,0,1-20.33,0c-2.7-2.43-4-6.06-4-10.91V300.82h-8.94q-6.67,0-9.91-4a13.55,13.55,0,0,1,0-17.58ZM186.83,358.8a15,15,0,0,1,3.07-4,14.67,14.67,0,0,1,4.21-2.72,11.44,11.44,0,0,1,4.64-1,11.92,11.92,0,0,1,6.23,1.45,19.17,19.17,0,0,1,4.2,3.5,36.51,36.51,0,0,1,3.51,4.61,25.09,25.09,0,0,0,3.86,4.61,20.07,20.07,0,0,0,5.34,3.5,19.53,19.53,0,0,0,8.07,1.45,14,14,0,0,0,10.17-3.95,12.81,12.81,0,0,0,4-9.49,11.1,11.1,0,0,0-1.49-6,10.64,10.64,0,0,0-3.94-3.72,19.79,19.79,0,0,0-5.61-2,34.25,34.25,0,0,0-6.66-.63,14.84,14.84,0,0,1-8.24-2.93q-3.85-2.77-3.86-9.09,0-5.23,3.25-7.59t11.66-4.11a17.32,17.32,0,0,0,7.8-3.32c1.93-1.58,2.9-3.95,2.9-7.12a7,7,0,0,0-3.25-6.32,13.57,13.57,0,0,0-7.46-2.06q-5.44,0-8.68,2.31a44,44,0,0,0-5.87,4.95,56.07,56.07,0,0,1-5.62,4.95,12.52,12.52,0,0,1-7.89,2.31,9.83,9.83,0,0,1-4.82-1.19,12.41,12.41,0,0,1-3.68-3,13.46,13.46,0,0,1-3.07-8.45,19.83,19.83,0,0,1,3.77-11.79,33.7,33.7,0,0,1,9.55-9,50.51,50.51,0,0,1,12.71-5.73,46.74,46.74,0,0,1,13.23-2,50.2,50.2,0,0,1,14.82,2.13,37.73,37.73,0,0,1,12.19,6.21,30,30,0,0,1,8.33,10,29,29,0,0,1,3.07,13.48A25.56,25.56,0,0,1,264,319.41q-3.33,6.13-10.7,11.36,10,3.77,15.17,11.61a32,32,0,0,1,5.17,18,31.54,31.54,0,0,1-3.42,14.54,36.68,36.68,0,0,1-9.3,11.61,42.7,42.7,0,0,1-13.85,7.6,53.79,53.79,0,0,1-17.27,2.7,52,52,0,0,1-17.71-2.9,49.15,49.15,0,0,1-13.94-7.52,35.29,35.29,0,0,1-9.2-10.58,24.72,24.72,0,0,1-3.33-12.13A10.19,10.19,0,0,1,186.83,358.8Z"/>
                            </svg>
                            <span class="nav-list-title">Quản lý ngày nghỉ</span>
                        </div>
                        <div class="arrow-listuser">
                            <i class="fas fa-chevron-right arrow-right-ngaynghi"></i>
                        </div>
                    </a>
                    <ul class="submenu-listuser submenu-listuser-active ">
                        <li>
                            <a class = "thongtinnn" href="./<?=$linklistsongaynghi?>">
                                <i class="fas fa-circle"></i>
                                <span>Thông tin ngày nghỉ</span>
                            </a>
                        </li>
                        <li>
                            <a class = "duyetngaynghi" href="./<?=$linklistdanhsachnn?>">
                                <i class="fas fa-circle"></i>
                                <span>Duyệt ngày nghỉ</span>
                            </a>
                        </li>
                        <li>
                            <a class = "lichsund" href="./<?=$linklistlichsunop?>">
                                <i class="fas fa-circle"></i>
                                <span>Lịch sử nộp đơn</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="list-items animation-submenu list-items-logout">
                    <a href= "<?= $logout ?>" class="list-user animation-nav">
                        <div class="icon-listuser-vector-only">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect opacity="0.300000012" x="0" y="0" width="24" height="24"/>
                                    <polygon class="icon-listuser-vector-first" fill="#000000" fill-rule="nonzero" opacity="1" points="7 4.89473684 7 21 5 21 5 3 11 3 11 4.89473684"/>
                                    <path class="icon-listuser-vector-last" d="M10.1782982,2.24743315 L18.1782982,3.6970464 C18.6540619,3.78325557 19,4.19751166 19,4.68102291 L19,19.3190064 C19,19.8025177 18.6540619,20.2167738 18.1782982,20.3029829 L10.1782982,21.7525962 C9.63486295,21.8510675 9.11449486,21.4903531 9.0160235,20.9469179 C9.00536265,20.8880837 9,20.8284119 9,20.7686197 L9,3.23140966 C9,2.67912491 9.44771525,2.23140966 10,2.23140966 C10.0597922,2.23140966 10.119464,2.2367723 10.1782982,2.24743315 Z M11.9166667,12.9060229 C12.6070226,12.9060229 13.1666667,12.2975724 13.1666667,11.5470105 C13.1666667,10.7964487 12.6070226,10.1879981 11.9166667,10.1879981 C11.2263107,10.1879981 10.6666667,10.7964487 10.6666667,11.5470105 C10.6666667,12.2975724 11.2263107,12.9060229 11.9166667,12.9060229 Z" fill="#000000"/>
                                </g>
                            </svg>
                            <span class="nav-list-title">Đăng xuất</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>