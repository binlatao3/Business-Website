<?php

    session_start();
    ob_get_contents();
    ob_start();

    require_once "./page/connectDB.php";

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);

    if(!isset($_SESSION['username']) || $name === 'www')
    {
        header("Location: ./page/logout.php");
        exit;
    }
    else
    {
        $check_active = is_password_change($_SESSION['username']);
        if($check_active['code'] == 0)
        {
            $user = user($_SESSION['username']);
            if($check_active['active'] == 1 && $user['role'] != '0')
            {
                if(!isset($_SESSION['username']) || $name === 'www')
                {
                    header("Location: ./page/logout.php");
                    exit;
                }
                else if($user['role'] == '2')
                {
                    echo "
                    <script type=\"text/javascript\">
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.list-items-task').style.display = 'block';
                        document.querySelector('.list-items-task .danhsachtasknv').style.display = 'block';
                        document.querySelector('.list-items-ngaynghi').style.display = 'block';
                        document.querySelector('.list-items-ngaynghi .thongtinnn').style.display = 'block';
                        document.querySelector('.list-items-ngaynghi .lichsund').style.display = 'block';
                    });
                    </script>
                    ";
                }
                elseif($user['role'] == '1')
                {
                    echo "
                    <script type=\"text/javascript\">
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.list-items-task').style.display = 'block';
                        document.querySelector('.themtask').style.display = 'block';
                        document.querySelector('.list-items-task .danhsachtasktp').style.display = 'block';
                        document.querySelector('.list-items-ngaynghi').style.display = 'block';
                        document.querySelector('.list-items-ngaynghi .thongtinnn').style.display = 'block';
                        document.querySelector('.list-items-ngaynghi .lichsund').style.display = 'block';
                        document.querySelector('.list-items-ngaynghi .duyetngaynghi').style.display = 'block';
                    });
                    </script>
                    ";
                }
            }
            else if ($user['role'] == '0')
            {
                echo "
                <script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.list-items-user').style.display = 'block';
                    document.querySelector('.list-items-pb').style.display = 'block';
                    document.querySelector('.list-items-ngaynghi').style.display = 'block';
                    document.querySelector('.list-items-ngaynghi .duyetngaynghi').style.display = 'block';
                });
                </script>
                ";
            }
            else
            {
                if($user['role'] != '0')
                {

                    $password = $passwordError = '';
                    $repassword = $repasswordError = '';
                    $change = '';
                    echo "
                        <script type=\"text/javascript\">
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelector('.modal-password').style.display = 'flex'
                        });
                        </script>
                    ";

                    $error = array();
                    if(isset($_POST['submit-verify']))
                    {
                        $password = $_POST['newpassword'];
                        $repassword = $_POST['retypenewpassword'];
                        $verify = verify_password($user['username'],$password);

                        if(empty($password)){
                            $passwordError = "Nhập mật khẩu";
                            array_push($error,$passwordError);
                        }
                        else if ($verify['code'] == 0 )
                        {
                            $passwordError = "Không được nhập mật khẩu cũ";
                            array_push($error,$passwordError);
                        }
                        
                        if(empty($repassword))
                        {
                            $repasswordError = "Chưa xác nhận lại mật khẩu mới";
                            array_push($error,$repasswordError);
                        }
                        else if($password !== $repassword)
                        {
                            $repasswordError = "Mật khẩu không khớp";
                            array_push($error,$repasswordError);
                        }
                        if(empty($error))
                        {
                            include_once ("./page/change_password.php");
                        }
                        
                    }
                }
                else
                {
                    echo "
                        <script type=\"text/javascript\">
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelector('.modal-password').style.display = 'none'
                        });
                        </script>
                    ";
                }
            }
        }
        else if($_SESSION['username'] == 'default')
        {
            echo "
                <script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.modal-password').style.display = 'none'
                    document.querySelector('.list-items-user').style.display = 'block';
                    document.querySelector('.list-items-task').style.display = 'block';
                    document.querySelector('.list-items-pb').style.display = 'block';
                    document.querySelector('.themtask').style.display = 'block';
                    document.querySelector('.list-items-task .danhsachtasktp').style.display = 'block';
                    document.querySelector('.list-items-task .danhsachtasknv').style.display = 'block';
                });
                </script>
            ";
        }
        else
        {
            header("Location: ./page/logout.php");
            exit;
        }

    }
    ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="./style.css">
    <title>Trang chủ</title>
</head>

<body>
    <div id="main" class="js-main">
        
        <!-- sidebar -->
        <?php require_once "./page/sidebar.php";?>

        <div id="section-content-main" class="js-hide-content section-content">
            
            <!-- header -->
            <?php require_once "./page/header.php";?>


            <div id="section-content" class="section-content-dashboard js-hide-content">
                <div class="content-overview">
                    <!-- <a href="#" class="link-sales">
                        <div class="overview overview-sales bd-rd-rem">
                            <div class="overview-icon mr-16">
                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  width="80px" height="100px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="100" height="100"/>
                                            <path class="icon-money-vector-first" d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z" fill="#000000" opacity="0.3" transform="translate(11.500000, 12.000000) rotate(-345.000000) translate(-11.500000, -12.000000) "/>
                                            <path class="icon-money-vector-last" d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z M11.5,14 C12.6045695,14 13.5,13.1045695 13.5,12 C13.5,10.8954305 12.6045695,10 11.5,10 C10.3954305,10 9.5,10.8954305 9.5,12 C9.5,13.1045695 10.3954305,14 11.5,14 Z" fill="#000000"/>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div class="overview-about mr-16">
                                <span class="about-content">Today's Money</span>
                                <h3 class="about-heading">$53K</h3>
                                <span class="about-time">May 23 - June 01 (2021)</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="link-user">
                        <div class="overview overview-user bd-rd-rem">
                            <div class="overview-icon mr-16">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80px" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                    <path class="icon-user-vector" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle  class="icon-user-vector" cx="9" cy="7" r="4"></circle>
                                    <path  class="icon-user-vector" d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path  class="icon-user-vector" d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div class="overview-about mr-16">
                                <span class="about-content">Company's Staff</span>
                                <h3 class="about-heading">346</h3>
                                <span class="about-time">May 23 - June 01 (2021)</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="link-online">
                        <div class="overview overview-online bd-rd-rem">
                            <div class="overview-icon mr-16">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="60px" viewBox="0 0 24 24" version="1.1">
                                    <g  stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <rect class="icon-online-vector" fill="#1bc5bd" x="3" y="13" width="3" height="7" rx="1.5"></rect>
                                        <rect class="icon-online-vector" fill="#1bc5bd" x="8" y="9" width="3" height="11" rx="1.5"></rect>
                                        <rect class="icon-online-vector" fill="#1bc5bd" opacity="0.3" x="13" y="6" width="3" height="14" rx="1.5"></rect>
                                        <rect class="icon-online-vector" fill="#1bc5bd" x="18" y="11" width="3" height="9" rx="1.5"></rect>
                                    </g>
                                </svg>
                            </div>
                            <div class="overview-about mr-16">
                                <span class="about-content">User Online</span>
                                <h3 class="about-heading">25</h3>
                                <span class="about-time">May 23 - June 01 (2021)</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="link-task">
                        <div class="overview overview-task bd-rd-rem">
                            <div class="overview-icon mr-16">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="60px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path class="icon-task-vector-first" d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
                                        <path class="icon-task-vector-last" d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                </svg>
                            </div>
                            <div class="overview-about mr-16">
                                <span class="about-content">Task Complete</span>
                                <h3 class="about-heading">56</h3>
                                <span class="about-time">May 23 - June 01 (2021)</span>
                            </div>
                        </div>
                    </a> -->
                </div>
            </div>
        <div>
    </div>

    <div class="modal-password">
        <div class="modal-change-password">
            <div class="modal-password-title">
                Bạn phải đổi mật khẩu khi đăng nhập lần đầu
            </div>
            <form method="POST" action="">
                <div class="form-input-password">
                    <label>Mật khẩu mới</label>
                    <input class="bd-rd-rem" id="newpassword" name="newpassword" type="password" placeholder="Nhập mật khẩu mới" value="<?= $password ?>">
                </div>
                <div class="alert alert-danger error-newpassword" style="display: none">
                    <?php
                        if(!empty($passwordError))
                        {
                            echo "<script>document.querySelector('.error-newpassword').style.display = 'block'</script>";
                            echo $passwordError;
                        }
                    ?>
                </div>
                <div class="form-input-password">
                    <label>Nhập lại mật khẩu mới</label>
                    <input class="bd-rd-rem" id="retypenewpassword" name="retypenewpassword" type="password" placeholder="Nhập lại mật khẩu mới" value = "<?= $repassword ?>">
                </div>
                <div class="alert alert-danger error-retypenewpassword" style="display: none">
                    <?php
                        if(!empty($repasswordError))
                        {
                            echo "<script>document.querySelector('.error-retypenewpassword').style.display = 'block'</script>";
                            echo $repasswordError;
                        }
                    ?>
                </div>
                <div class="btn-change-password">
                    <button name="submit-verify">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script type="text/javascript" src="./main.js"></script>

</body>
</html>