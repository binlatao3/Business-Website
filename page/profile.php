<?php

    session_start();

    require_once "./connectDB.php";

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);
    $user = user($_SESSION['username']);
    
    
    if(!isset($_SESSION['username']) || $name === 'www')
    {
        header("Location: ./page/logout.php");
        exit;
    }
    else
    {
        if($user['role'] == '2')
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
        else if($user['role'] == '1')
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
            echo "Bạn không đủ quyền truy cập vào trang này<br>";
            echo "<a href='../index.php'> Click để về lại trang chủ</a>";
            exit();
        }
    }
    $alertimg = false;
    $password = $passwordError = '';
    $newpassword = $newpasswordError = '';
    $repassword = $repasswordError = '';
    $change = '';

    $error = array();

    if(isset($_POST['submit-verify']))
    {
        $password = $_POST['password'];
        $newpassword = $_POST['newpassword'];
        $repassword = $_POST['retypenewpassword'];
        $verify = verify_password($user['username'],$password);

        if(empty($password)){
            $passwordError = "Nhập mật khẩu cũ";
            array_push($error,$passwordError);
        }
        else if ($verify['code'] == 2 )
        {
            $passwordError = "Mật khẩu không chính xác";
            array_push($error,$passwordError);
        }
        else
        {
            if(empty($newpassword)){
                $newpasswordError = "Nhập mật khẩu mới";
                array_push($error,$newpasswordError);
            }
            else if ($password ==  $newpassword)
            {
                $newpasswordError = "Không được nhập mật khẩu cũ";
                array_push($error,$newpasswordError);
            }
            else
            {
                if(empty($repassword))
                {
                    $repasswordError = "Chưa xác nhận lại mật khẩu mới";
                    array_push($error,$repasswordError);
                }
                else if($newpassword !== $repassword)
                {
                    $repasswordError = "Mật khẩu không khớp";
                    array_push($error,$repasswordError);
                }
            }
        }
        $_POST = array();
        if(empty($error))
        {
            update_password($user['username'],$newpassword);
            header("Location: ./logout.php");
            exit;
        }
        else if($error)
        {
            echo "
            <script type=\"text/javascript\">
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.js-modal-delete-user').classList.add('active')
            });
            </script>
            ";
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if(isset($_FILES['input-img']))
        {
            $mysqli = open_database();
            $username = $_SESSION['username'];
            $file_name = $_FILES['input-img']['name'];
            $file_tmp =$_FILES['input-img']['tmp_name'];
            $FileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
            $idimg = user($username)['id_user'];
            $idimg = $idimg.'.'.$FileType;
            $path = "../images/$idimg";
            if (file_exists( $path)) {
                unlink($path);
            }
            $sql = "UPDATE user SET img = '$idimg' WHERE username = '$username'";

            $query = mysqli_query($mysqli,$sql);
            $_POST = array();
            if($query)
            {
                move_uploaded_file($file_tmp,"../images/".$idimg);
                $alertimg = true;
            }
            mysqli_close($mysqli);
        }
    }
        
    
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
    <link rel="stylesheet" href="../style.css">
    <title>Thông tin phòng ban</title>
</head>

<body>
    <div id="main" class="js-main">

        <!-- Sidebar -->
        <?php require_once "./sidebar.php";?>


        <!-- Header -->
        <?php require_once "./header.php";?>

        <!--  management-user -->
        <!-- start -->
        <div class="management-user">
            <div class="user-list">
            <?php if($alertimg)
                {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Thành công!</strong> Update Hình ảnh thành công!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
                }
                ?>
                <div class="see-detail-task">
                    <div class="see-detail-infor-task">
                        <div class="profile-title-detail">
                            <span>Thông tin cá nhân</span>
                        </div>
                            <div class="content-profile-detail">
                                <div class="infor-profile">
                                    <div class="img-profile">
                                    <?php 
                                        if(user($_SESSION['username'])['img'])
                                            $imgt = '../images/'.user($_SESSION['username'])['img'];
                                        else
                                            $imgt = "../images/default.png";
                                    ?> 
                                    <img id ="anh" src=<?=$imgt?>>
                                    <div class="btn-yes-profile">
                                    <label class="custom-file-upload">
                                    <form id="change-img" method="post" enctype="multipart/form-data" >
                                        <input name="input-img" type="file" onchange="loadimg(event)"/>
                                        <i class="fas fa-pen"></i>
                                    </form>
                                    </label>
                                    </div>
                                    </div>
                                    <div class="infor-profile-title">
                                        <label>Mã số nhân viên:</label>
                                        <span><?=user($_SESSION['username'])['id_user']?></span>
                                    </div>
                                    <div class="infor-profile-title">
                                        <label>Họ và tên:</label>
                                        <span><?=user($_SESSION['username'])['fullname']?></span>
                                    </div>
                                    <div class="infor-profile-title">
                                        <label>Tên tài khoản:</label>
                                        <span><?=user($_SESSION['username'])['username']?></span>
                                    </div>
                                    <div class="infor-profile-title">
                                        <label>Chức vụ:</label>
                                        <span>
                                        <?php
                                            if(user($_SESSION['username'])['role'] == 0)
                                                echo "Giám đốc";
                                            else if(user($_SESSION['username'])['role'] == 1)
                                                echo "Trưởng Phòng";
                                            else
                                                echo "Nhân Viên"
                                        ?></span>
                                    </div>
                                    <?php if(user($_SESSION['username'])['id_phongban']):?>
                                    <div class="infor-profile-title">
                                        <label>Phòng ban:</label>
                                        <span><?=user($_SESSION['username'])['id_phongban']?></span>
                                    </div>
                                    <?php endif;?>
                                    <div class="infor-profile-title">
                                        <label>CCCD:</label>
                                        <span><?=user($_SESSION['username'])['cmnd']?></span>
                                    </div>
                                    <div class="infor-profile-title">
                                        <label>Số điện thoại:</label>
                                        <span><?=user($_SESSION['username'])['sdt']?></span>
                                    </div>
                                    <div class="infor-profile-title">
                                        <label>Email:</label>
                                        <span><?=user($_SESSION['username'])['email']?></span>
                                    </div>
                                    <div class="infor-profile-title">
                                        <label>Địa chỉ:</label>
                                        <span><?=user($_SESSION['username'])['address']?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-danger error-file" id="error-file"style="display:none">Định dạng file không hợp lệ</div>
                            <div class="content-task-detail-status">
                                <div class="form-input-extend">
                                    <div class="btn-no-task">
                                        <button type="button" onclick="showModalNewPw()">Đổi mật khẩu</button>
                                    </div>
                                    <div class="btn-no-task color-blu">
                                        <button onclick="luuanh()" type="button">Lưu ảnh</button>
                                    </div>
                                </div>
                            </div>
                            <form method="POST">
                            <div class="modal-delete-user js-modal-delete-user">
                                <div class="modal-delete">
                                    <h4 class="title-evaluate">Thay đổi mật khẩu</h4>
                                    <div class="form-input-password">
                                        <label>Mật khẩu cũ</label>
                                        <input class="bd-rd-rem" id="password" name="password" type="password" placeholder="Nhập mật khẩu cũ">
                                    </div>
                                    <div class="alert alert-danger error-password" style="display: none">
                                        <?php
                                            if(!empty($passwordError))
                                            {
                                                echo "<script>document.querySelector('.error-password').style.display = 'block'</script>";
                                                echo $passwordError;
                                            }
                                        ?>
                                    </div>
                                    <div class="form-input-password">
                                        <label>Mật khẩu mới</label>
                                        <input class="bd-rd-rem" id="newpassword" name="newpassword" type="password" placeholder="Nhập mật khẩu mới">
                                    </div>
                                    <div class="alert alert-danger error-newpassword" style="display: none">
                                        <?php
                                            if(!empty($newpasswordError))
                                            {
                                                echo "<script>document.querySelector('.error-newpassword').style.display = 'block'</script>";
                                                echo $newpasswordError;
                                            }
                                        ?>
                                    </div>
                                    <div class="form-input-password">
                                        <label>Nhập lại mật khẩu mới</label>
                                        <input class="bd-rd-rem" id="retypenewpassword" name="retypenewpassword" type="password" placeholder="Nhập lại mật khẩu mới">
                                        <div class="error-username"></div>
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
                                    <div class="btn-infor-save">
                                        <div class="btn-no-infor">
                                            <button type="button" onclick="closeModalNewPw()">Hủy bỏ</button>
                                        </div>
                                        <div class="btn-change-password">
                                            <button name="submit-verify">Xác nhận</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../main.js"></script>

</body>
</html>