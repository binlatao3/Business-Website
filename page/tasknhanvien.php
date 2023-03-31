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

    $conn = open_database();
    $limit = 10;
    $page = isset($_GET['page'])? $_GET['page'] : 1;
    $start = ($page -1 ) * $limit;

    if(mysqli_query($conn,"SELECT * from task where username = '".$_SESSION['username']."' and trangthai != 2 ORDER BY id_task DESC LIMIT $start, $limit"))
    {
        $count= mysqli_query($conn,"SELECT * from task where username = '".$_SESSION['username']."' and trangthai != 2 ORDER BY id_task DESC LIMIT $start, $limit");
        $data = $count->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        exit;
    }

    $count= mysqli_query($conn,"SELECT count(*) as total from task");
    $total = $count->fetch_assoc();
    $pages = ceil($total['total']/$limit);
    $previous = $page - 1;
    $next = $page + 1;
    mysqli_close($conn);

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
                    <div class="list-header">
                        <div class="header-title">
                            <h4>Danh sách nhiệm vụ</h4>
                        </div>
                    </div>
                    <div class="header-content-table">
                        <div class="table-data-list">
                            <div class="list-management-user">
                                <table class="user-table">
                                    <tr class="title-table">
                                        <th>Nhiệm vụ</th>
                                        <th class="dis-bl-mobile">Người giao</th>
                                        <th class="dis-bl-mobile">Trạng thái</th>
                                        <th class="dis-bl dis-bl-mobile">Mô tả</th>
                                        <th class="dis-bl dis-bl-mobile">Đánh giá</th>
                                        <th>Chức năng</td>
                                    </tr>
                                    <?php foreach($data as $task): ?>
                                    <tr class="title-content">
                                        <td class="list-task-title"><?=$task['nhiemvu']?></td>
                                        <td class="list-task-name-user dis-bl-mobile"><?=user($task['nguoigiao'])['fullname']?></td>
                                        <!-- list-task-status-new -->
                                        <!-- list-task-status-inprogress -->
                                        <!-- list-task-status-canceled -->
                                        <!-- list-task-status-waiting -->
                                        <!-- list-task-status-rejected -->
                                        <!-- list-task-status-completed -->
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
                                        <td class="ev-task dis-bl dis-bl-mobile"><?=$task['danhgia']?></td>
                                        <td class="see-edit-delete">
                                            <?php 
                                                $link = "./chitiettasknv.php?idtask=".$task['id_task']
                                            ?>
                                            <a href=<?= $link?> class="link-see bd-rd-rem">
                                                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="eye" class="svg-inline--fa fa-eye fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19.5px" height="19.5px">
                                                    <path class="icon-see-vector-only" fill="currentColor" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                </svg>
                                            </a>
                                            <?php 
                                                $link = "./taskhistory.php?idtask=".$task['id_task'];
                                            ?>
                                            <a href="<?=$link?>" class="link-change-password bd-rd-rem"> 
                                                <svg  viewBox="0 0 48 48" width="24px" height="24px" xmlns="http://www.w3.org/2000/svg">
                                                    <path  d="M0 0h48v48h-48z" fill="none"/>
                                                    <path  class="icon-change-vector-last" d="M25.99 6c-9.95 0-17.99 8.06-17.99 18h-6l7.79 7.79.14.29 8.07-8.08h-6c0-7.73 6.27-14 14-14s14 6.27 14 14-6.27 14-14 14c-3.87 0-7.36-1.58-9.89-4.11l-2.83 2.83c3.25 3.26 7.74 5.28 12.71 5.28 9.95 0 18.01-8.06 18.01-18s-8.06-18-18.01-18zm-1.99 10v10l8.56 5.08 1.44-2.43-7-4.15v-8.5h-3z" opacity="1"/>
                                                </svg>
                                            </a>
                                        </td>
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
                                                    echo "href=./tasknhanvien.php?page=1";               
                                            ?>>
                                            <div><i class="fas fa-angle-double-left"></i></div>
                                        </a>
                                    </li>
                                    <li class="arrow-back-list-user">
                                        <a <?php if($previous<1)
                                                echo "";
                                            else
                                                echo "href=./tasknhanvien.php?page=".$previous;  
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
                                                echo "href=./tasknhanvien.php?page=".$i;  
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
                                                echo "href=./tasknhanvien.php?page=".$next;               
                                        ?>>
                                        <div><i class="fas fa-angle-right"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($page == $pages || $pages==0)
                                                echo "";
                                            else
                                                echo "href=./tasknhanvien.php?page=".$pages;               
                                        ?>>
                                        <div><i class="fas fa-angle-double-right"></i></div>
                                    </a>
                                    </li>
                                </div>
                                <div class="show-total-page">
                                    <span class="dis-bl-mobile">Đang xem trang <?=$page?></span>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->
    </div>


    <div class="modal-edit-user js-modal-edit-user">
        <div class="modal-edit-detail js-modal-edit-detail">
                <div class="btn-edit">
                    <div class="btn-save-edit">
                        <button type="button" onclick="showModalSaveInfor()">Lưu</button>
                    </div>
                    <div class="modal-save-infor js-modal-save-infor">
                        <div class="modal-save"> 
                            <h4>Bạn muốn thay đổi thông tin của </h4>
                            <h6>Lương Minh Quang</h6>
                            <div class="btn-infor-save">
                                <div class="btn-no-infor">
                                    <button type="button" onclick="closeModalSaveinfor()">Hủy bỏ</button>
                                </div>
                                <div class="btn-yes-infor">
                                    <button type="submit">Đồng ý</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-delete-user js-modal-delete-user">
        <div class="modal-delete">
            <h4>Bạn muốn hủy bỏ nhiệm vụ</h4>
            <h6>Đồ án</h6>
            <div class="btn-infor-save">
                <div class="btn-no-infor">
                    <button type="button" onclick="closeModalDeleteuser()">Hủy</button>
                </div>
                <div class="btn-yes-infor">
                    <button type="button">Xác nhận</button>
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