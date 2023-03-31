<!DOCTYPE html>
<html lang="en">
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

<?php 


    $sophongban  = $sophongbanError= '';
    $tenpb = $tenpbError = '';
    $mota = $motaError = '';

    $error = array();

    if(isset($_POST['addpb']))
    {
        echo "<script>console.log(true)</script>";
        $sophongban = $_POST['sophongban'];
        $tenpb = $_POST['tenpb'];
        $mota = $_POST['mota'];

        if(empty($sophongban)){
            $sophongbanError = "Nhập số phòng ban";
            array_push($error,$sophongbanError);
        }
        else if(is_pb_exits($sophongban))
        {
            $sophongbanError = "Số phòng ban đã tồn tại";
            array_push($error,$sophongbanError);
        }

        if(empty($tenpb))
        {
            $tenpbError = "Nhập tên phòng ban";
            array_push($error,$tenpbError);
        }

        if(empty($mota))
        {
            $motaError = "Nhập mô tả";
            array_push($error,$motaError);
        }

        if(empty($error)) 
        {

            $sql = "INSERT INTO phongban(id_PhongBan,Ten_phongban,mota)
            VALUES ('$sophongban','$tenpb','$mota')";
            $mysqli = open_database();
            $query = mysqli_query($mysqli,$sql);
            if($query)
            {
                $alertsubmit = true;
            }
            mysqli_close($mysqli);
        }
    }
?>

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
                    <?php if($alertsubmit)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong>   Thêm Phòng ban thành công!   
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>";
                        }
                    ?>
                    <div class="add-user-header">
                        <div class="add-user-content">
                            <h2 class="add-user-title">Thêm thông tin phòng ban</h2>
                            <form method="POST">
                                <div class="form-input-add">
                                    <label>Số phòng ban</label>
                                    <input class="bd-rd-rem" id="name" name="sophongban" type="text" value="<?= $sophongban ?>" placeholder="Số phòng ban">
                                </div>
                                <div class="alert alert-danger error-sophongban" style="display:none">
                                    <?php
                                        if(!empty($sophongbanError))
                                        {
                                            echo "<script>document.querySelector('.error-sophongban').style.display = 'block'</script>";
                                            echo $sophongbanError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add">
                                    <label>Tên phòng ban</label>
                                    <input class="bd-rd-rem" id="name" name = "tenpb" type="text" value="<?= $tenpb ?>"placeholder="Tên phòng ban">
                                </div>
                                <div class="alert alert-danger error-tenpb" style="display:none">
                                    <?php
                                        if(!empty($tenpbError))
                                        {
                                            echo "<script>document.querySelector('.error-tenpb').style.display = 'block'</script>";
                                            echo $tenpbError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add textarea-pb-label">
                                    <label class="title-textarea-pb">Mô tả</label>
                                    <textarea class="bd-rd-rem textarea-pb" id="name" name = "mota" type="text" value="<?= $mota ?>" placeholder="Mô tả phòng ban"></textarea>
                                </div>
                                <div class="alert alert-danger error-mota" style="display:none">
                                    <?php
                                        if(!empty($motaError))
                                        {
                                            echo "<script>document.querySelector('.error-mota').style.display = 'block'</script>";
                                            echo $motaError;
                                        }
                                    ?>
                                </div>
                                <div class="btn-add-user">
                                    <button class="btn-user" type="button" onclick="showModalPb()">Thêm</button>
                                </div>
                                <div class="modal-add-pb js-modal-add-pb">
                                    <div class="modal-pb"> 
                                        <h4>Bạn muốn thêm phòng ban</h4>
                                        <h6>Kinh tế</h6>
                                        <div>
                                            <div class="btn-close-password">
                                                <button type="button" onclick="closeModalpb()">Hủy</button>
                                            </div>
                                            <div class="btn-change-password">
                                                <button name = "addpb" type="submit">Thêm</button>
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
            <div class="modal-add-pb js-modal-add-pb">
                <div class="modal-pb"> 
                    <h4>Bạn muốn thêm phòng ban</h4>
                    <h6>Kinh tế</h6>
                    <div>
                        <div class="btn-close-password">
                            <button type="button" onclick="closeModalpb()">Hủy</button>
                        </div>
                        <div class="btn-change-password">
                            <button type="button">Thêm</button>
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