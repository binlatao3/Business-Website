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
    $alertnv = isset($_GET['checkadd'])? $_GET['checkadd']:0;
    $alerttask = false;
    $alertcancel = false;

    $conn = open_database();
    $limit = 10;
    $page = isset($_GET['page'])? $_GET['page'] : 1;
    $start = ($page -1 ) * $limit;
    $nhanvien = [];
    if($_SESSION['username'] != 'default')
    {
        $sophongban = dataphongban($_SESSION['username'])['id_PhongBan'];
        $truongphong = $_SESSION['username'];
    }

    $nhanvienid = isset($_GET['nhanvienid'])? $_GET['nhanvienid'] : null;
    $idtask = isset($_GET['idtask'])? $_GET['idtask'] : null;

    if(isset($_GET['idcancel']))
    {
        $idcancel = $_GET['idcancel'];
        $trangthai ='';
        $getstatus = mysqli_query($conn,"SELECT trangthai from task where id_task =  '$idcancel'");
        $status = $getstatus->fetch_assoc();
        if($status)
        {
            $trangthai = $status['trangthai'];
        }
        if($trangthai ==0)
        {
            if(isset($_COOKIE['preventdel']))
            {
                if($_COOKIE['preventdel']==1)
                {

                    date_default_timezone_set("asia/ho_chi_minh");
                    $currentday = date("Y-m-d\TH:i");
                    $sql = "UPDATE task SET trangthai = 2 where id_task = '$idcancel'";
                    $sql2 = "INSERT INTO lichsu(id_task,trangthai,ngay) 
                    VALUES ('$idcancel',2,'$currentday')";
                    $update = mysqli_query($conn,$sql);
                    $update2 = mysqli_query($conn,$sql2);
                    if($update &&  $update2)
                    { 
                        $alertcancel = true;
                    }
                    setcookie('preventdel','0');
                }
            }
        }
    }

    if($nhanvienid &&  $idtask)
    {
        $username = id($nhanvienid)['username'];
        $sql = "UPDATE task SET username = '".$username."' WHERE id_task='".$idtask."'";

        $update = mysqli_query($conn,$sql);
        if($update)
        { 
            $alerttask = true;
        }
    }

    if(mysqli_query($conn,"SELECT * from task where nguoigiao = '".$_SESSION['username']."' ORDER BY id_task DESC LIMIT $start, $limit"))
    {
        $count= mysqli_query($conn,"SELECT * from task where nguoigiao = '".$_SESSION['username']."'ORDER BY id_task DESC LIMIT $start, $limit");
        $data = $count->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        exit;
    }

    $count= mysqli_query($conn,"SELECT count(*) as total from task where nguoigiao = '".$_SESSION['username']."'");
    $total = $count->fetch_assoc();
    $pages = ceil($total['total']/$limit);
    $previous = $page - 1;
    $next = $page + 1;
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
    <link rel="shortcut icon" href="../images/bg3.png" />
    <link rel="stylesheet" href="../style.css">
    <title>Danh sách nhiệm vụ</title>
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
                <?php if($alertnv)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong>   Thêm nhiệm vụ thành công!   
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>";
                        }
                        if($alerttask)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong> Thêm nhân viên thành công!   
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>";
                            echo "<script type='text/javascript'>
                            var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?page=".$page."'; 
                            window.history.pushState({ path: refresh }, '', refresh);
                            </script>";
                        }
                        if($alertcancel)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong> Hủy nhiệm vụ thành công!   
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>";
                            echo "<script type='text/javascript'>
                            var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?page=".$page."'; 
                            window.history.pushState({ path: refresh }, '', refresh);
                            </script>";
                        }
                    ?>
                    <div class="list-header">
                        <div class="header-title">
                            <h4>Danh sách nhiệm vụ</h4>
                        </div>
                        <div class="header-choose">
                            <a  href="./addtask.php" class="user-add" style="text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <rect class="icon-adduser-vector-first" class="icon-listuser-vector-first"  fill="#000000" opacity="1" x="4" y="5" width="16" height="6" rx="1.5"></rect>
                                        <rect class="icon-adduser-vector-last" class="icon-listuser-vector-last"  fill="#000000" x="4" y="13" width="16" height="6" rx="1.5"></rect>
                                    </g>
                                </svg>
                                <span class="dis-bl-mobile">Thêm nhiệm vụ</span>
                            </a>
                        </div>
                    </div>
                    <div class="header-content-table">
                        <div class="table-data-list">
                            <div class="list-management-user">
                                <table class="user-table">
                                    <tr class="title-table">
                                        <th class="dis-bl-mobile">Nhiệm vụ</th>
                                        <th>Người nhận</th>
                                        <th class="dis-bl-mobile">Trạng thái</th>
                                        <th class="dis-bl dis-bl-mobile">Mô tả</th>
                                        <th class="dis-bl dis-bl-mobile">Đánh giá</th>
                                        <th>Chức năng</td>
                                    </tr>
                                    <?php foreach($data as $task): ?>
                                    <tr class="title-content">
                                        <td class="list-task-title dis-bl-mobile"><?=$task['nhiemvu']?></td>
                                        <td class="list-task-name-user"><?php
                                        if(user($task['username']))
                                                echo user($task['username'])['fullname'];
                                                else
                                                echo "Chưa có"    
                                        ?></td>
                                        <!-- list-task-status-new 0-->
                                        <!-- list-task-status-inprogress 1-->
                                        <!-- list-task-status-canceled 2-->
                                        <!-- list-task-status-waiting 3-->
                                        <!-- list-task-status-rejected 4-->
                                        <!-- list-task-status-completed 5-->
                                        <?php
                                            switch ($task['trangthai']) {
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
                                        <td class="<?=$class?> dis-bl-mobile"></td>
                                        <td class="list-task-content dis-bl dis-bl-mobile"><?=$task['mota']?></td>
                                        <!-- đánh giá -->
                                        <td class="ev-task dis-bl dis-bl-mobile">
                                            <?=$task['danhgia']?>
                                        </td>
                                        <td class="see-edit-delete">
                                            <a href="./chitiettask.php?idtask=<?=$task['id_task']?>" class="link-see bd-rd-rem">
                                                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="eye" class="svg-inline--fa fa-eye fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19.5px" height="19.5px">
                                                    <path class="icon-see-vector-only" fill="currentColor" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                </svg>
                                            </a>
                                            <?php
                                                  $conn = open_database();
                                                  $user= mysqli_query($conn,"SELECT * FROM user WHERE id_PhongBan = '$sophongban' AND username !='$truongphong'"); 
                                                  if($user)
                                                  {
                                                      while($row = $user->fetch_assoc()) {
                                                              array_push($nhanvien,$row);
                                                      } 
                                                  }
                                                  $nhiemvu = json_encode($task['nhiemvu']);
                                                  $tenphongban = json_encode(dataphongban($truongphong)['Ten_phongban']);
                                                  $matask = json_encode($task['id_task']);
                                                  $param = json_encode($nhanvien);
                                                  mysqli_close($conn);  
                                            ?>

                                            <?php if($task['username']==null  && $task['trangthai'] != 2): ?>
                                                <a  class="link-edit bd-rd-rem" onclick='showModalTask(<?php echo $param?>,<?php echo $tenphongban?>,<?php echo $nhiemvu ?>,<?php echo $matask ?>)'>
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect class="icon-edit-vector-first" fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
                                                            <rect class="icon-edit-vector-first" fill="#000000" opacity="1" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
                                                        </g>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="./taskhistory.php?idtask=<?=$task['id_task']?>" class="link-change-password bd-rd-rem" > 
                                                <svg  viewBox="0 0 48 48" width="24px" height="24px" xmlns="http://www.w3.org/2000/svg">
                                                    <path  d="M0 0h48v48h-48z" fill="none"/>
                                                    <path  class="icon-change-vector-last" d="M25.99 6c-9.95 0-17.99 8.06-17.99 18h-6l7.79 7.79.14.29 8.07-8.08h-6c0-7.73 6.27-14 14-14s14 6.27 14 14-6.27 14-14 14c-3.87 0-7.36-1.58-9.89-4.11l-2.83 2.83c3.25 3.26 7.74 5.28 12.71 5.28 9.95 0 18.01-8.06 18.01-18s-8.06-18-18.01-18zm-1.99 10v10l8.56 5.08 1.44-2.43-7-4.15v-8.5h-3z" opacity="1"/>
                                                </svg>
                                            </a>
                                            <?php if($task['trangthai']==0): ?>                             
                                            <a class="link-delete bd-rd-rem" onclick='showModalcancel(<?php echo $nhiemvu?>,<?php echo $matask?>)'>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">										
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">											
                                                        <rect x="0" y="0" width="24" height="24"></rect>											
                                                        <path class="icon-delete-vector-first" d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill-rule="nonzero">
                                                        </path>											
                                                        <path class="icon-delete-vector-last" d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000">
                                                        </path>					
                                                    </g>									
                                                </svg>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                        <?php $nhanvien = []?> 
                                    </tr>
                                    <?php endforeach;?>
                                </table>
                            </div>
                            <ul class="list-user-page">
                                <div>
                                    <li class="arrow-back-list-user">
                                        <a <?php if($page == 1)
                                                    echo "";
                                                else
                                                    echo "href=./listtask.php?page=1";               
                                            ?>>
                                            <div><i class="fas fa-angle-double-left"></i></div>
                                        </a>
                                    </li>
                                    <li class="arrow-back-list-user">
                                        <a <?php if($previous<1)
                                                echo "";
                                            else
                                                echo "href=./listtask.php?page=".$previous;  
                                        ?>>
                                            <div><i class="fas fa-angle-left"></i></div>
                                        </a>
                                    </li>
                                    <?php 
                                    for($i=1;$i<=$pages;$i++) : ?>
                                    <li class="number-page-list-user">
                                    <a <?php if($i==$page)
                                                echo "";
                                            else
                                                echo "href=./listtask.php?page=".$i;  
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php endfor; ?>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($next>$pages)
                                                echo "";
                                            else
                                                echo "href=./listtask.php?page=".$next;               
                                        ?>>
                                        <div><i class="fas fa-angle-right"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($page == $pages || $pages==0)
                                                echo ""; 
                                            else
                                                echo "href=./listtask.php?page=".$pages;               
                                        ?>>
                                        <div><i class="fas fa-angle-double-right"></i></div>
                                    </a>
                                    </li>
                                </div>
                                <div class="show-total-page">
                                    <span class="dis-bl-mobile">Đang xem trang<?=$page?></span>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->
    </div>

    <div class="modal-task-user js-modal-task-user">
        <div class="modal-add-task-user">
            <div class="modal-close-edit" onclick="closeModaltask()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h2 class="add-user-title">Danh sách nhân viên phòng ban <?=$sophongban?></h2>
            <span id = "nhiemvu" class="title-task-list-user">Nhiệm vụ : Đồ án</span>
            <form method="POST">
                <div class="table-data-list-task js-table-data-list-task">
                    <div class="list-management-user">
                        <table class="user-table">
                            <tr class="title-table">
                                <th>Họ tên</th>
                                <th class="dis-bl-mobile">Chức vụ</th>
                                <th class="dis-bl dis-bl-mobile">Phòng ban</th>
                            </tr>
                            <tbody id = "table-task" >
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="btn-infor-save-task">
                    <div class="btn-no-save-task">
                        <button type="button" onclick="closeModaltask()">Hủy bỏ</button>
                    </div>
                    <div class="btn-yes-save-task">
                        <button type="submit" onclick="luutask()">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-delete-user js-modal-delete-user">
        <div class="modal-delete">
            <h4>Bạn muốn hủy bỏ nhiệm vụ</h4>
            <h6 id="modal-nv">Đồ án</h6>
            <div class="btn-infor-save">
                <div class="btn-no-infor">
                    <button type="button" onclick="closeModalDeleteuser()">Hủy</button>
                </div>
                <div class="btn-yes-infor">
                    <button onclick="cancelnv()" type="button">Xác nhận</button>
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