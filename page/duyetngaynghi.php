<?php

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

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

    $conn = open_database();

    $accept_id = isset($_GET['acceptid'])? $_GET['acceptid'] : null;
    $refuse_id = isset($_GET['refuseid'])? $_GET['refuseid'] : null;

    $alertaccept = false;
    $alertrefuse = false;
    date_default_timezone_set("asia/ho_chi_minh");
    $ngaytao = date("Y-m-d\TH:i");

    if($accept_id !=null)
    {
        if(isset($_COOKIE['preventdel']))
        {
            if($_COOKIE['preventdel'] == 1)
            {
                $accept = "UPDATE ngaynghi set ngayphanhoi = '$ngaytao',trangthai = 1,active = 0  WHERE id_nn = '$accept_id'";
                if(mysqli_query($conn,$accept))
                $alertaccept= true;
                setcookie('preventdel','0');
            }
        }    
    }


    if($refuse_id !=null)
    {
        if(isset($_COOKIE['preventres']))
        {
            if($_COOKIE['preventres'] == 1)
            {
                $refuse = "UPDATE ngaynghi set ngayphanhoi = '$ngaytao',trangthai = 2,active = 0  WHERE id_nn = '$refuse_id'";
                if(mysqli_query($conn,$refuse))
                $alertrefuse= true;
                setcookie('preventres','0');
            }
        }    
    }


    $limit = 10;
    $page = isset($_GET['page'])? $_GET['page'] : 1;
    $last = isset($_GET['last'])? $_GET['last'] : 1;
    $start = ($page -1 ) * $limit;
    $donnop = [];

    if(mysqli_query($conn,"SELECT * from ngaynghi where nguoiphanhoi = '".$_SESSION['username']."' ORDER BY id_nn DESC LIMIT $start, $limit"))
    {
        $count= mysqli_query($conn,"SELECT * from ngaynghi where nguoiphanhoi = '".$_SESSION['username']."' ORDER BY id_nn DESC LIMIT $start, $limit");
        $data = $count->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        exit;
    }

    $count= mysqli_query($conn,"SELECT count(*) as total from ngaynghi where nguoiphanhoi = '".$_SESSION['username']."'");
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
                   <?php 
                        if($alertaccept)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong>   Phê duyệt thành công!   
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>";
                            echo "<script type='text/javascript'>
                            var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?page=".$page."'; 
                            window.history.pushState({ path: refresh }, '', refresh);
                            </script>"; 
                        }

                        if($alertrefuse)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong>   Từ chối phê duyệt thành công!   
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
                            <h4>Danh sách ngày nghỉ của nhân viên</h4>
                        </div>
                    </div>
                    <div class="header-content-table">
                        <div class="table-data-list">
                            <div class="list-management-user">
                                <table class="user-table">
                                    <tr class="title-table">
                                        <th>Nhân viên gửi</th>
                                        <th>Ngày nộp</th>
                                        <th class="dis-bl dis-bl-mobile">Ngày trả kết quả</th>
                                        <th class="dis-bl-mobile">Trạng thái</th>
                                        <th>Chức năng</td>
                                    </tr>
                                    <?php foreach($data as $don): ?>
                                    <tr class="title-content">
                                        <td><?=user($don['username'])['fullname']?></td>
                                        <td class="list-task-title"><?=explode('T',$don['ngaytao'])[0]?></td>
                                        <?php if($don['ngayphanhoi']==null): ?>
                                            <td class="list-task-name-user dis-bl dis-bl-mobile">Đang duyệt</td>
                                        <?php else: ?>
                                            <td class="list-task-name-user dis-bl dis-bl-mobile"><?= explode('T',$don['ngayphanhoi'])[0]?></td>
                                        <?php endif; ?>
                                        <!-- list-task-status-waiting -->
                                        <?php
                                            switch ($don['trangthai']) {
                                                case 0:
                                                    $class = "list-task-status-waiting";
                                                break;
                                                case 1:
                                                    $class = "list-task-status-approved";
                                                break;
                                                case 2:
                                                    $class = "list-task-status-refused";
                                                break;   
                                            }
                                        ?>
                                        <td class="<?=$class?> dis-bl-mobile"></td>
                                        <!-- đánh giá -->
                                        <?php
                                            $conn = open_database();
                                            $donxin= mysqli_query($conn,"SELECT * FROM ngaynghi WHERE id_nn = '".$don['id_nn']."'");
                                            if($donxin)
                                            {
                                                while($row = $donxin->fetch_assoc()) {
                                                        array_push($donnop,$row);
                                                } 
                                            }
                                            $fullname = json_encode(user($don['username'])['fullname']);
                                            $param = json_encode($donnop);
                                            mysqli_close($conn);  
                                        ?>
                                        <td class="see-edit-delete">
                                            <a  class="link-see bd-rd-rem" onclick='showModalDDon(<?php echo $param?>,<?php echo $fullname?>)'>
                                                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="eye" class="svg-inline--fa fa-eye fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19.5px" height="19.5px">
                                                    <path class="icon-see-vector-only" fill="currentColor" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                </svg>
                                            </a>
                                        </td>
                                        <?php $donnop = []?>
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
                                                echo "href=./duyetngaynghi.php?page=1";               
                                        ?>>
                                        <div><i class="fas fa-angle-double-left"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-back-list-user">
                                        <a <?php if($previous<1)
                                                echo "";
                                            else
                                                echo "href=./duyetngaynghi.php?page=".$previous;  
                                        ?>>
                                            <div><i class="fas fa-angle-left"></i></div>
                                        </a>
                                    </li>
                                    <?php  if($pages <= 4):  
                                        for($i=1;$i<=$pages;$i++) : ?>
                                    <li class="number-page-list-user">
                                    <a <?php 
                                            echo "href=./duyetngaynghi.php?page=".$i;
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php
                                        endfor; ?>
                                    <?php elseif($pages - $page < 3):
                                        if($pages - $page == 2)
                                        {
                                            $donvi = 1;
                                        }
                                        elseif($pages - $page == 1)
                                        {
                                            $donvi = 2;
                                        }
                                        else
                                        {
                                            $donvi = 3;
                                        }
                                    for($i=$page-$donvi;$i<=$pages;$i++) :
                                    ?>
                                    <li class="number-page-list-user">
                                    <a <?php 
                                            echo "href=./duyetngaynghi.php?page=".$i."&last=".$page;  
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php endfor; ?>
                                    <?php 
                                        elseif($last>$page): 
                                            if($page-3<1)
                                            {
                                                $donvi = 1;
                                                if($page == 2)
                                                {
                                                    $den =$page +2;
                                                }
                                                else if($page == 3)
                                                {
                                                    $den =$page +1;
                                                }
                                                else
                                                {
                                                    $den =$page +3;
                                                }
                                            }
                                            else
                                            {
                                                $donvi = $page-3;
                                                $den = $page;
                                            }
                                        for($i=$donvi;$i<=$den;$i++) : ?>
                                    <li class="number-page-list-user">
                                    <a <?php 
                                            echo "href=./duyetngaynghi.php?page=".$i."&last=".$page; 
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php
                                        endfor; 
                                    ?>
                                     <?php 
                                        elseif($last<$page): 
                                            $donvi = $page+3;
                                        for($i=$page;$i<=$donvi;$i++) : ?>
                                    <li class="number-page-list-user">
                                    <a <?php 
                                            echo "href=./duyetngaynghi.php?page=".$i."&last=".$page; 
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php
                                        endfor; 
                                    ?>
                                    <?php 
                                        elseif($last==$page):
                                            $donvi = $page+1;
                                            $den = $page-2;
                                            if($den<1)
                                            {
                                                $donvi = 4;
                                                $den = 1;
                                            }
                                        for($i=$den;$i<=$donvi;$i++) : ?>
                                    <li class="number-page-list-user">
                                    <a <?php 
                                            echo "href=./duyetngaynghi.php?page=".$i."&last=".$page; 
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php
                                        endfor; 
                                    ?>
                                    <?php else:  
                                        $count = 0;
                                        for($i=$page;$i<=$pages;$i++) : ?>
                                    <li class="number-page-list-user">
                                    <a <?php 
                                            echo "href=./duyetngaynghi.php?page=".$i."&last=".$page; 
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php
                                        $count++;
                                        if($count ==4)
                                            break;
                                        endfor; 
                                    ?>
                                    <?php endif; ?>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($next>$pages)
                                                echo "";
                                            else
                                                echo "href=./duyetngaynghi.php?page=".$next;               
                                        ?>>
                                        <div><i class="fas fa-angle-right"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($page == $pages)
                                                echo "";
                                            else
                                                echo "href=./duyetngaynghi.php?page=".$pages;               
                                        ?>>
                                        <div><i class="fas fa-angle-double-right"></i></div>
                                    </a>
                                    </li>
                                </div>
                                <div class="show-total-page">
                                    <span class="dis-bl-mobile">Đang xem Trang <?= $page?></span>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->
    </div>

    <div class="modal-delete-user js-modal-duyet-user">
        <div class="modal-delete min-wid">
            <div class="modal-close-edit" onclick="closeModalDDon()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h4>Thông tin đơn xin nghỉ</h4>
            <div class="infor-dayoff">
                <label>Người gửi:</label>
                <span id = "nguoigui"></span>
            </div>
            <div class="infor-dayoff">
                <label>Ngày bắt đầu nghỉ:</label>
                <span id = "ngaybatdau"></span>
            </div>
            <div class="infor-dayoff">
                <label>Ngày kết thúc:</label>
                <span id = "ngayketthuc"></span>
            </div>
            <div class="infor-dayoff">
                <label>Tổng ngày nghỉ:</label>
                <span id = "tongsongay"></span>
            </div>
            <div class="infor-dayoff">
                <label>Lý do:</label>
                <span id = "lydo"></span>
            </div>
            <div id = "showfiledon"  class="infor-dayoff dayoff-link" style="display:none">
                <label>Đính kèm:</label>
                <a id='downdon'><span id = "dinhkemdon"></span> </a>
            </div>
            <div id = "status" class="content-task-detail-status">
                <div class="form-input-extend disflex-btn-dayoff">
                    <div class="btn-no-task">
                        <button onclick = "refusedDon()" type="button">Không đồng ý</button>
                    </div>
                    <div class="btn-yes-task">
                        <button onclick = "AcceptedDon()" type="button">Đồng ý</button>
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