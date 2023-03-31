<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    require_once "./connectDB.php"; 

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);
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
                });
                </script>
            ";
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
        else
        {
            echo "Bạn không đủ quyền truy cập vào trang này<br>";
            echo "<a href='../index.php'> Click để về lại trang chủ</a>";
            exit();
        }
    }
    if(!isset($_GET['idtask']))
    {
        header("Location: tasknhanvien.php");
        exit;
    }
    $idtask = $_GET['idtask'];

    if(is_id_task_exits($idtask) == false)
    {
        header("Location: tasknhanvien.php");
        exit;
    }
    $trangthai = task($idtask)['trangthai'];
    if($trangthai == 2)
    {
        header("Location: tasknhanvien.php");
        exit;
    }
    $starttask = isset($_GET['starttask'])?$_GET['starttask']:null;
    date_default_timezone_set("asia/ho_chi_minh");
    if(isset($_COOKIE['preventdel']))
    {
        if($_COOKIE['preventdel']==1)
        {
            setcookie('preventdel','0');
            if($starttask)
            {
                $conn = open_database();
                $currentday = date("Y-m-d\TH:i");
                $sql = "UPDATE task SET trangthai = 1 where id_task = '$starttask'";
                $sql2 = "INSERT INTO lichsu(id_task,trangthai,ngay) 
                        VALUES ('$idtask',1,'$currentday')";
                if(mysqli_query($conn,$sql) && mysqli_query($conn,$sql2))
                {
                    header("Location: chitiettasknv.php?alertstart=1&idtask=".$idtask);
                }
            }
        }
    }
    

    $alertstart = isset($_GET['alertstart'])?$_GET['alertstart']:0;
    $alertsub = isset($_GET['checksub'])?$_GET['checksub']:0;

    $taskdata = task($idtask);
    
    $temp= explode("T", $taskdata['giahan']);
    $temp2= explode("T", $taskdata['ngaygiao']);

    $ngaynop= date("d/m/Y", strtotime($temp[0]));
    $gionop = $temp[1];

    $ngaygiao = date("d/m/Y", strtotime($temp2[0]));
    $giogiao = $temp2[1];

    $filetypearr = explode(".", $taskdata['tepdinhkem']);
    $filetype = end($filetypearr);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {

        $tieude = $_POST['tieude'];
        $mota = $_POST['mota'];

        $mysqli = open_database();
        $nguoigui = $_SESSION['username'];
        $nguoigiao = $taskdata['nguoigiao'];
         
        $file_name = $_FILES['tep']['name'];
        $file_tmp =$_FILES['tep']['tmp_name'];
        $FileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
        $idotask = lastrowtraodoi();
        $idtraodoi = $idotask;
        if($idotask == false)
        {
            exit;
        }
        else
        {
            $idotask = 'TASKTD'.$idotask.'.'.$FileType;
        }
        move_uploaded_file($file_tmp,"../files/traodoi/".$idotask);
    
        date_default_timezone_set("asia/ho_chi_minh");
        $ngaynop = date("Y-m-d\TH:i");
        $sql = "INSERT INTO tasktraodoi(id_task,tieude,mota,username,nguoigiao,ngaynop,tepdinhkem)
        VALUES ('$idtask','$tieude','$mota','$nguoigui','$nguoigiao','$ngaynop','$file_name')";
        $sql2 = "UPDATE task SET trangthai = 3 where id_task = '$idtask'";
        $sql3 = "INSERT INTO lichsu(id_task,trangthai,ngay,id_traodoi) 
        VALUES ('$idtask',3,'$ngaynop','$idtraodoi')";
        
        $query = mysqli_query($mysqli,$sql);
        $query2 = mysqli_query($mysqli,$sql2);
        $query3 = mysqli_query($mysqli,$sql3);

        $_POST = array();
        if($query &&  $query2 && $query3)
        {
            header("Location: chitiettasknv.php?checksub=1&idtask=".$idtask);
        }
        
        mysqli_close($mysqli);
    }

    $conn = open_database();
    $submit = mysqli_query($conn,"SELECT * from tasktraodoi where id_task = '$idtask' and id_role != 0");
    if($submit)
    {
        $data = $submit->fetch_all(MYSQLI_ASSOC);
    }
    mysqli_close($conn); 
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
    <title>Thông tin nhiệm vụ</title>
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
            <?php 
                if($alertstart ==1)
                    {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Bắt Đầu!</strong>  Nhiệm vụ Bắt Đầu!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
                    echo "<script type='text/javascript'>
                    var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?idtask=".$idtask."'; 
                    window.history.pushState({ path: refresh }, '', refresh);
                    </script>"; 
                    }
                    if($alertsub ==1)
                    {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Thành công!</strong>  Nộp Thành Công!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
                    echo "<script type='text/javascript'>
                    var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?idtask=".$idtask."'; 
                    window.history.pushState({ path: refresh }, '', refresh);
                    </script>"; 
                }
                ?>
                <div class="see-detail-task">
                    <div class="see-detail-infor-task dis-flex-monile">
                        <div>
                            <div class="task-title-detail">
                                <span>Thông tin chi tiết nhiệm vụ</span>
                            </div>
                            <div class="content-task-detail">
                                <div class="infor-task">
                                    <div class="infor-task-title">
                                        <label>Nhiệm vụ: </label>
                                        <span><?=$taskdata['nhiemvu']?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Người Giao:</label>
                                        <span><?=user($taskdata['nguoigiao'])['fullname']?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Hạn nộp:</label>
                                        <span><?=$ngaynop?></span>
                                        <span><?="Giờ: ".$gionop?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Mô tả:</label>
                                        <span><?=$taskdata['mota']?></span>
                                    </div>
                                    <div class="infor-task-title infor-task-title-link">
                                        <label>Đính kèm:</label>
                                        <?php if($taskdata['tepdinhkem'] ==""): ?>
                                            <span>Không có</span>
                                        <?php else: ?>
                                            <a href=<?="../files/truongphong/"."TASK".$taskdata['id_task'].".".$filetype ?> download=<?=$taskdata['tepdinhkem']?>> <span><?=$taskdata['tepdinhkem']?></span></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="task-start-nv">
                                    <?php if($trangthai == 0): ?>
                                        <button class="start-nv" type="button" onclick='showModalstart()' >Start</button>
                                    <?php elseif($trangthai == 1 || $trangthai == 4): ?>   
                                            <button class="task-submit-nv" type="button" onclick="showModalTaskNV()">Submit</button>
                                    <?php endif;?>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="status-time">
                        <?php
                            switch ($trangthai) {
                                case 0:
                                    $class = "list-task-status-new";
                                break;
                                case 1:
                                    $class = "list-task-status-inprogress";
                                break;
                                case 2:
                                    $class = "list-task-status-canceled";
                                break;
                                case 3:
                                    $class = "list-task-status-waiting";
                                    break;
                                case 4:
                                    $class = "list-task-status-rejected";
                                    break;
                                default:
                                    $class = "list-task-status-completed";      
                            }
                            ?>
                            <div class=<?=$class?>></div>
                            <div class="datetime-task">
                                <span><?=$giogiao?></span>
                                <span><?=$ngaygiao?></span>
                            </div>
                        </div>
                    </div>
                </div>

            <?php foreach($data as $task): ?>
            <div class="see-detail-task mr-top">
                <div class="see-detail-infor-task dis-flex">
                    <div>
                        <div class="task-title-detail">
                            <span><?=$task['tieude']?></span>
                        </div>
                        <div class="content-task-detail">
                            <div class="infor-task">
                                <div class="infor-task-title">
                                    <label>Trưởng phòng gửi:</label>
                                    <span><?=user($task['nguoigiao'])['fullname']?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Nhận xét:</label>
                                    <span><?=$task['mota']?></span>
                                </div>
                                <div class="infor-task-title infor-task-title-link">
                                <?php
                                    if($task['tepdinhkem'])
                                    {
                                        $filetypearr = explode(".", $task['tepdinhkem']);
                                        $filetype = end($filetypearr);
                                    }
                                ?>
                                <label>Đính kèm:</label>
                                <?php if($task['tepdinhkem'] ==""): ?>
                                        <span>Không có</span>
                                    <?php else: ?>
                                        <a href=<?="../files/traodoi/"."TASKTD".$task['id_traodoi'].".".$filetype ?> download=<?=$task['tepdinhkem']?>> <span><?=$task['tepdinhkem']?></span></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="datetime-task">
                    <span><?=realtime($task['ngaynop'])?></span>
                    <span><?=realdate($task['ngaynop'])?></span>
                    </div>
                </div>
            </div>
            <?php endforeach;?>

            </div>
        </div>
        <!-- end -->
    </div>

    <div class="modal-delete-user js-modal-no-task">
        <div class="modal-delete">
            <div class="modal-close-edit" onclick="closeModalTaskNV()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h4 class="pd-top mr-bottom">Nộp báo cáo</h4>
            <form id="submit" method="POST" enctype="multipart/form-data">
                <div class="form-input-add">
                    <label>Tiêu đề:</label>
                    <input class="bd-rd-rem date-addtask" id= "tieude" name="tieude" type="text" placeholder="Tiêu đề">
                </div>
                <div class="alert alert-danger error-tieude" id= "error-tieude" style="display:none">Vui Lòng Tiêu Đề</div>
                <div class="form-input-add text-area-maxwd">
                    <label>Mô tả</label>
                    <textarea class="textarea-pb" id = "mota" name="mota" type="mota" placeholder="Mô tả nội dung"></textarea>
                </div>
                <div class="alert alert-danger error-mota" id= "error-mota" style="display:none">Vui Lòng Nhập Mô Tả</div>
                <div class="form-input-file">
                    <label>Đính kèm</label>
                    <div class="custom-file">
                        <input id ="tep" name="tep" type="file" class="custom-file-input js-limit-word" maxlength="20">
                        <label class="custom-file-label" for="documentnv">Choose file</label>
                    </div>      
                </div>
                <div class="alert alert-danger error-file"  id= "error-file"  style="display:none"></div>  
                <div class = "form-group">
                    <div class="progress" style = "display: none;height: 12px;">
                        <div id = "progress-bar" class="progress-bar bg-success" style="width:0%;border-radius: 6px;"></div>
                    </div> 
                </div>    
                <div class="btn-infor-save">
                    <div class="btn-no-infor">
                        <button type="button" onclick="closeModalTaskNV()">Hủy</button>
                    </div>
                    <div class="btn-yes-evaluate">
                        <button onclick="submittask()" type="button">Xác nhận</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-delete-user js-modal-delete-user">
        <div class="modal-delete">
            <h4 class="title-evaluate">Bạn muốn bắt đầu nhiệm vụ này</h4>
            <div class="btn-infor-save">
                <div class="btn-no-infor">
                    <button type="button" onclick="closeModalDeleteuser()">Hủy</button>
                </div>
                <div class="btn-yes-evaluate">
                    <button onclick='startmiss(<?php echo $idtask ?>)' type="button">Xác nhận</button>
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
