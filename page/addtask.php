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
        else
        {
            echo "Bạn không đủ quyền truy cập vào trang này<br>";
            echo "<a href='../index.php'> Click để về lại trang chủ</a>";
            exit();
        }
    }   

    $tieude  = $tieudeError= '';
    $mota = $motaError = '';
    $hannop = $hannopError = '';


    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $uploadError = '';

        $tieude = $_POST['tieude'];
        $mota = $_POST['mota'];
        $hannop = $_POST['ngay'];
        $error = array();

        if(empty($tieude)){
            $tieudeError = "Nhập tiêu đề";
            array_push($error,$tieudeError);
        }
        
        if(empty($mota))
        {
            $motaError = "Nhập mô tả";
            array_push($error,$motaError);
        }

        date_default_timezone_set("asia/ho_chi_minh");
        $currentday = date("Y-m-d\TH:i");

        if(empty($hannop))
        {
            $hannopError = "Chọn hạn nộp";
            array_push($error,$hannopError);
        }
        else if($hannop <= $currentday)
        {
            $hannopError = "Vui lòng đặt deadline lớn hơn ngày hiện tại";
            array_push($error,$hannopError);
        }

        if(empty($error)) 
        {
            $mysqli = open_database();
            $nguoigiao  = $_SESSION['username'];
            $file_name = '';
            $idtask = lastrowtask(); 
            $idTraodoi = $idtask;

            if(isset($_FILES['tep']))
            {
                $file_name = $_FILES['tep']['name'];
                $file_tmp =$_FILES['tep']['tmp_name'];
                $FileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

                if($idtask == false)
                {
                    exit;
                }
                else
                {
                    $idtask = 'TASK'.$idtask.'.'.$FileType;
                }
                move_uploaded_file($file_tmp,"../files/truongphong/".$idtask);
            }
            else
            {
                $file_name ='';
            }

            $sql = "INSERT INTO task(nhiemvu,mota,ngaygiao,giahan,nguoigiao,tepdinhkem)
            VALUES ('$tieude','$mota','$currentday','$hannop','$nguoigiao','$file_name')";
            $sql2 = "INSERT INTO lichsu(id_task,trangthai,ngay) 
                    VALUES ('$idTraodoi',0,'$currentday')";

            $query = mysqli_query($mysqli,$sql);
            $query2 = mysqli_query($mysqli,$sql2);
            $_POST = array();
            if($query && $query2)
            {
                header("Location: listtask.php?checkadd=1");
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
    <title>Thêm nhiệm vụ</title>
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
                    <div class="add-user-header">
                        <div class="add-user-content">
                            <h2 class="add-user-title">Thêm nhiệm vụ mới</h2>
                            <form id="submittask" method="POST" enctype="multipart/form-data">
                                <div class="form-input-add">
                                    <label>Tiêu đề</label>
                                    <input class="bd-rd-rem" id="name" name="tieude" type="text" value="<?=$tieude?>" placeholder="Tiêu đề">
                                    <div class="error-username"></div>
                                </div>
                                <div class="alert alert-danger error-tieude" style="display:none">
                                    <?php
                                        if(!empty($tieudeError))
                                        {
                                            echo "<script>document.querySelector('.error-tieude').style.display = 'block'</script>";
                                            echo $tieudeError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-add textarea-pb-label">
                                    <label class="title-textarea-pb">Mô tả nhiệm vụ</label>
                                    <textarea class="bd-rd-rem textarea-pb" id="mota" name="mota" type="text" placeholder="Mô tả nhiệm vụ" ><?php echo htmlspecialchars($mota); ?></textarea>
                                    <div class="error-username"></div>
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
                                <div class="form-input-add">
                                    <label>Hạn nộp</label>
                                    <input class="bd-rd-rem date-addtask" id="ngay" value="<?=$hannop?>" name="ngay" type="datetime-local" >
                                    <div class="error-username"></div>
                                </div>
                                <div class="alert alert-danger error-ngay" style="display:none">
                                    <?php
                                        if(!empty($hannopError))
                                        {
                                            echo "<script>document.querySelector('.error-ngay').style.display = 'block'</script>";
                                            echo $hannopError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input-file">
                                    <label>Đính kèm</label>
                                    <div class="custom-file">
                                        <input name="tep" type="file" class="custom-file-input js-limit-word" id="documenttask" maxlength="20">
                                        <label class="custom-file-label" for="documenttask">Choose file</label>
                                    </div>
                                </div>
                                <div class="alert alert-danger error-file" style="display:none">
                                    <?php
                                        if(!empty($uploadError))
                                        {
                                            echo "<script>document.querySelector('.error-file').style.display = 'block'</script>";
                                            echo $uploadError;
                                        }
                                    ?>
                                </div>
                                <div class = "form-group">
                                    <div class="progress" style = "display: none;height: 12px;">
                                        <div id = "progress-bar" class="progress-bar bg-success" style="width:0%;border-radius: 6px;"></div>
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <div class="alert alert-danger" id = "errortask" style = "display: none;"></div>
                                </div>
                                <div class="btn-add-user">
                                    <button class="btn-user" type="button" onclick="showModalPb()">Thêm</button>
                                </div>
                                
                                     <!-- end -->
                                <div class="modal-add-pb js-modal-add-pb">
                                    <div class="modal-pb"> 
                                        <h4>Bạn muốn thêm phòng nhiệm vụ mới</h4>
                                        <h6>Kinh tế</h6>
                                        <div>
                                            <div class="btn-close-password">
                                                <button type="button" onclick="closeModalpb()">Hủy</button>
                                            </div>
                                            <div class="btn-change-password">
                                                <button onclick = "uploadtask()" type="button">Thêm</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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