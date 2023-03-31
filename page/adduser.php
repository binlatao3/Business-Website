<?php 

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    ob_start();
    require_once "./connectDB.php"; 

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);
    $alertsubmit = false;
    $check_user = check_user($_SESSION['username']);
    if(!isset($_SESSION['username']))
    {
        header("Location: login.php");
        exit;
    }
    else if ($check_user['code'] === 1 && $_SESSION['username'] !== 'default')
    {
        header("Location: login.php");
        exit;
    }
    else
    {
        $user = user($_SESSION['username']);
        if($_SESSION['username'] == 'default')
        {
            echo "
                <script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.list-items-user').style.display = 'block';
                    document.querySelector('.list-items-task').style.display = 'block';
                    document.querySelector('.list-items-pb').style.display = 'block';
                    document.querySelector('.themtask').style.display = 'block';
                    document.querySelector('.list-items-task .danhsachtasktp').style.display = 'block';
                    document.querySelector('.list-items-task .danhsachtasknv').style.display = 'block';
                    document.querySelector('.list-items-ngaynghi').style.display = 'block';
                    document.querySelector('.list-items-ngaynghi .thongtinnn').style.display = 'block';
                    document.querySelector('.list-items-ngaynghi .lichsund').style.display = 'block';
                    document.querySelector('.list-items-ngaynghi .duyetngaynghi').style.display = 'block';
                });
                </script>
            ";
        }
        else if($user['role'] == '0')
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
    <link rel="stylesheet" href="../style.css">
    <title>Add User</title>
</head>

<body>

<?php 

    $conn = open_database();
    $pb = "SELECT Ten_phongban,id_PhongBan from phongban";
    $query = mysqli_query($conn,$pb);
    if($query->num_rows > 0)
    {
        $data = $query->fetch_all(MYSQLI_ASSOC);
    }

    $username = $usernameError= '';
    $password = $passwordError = '';
    $chucvu = 2;
    $phongban = $phongbanError = '';
    $email = $emailError = '';
    $sdt = $sdtError = '';
    $fullname = $fullnameError = '';
    $address = $addressError = '';
    $cmnd = $cmndError = '';
    $error = array();
    $active = 0;
    if(isset($_POST['adduser']))
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $sdt = $_POST['sdt'];
        $fullname = $_POST['name'];
        $address = $_POST['address'];
        $phongban = $_POST['phongban'];
        $cmnd = $_POST['cmnd'];

        if(empty($username)){
            $usernameError = "Nhập tài khoản";
            array_push($error,$usernameError);
        }
        else if(is_username_exits($username))
        {
            $usernameError = "Tài khoản đã tồn tại";
            array_push($error,$usernameError);
        }

        if(empty($phongban))
        {
            $phongbanError = "Nhập phòng ban";
            array_push($error,$phongbanError);
        }

        if(empty($email))
        {
            $emailError = "Nhập email";
            array_push($error,$emailError);
        }
        else if(is_email_exits($email))
        {
            $emailError = "Email đã tồn tại";
            array_push($error,$emailError);
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $emailError = "Email không hợp lệ";
            array_push($error,$emailError);
        }

        if(empty($sdt))
        {
            $sdtError = "Nhập số điện thoại";
            array_push($error,$sdtError);
        }
        else if(is_sdt_exits($sdt))
        {
            $sdtError = "Số điện thoại đã tồn tại";
            array_push($error,$sdtError);
        }
        else if(!is_numeric($sdt))
        {
            $sdtError = "Số điện thoại không hợp lệ";
            array_push($error,$sdtError);
        }

        if(empty($fullname))
        {
            $fullnameError = "Nhập họ tên";
            array_push($error,$fullnameError);
        }

        if(empty($address))
        {
            $addressError = "Nhập địa chỉ";
            array_push($error,$addressError);
        }

        if(empty($cmnd))
        {
            $cmndError = "Nhập CMND/CCCD";
            array_push($error,$cmndError);
        }
        else if(is_cmnd_exits($cmnd))
        {
            $cmndError = "CMND/CCCD đã tồn tại";
            array_push($error,$cmndError);
        }
        else if(!is_numeric($cmnd))
        {
            $cmndError = "CMND/CCCD không hợp lệ";
            array_push($error,$cmndError);
        }
        

        if(empty($error)) 
        {
            $encryptpassword = password_hash($username, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user(fullname,username,password,id_role,id_PhongBan,cmnd,sdt,email,address,active)
            VALUES ('$fullname','$username','$encryptpassword','$chucvu','$phongban','$cmnd','$sdt','$email','$address','active')";
            $mysqli = open_database();
            $query = mysqli_query($mysqli,$sql);
            $update = "UPDATE user set id_user = CONCAT('NV', LPAD(id, 4, '0')) where username = '$username'";
            $qry = mysqli_query($mysqli,$update);
            if($qry)
            {
                $alertsubmit = true;
            }
            mysqli_close($mysqli);
        }
    }
    
?>
    <div id="main" class="js-main">

        <!-- Sidebar -->
        <?php require_once "./sidebar.php";?>


            <!-- Header -->
            <?php require_once "./header.php";?>

            <!--  management-user -->
            <!-- start -->
            <div class="management-user">
                <div class="user-list">
                    <?php if($alertsubmit)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong>   Thêm user thành công!   
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>";
                            echo "<script type='text/javascript'>
                            var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname; 
                            window.history.pushState({ path: refresh }, '', refresh);
                            </script>";
                        }
                        else
                        {
                            echo "<script type='text/javascript'>
                            var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname; 
                            window.history.pushState({ path: refresh }, '', refresh);
                            </script>";
                        }
                    ?>
                    <div class="add-user-header">
                        <div class="add-user-content">
                            <h2 class="add-user-title">User's Profile</h2>
                            <form method="POST" action="">
                                <div class="form-input-add">
                                    <label for="username">Họ tên</label>
                                    <input class="bd-rd-rem" id="name" type="text" value="<?= $fullname?>"  placeholder="Nhập họ tên" name="name">
                                </div>
                                <div class="alert alert-danger error-fullname" style="display:none">
                                    <?php
                                        if(!empty($fullnameError))
                                        {
                                            echo "<script>document.querySelector('.error-fullname').style.display = 'block'</script>";
                                            echo $fullnameError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add">
                                    <label for="cmnd">Căn cước công dân</label>
                                    <input class="bd-rd-rem" id="cmnd" name = "cmnd" type="text"  value="<?= $cmnd ?>" placeholder="CCCD">
                                </div>
                                <div class="alert alert-danger error-cmnd" style="display:none">
                                    <?php
                                        if(!empty($cmndError))
                                        {
                                            echo "<script>document.querySelector('.error-cmnd').style.display = 'block'</script>";
                                            echo $cmndError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add">
                                    <label for="sdt">SĐT</label>
                                    <input class="bd-rd-rem" id="sdt" name = "sdt" type="text"  value="<?= $sdt ?>" placeholder="Số điện thoại">
                                </div>
                                <div class="alert alert-danger error-sdt" style="display:none">
                                    <?php
                                        if(!empty($sdtError))
                                        {
                                            echo "<script>document.querySelector('.error-sdt').style.display = 'block'</script>";
                                            echo $sdtError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add">
                                    <label for="email">Email</label>
                                    <input class="bd-rd-rem" id="email" name = "email" type="text"  value="<?= $email ?>"placeholder="Email">
                                </div>
                                <div class="alert alert-danger error-email" style="display:none">
                                    <?php
                                        if(!empty($emailError))
                                        {
                                            echo "<script>document.querySelector('.error-email').style.display = 'block'</script>";
                                            echo $emailError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add">
                                    <label for="phongban">Phòng ban</label>
                                    <select class = "chon-phongban" name="phongban" value="<?= $phongban ?>">
                                        <option selected value>Chọn phòng ban</option>
                                        <?php
                                            foreach($data as $pb) : ?>
                                                <option value= "<?=$pb['id_PhongBan']?>"><?=$pb['Ten_phongban']?></option>
                                        <?php endforeach; ?>  
                                    </select>
                                </div>
                                <div class="alert alert-danger error-phongban" style="display:none">
                                    <?php
                                        if(!empty($phongbanError))
                                        {
                                            echo "<script>document.querySelector('.error-phongban').style.display = 'block'</script>";
                                            echo $phongbanError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add">
                                    <label for="address">Địa chỉ</label>
                                    <input class="bd-rd-rem" id="address" name = "address" type="text" value="<?= $address ?>" placeholder="Địa chỉ">
                                </div>
                                <div class="alert alert-danger error-address" style="display:none">
                                    <?php
                                        if(!empty($addressError))
                                        {
                                            echo "<script>document.querySelector('.error-address').style.display = 'block'</script>";
                                            echo $addressError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add">
                                    <label for="username">Tài khoản</label>
                                    <input class="bd-rd-rem" id="username" type="text" placeholder="Tài khoản"  value="<?= $username ?>" name="username">
                                </div>
                                <div class="alert alert-danger error-username" style="display:none">
                                    <?php
                                        if(!empty($usernameError))
                                        {
                                            echo "<script>document.querySelector('.error-username').style.display = 'block'</script>";
                                            echo $usernameError;
                                        }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <button name="adduser" class="btn-login bd-rd-rem">Thêm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../main.js"></script>

</body>
</html>