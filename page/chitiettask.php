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
    if(!isset($_GET['idtask']))
    {
        header("Location: listtask.php");
        exit;
    }

    $idtask = $_GET['idtask'];

    if(is_id_task_exits($idtask) == false)
    {
        header("Location: listtask.php");
        exit;
    }
   
    $alertsub = isset($_GET['checksub'])?$_GET['checksub']:0;
    $danhgia = isset($_GET['danhgia'])?$_GET['danhgia']:null;
    $taskdata = task($idtask);
    $nguoinop = $taskdata['username'];
    $alertdanhgia = false;

    if($danhgia != null)
    {
        if(isset($_COOKIE['preventdel']))
        {
            if($_COOKIE['preventdel']==1)
            {
                setcookie('preventdel','0');
                if($danhgia == 1)
                {
                    $danhgia = 'BAD';
                }
                elseif($danhgia == 2)
                {
                    $danhgia = 'OK';
                }
                else
                {
                    $danhgia = 'GOOD';
                }

                $mysqli = open_database();
                date_default_timezone_set("asia/ho_chi_minh");
                $ngaynop = date("Y-m-d\TH:i");
                $sql = "UPDATE task SET trangthai = 5 where id_task = '$idtask'";
                $sql2 = "UPDATE task SET danhgia = '$danhgia ' where id_task = '$idtask'";
                $sql3 = "INSERT INTO lichsu(id_task,trangthai,ngay) 
                VALUES ('$idtask',5,'$ngaynop')";

                $query = mysqli_query($mysqli,$sql);
                $query2 = mysqli_query($mysqli,$sql2);
                $query3 = mysqli_query($mysqli,$sql3);
                if($query &&  $query2 && $query3)
                {
                    $alertdanhgia = true;
                }
                mysqli_close($mysqli);

            }
        }
    }
    $trangthai = task($idtask)['trangthai'];

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
        $hannop = $_POST['ngay'];
        $nguoigiao = $_SESSION['username'];
        $nguoinhan = $taskdata['username'];
        
        $mysqli = open_database();
        $idotask = lastrowtraodoi();
        $idtraodoi = $idotask;
        if(isset($_FILES['tep']) && !empty($_FILES['tep']['name']))
        {
            $file_name = $_FILES['tep']['name'];
            $file_tmp =$_FILES['tep']['tmp_name'];
            $FileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

            if($idotask == false)
            {
                exit;
            }
            else
            {
                $idotask = 'TASKTD'.$idotask.'.'.$FileType;
            }
            move_uploaded_file($file_tmp,"../files/traodoi/".$idotask);
        }
        else
        {
            $file_name ='';
        }
    
        date_default_timezone_set("asia/ho_chi_minh");
        $ngaynop = date("Y-m-d\TH:i");
        $sql = "INSERT INTO tasktraodoi(id_task,tieude,mota,username,nguoigiao,ngaynop,tepdinhkem,id_role)
        VALUES ('$idtask','$tieude','$mota','$nguoinhan','$nguoigiao','$ngaynop','$file_name',1)";
        $sql2 = "UPDATE task SET trangthai = 4 where id_task = '$idtask'";
        $sql3 = "UPDATE task SET giahan = '$hannop' where id_task = '$idtask'";
        $sql4 = "INSERT INTO lichsu(id_task,trangthai,ngay,id_traodoi) 
        VALUES ('$idtask',4,'$ngaynop','$idtraodoi')";

        $query = mysqli_query($mysqli,$sql);
        $query2 = mysqli_query($mysqli,$sql2);
        $query3 = mysqli_query($mysqli,$sql3);
        $query4 = mysqli_query($mysqli,$sql4);

        $_POST = array();
        if($query &&  $query2 && $query3 && $query4)
        {
            header("Location: chitiettask.php?checksub=1&idtask=".$idtask);
        }
        
        mysqli_close($mysqli);
    }
    
    $conn = open_database();
    $submit = mysqli_query($conn,"SELECT * from tasktraodoi where id_task = '$idtask' and id_role != 1");
    $submitcuoi = mysqli_query($conn,"SELECT * FROM tasktraodoi where id_task = '$idtask' and username = '$nguoinop' ORDER BY id_traodoi DESC LIMIT 1");
    if($submit && $submitcuoi)
    {
        $data = $submit->fetch_all(MYSQLI_ASSOC);
        $data2 = $submitcuoi->fetch_assoc();
    }
    if($data2)
    {
        $deadline = $data2['ngaynop'];
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
                  if($alertsub ==1)
                  {
                  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                  <strong>Thành công!</strong>  Đã từ chối bài nộp của nhân viên!   
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                  </button>
                  </div>";
                  echo "<script type='text/javascript'>
                  var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?idtask=".$idtask."'; 
                  window.history.pushState({ path: refresh }, '', refresh);
                  </script>";
                  } 
                  if($alertdanhgia)
                  {
                  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                  <strong>Thành công!</strong> Đã đánh giá bài nộp của nhân viên!   
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
                    <div class="see-detail-infor-task dis-flex dis-flex-monile">
                        <div>
                            <div class="task-title-detail">
                                <span>Thông tin chi tiết nhiệm vụ</span>
                            </div>
                            <div class="content-task-detail">
                                <div class="infor-task">
                                    <div class="infor-task-title">
                                        <label>Nhiệm vụ:</label>
                                        <span><?=$taskdata['nhiemvu']?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Nhân viên thực hiện:</label>
                                        <span><?php
                                                if(user($taskdata['username']))
                                                    echo user($taskdata['username'])['fullname'];
                                                else
                                                    echo "Chưa có"  
                                        ?></span>
                                    </div>
                                    <div class="infor-task-title ">
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
                                        <label>Nhân viên gửi:</label>
                                        <span><?=user($task['username'])['fullname']?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Mô tả:</label>
                                        <span><?=$task['mota']?></span>
                                    </div>
                                    <div class="infor-task-title infor-task-title-link">
                                        <label>Đính kèm:</label>
                                        <?php
                                            if($task['tepdinhkem'])
                                            {
                                                $filetypearr = explode(".", $task['tepdinhkem']);
                                                $filetype = end($filetypearr);
                                            }
                                        ?>
                                        <?php if($task['tepdinhkem']): ?>
                                            <a href=<?="../files/traodoi/"."TASKTD".$task['id_traodoi'].".".$filetype ?> download=<?=$task['tepdinhkem']?>> <span><?=$task['tepdinhkem']?></span></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="content-task-detail-status">
                                <div class="form-input-extend">
                                   <?php if($trangthai == 3 && $task['id_traodoi'] == $data2['id_traodoi']): ?>
                                        <div class="btn-no-task">
                                            <button type="button" onclick="showModalNoTask()">Không đồng ý</button>
                                        </div>
                                        <div class="btn-yes-task">
                                            <button type="button" onclick="showModaldanhgia()">Đồng ý</button>
                                        </div>
                                    <?php endif;?>
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

    <div class="modal-delete-user js-modal-delete-user">
        <div class="modal-delete">
            <h4 class="title-evaluate">Đánh giá mức độ hoàn thành</h4>
            <div class="evaluate">
                <div>
                    <label>Bad</label>
                    <input type="radio" id="bad" name="danhgiamucdo">
                </div>
                <div>
                    <label>OK</label>
                    <input type="radio" id="ok" name="danhgiamucdo">
                </div>
                <?php if($taskdata['giahan']>$deadline):?>          
                <div>
                    <label>Good</label>
                    <input type="radio" id="good" name="danhgiamucdo">
                </div>
                <?php else:?>
                <div>
                    <label>Good</label>
                    <input type="radio" id="good" name="danhgiamucdo" disabled>
                </div>
                <?php endif;?>
            </div>
            <div class="alert alert-danger error-danhgia"  id= "error-danhgia"  style="display:none">Vui Lòng Chọn Đánh Giá</div>   
            <div class="btn-infor-save">
                <div class="btn-no-infor">
                    <button type="button" onclick="closeModaldanhgia()">Hủy</button>
                </div>
                <div class="btn-yes-evaluate">
                    <button onclick='complete(<?php echo $idtask ?>)' type="button">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-delete-user js-modal-no-task">
        <div class="modal-delete">
            <div class="modal-close-edit" onclick="closeModalNotask()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h4 class="pd-top mr-bottom">Nhận xét nhiệm vụ</h4>
            <form id="submit" method="POST" enctype="multipart/form-data">
                <div class="form-input-add">
                    <label>Tiêu đề</label>
                    <input class="bd-rd-rem date-addtask" id= "tieude" name="tieude" type="text" placeholder="Tiêu đề">
                </div>
                <div class="alert alert-danger error-tieude" id= "error-tieude" style="display:none">Vui Lòng Tiêu Đề</div>
                <div class="form-input-add">
                    <label>Gia hạn (nếu có)</label>
                    <input class="bd-rd-rem date-addtask" id="giahan" name="ngay" type="datetime-local" value="<?=$taskdata['giahan']?>">
                </div>
                <div class="alert alert-danger error-mota" id= "error-ngay" style="display:none">Vui Lòng Nhập gia hạn lớn hơn hạn nộp trước đó</div>
                <div class="form-input-add">
                    <label>Nhận xét</label>
                    <textarea class="textarea-pb" id = "mota" name="mota" type="mota" placeholder="Nhận xét nội dung"></textarea>
                </div>
                <div class="alert alert-danger error-mota" id= "error-mota" style="display:none">Vui Lòng Nhập Mô Tả</div>
                <div class="form-input-file">
                    <label>Đính kèm (nếu có)</label>
                    <div class="custom-file">
                        <input id ="tep" name="tep" type="file" class="custom-file-input js-limit-word" id="document" maxlength="20">
                        <label class="custom-file-label" for="document">Choose file</label>
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
                        <button type="button" onclick="closeModalNotask()">Hủy</button>
                    </div>
                    <div class="btn-yes-evaluate">
                        <button onclick='resubmittask()' type="button">Xác nhận</button>
                    </div>
                </div>
            </form>
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