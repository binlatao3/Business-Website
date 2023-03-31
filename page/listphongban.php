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

    $conn = open_database();
    $limit = 10;
    $page = isset($_GET['page'])? $_GET['page'] : 1;
    $del_pb = isset($_GET['delid'])? $_GET['delid'] : null;
    $start = ($page -1 ) * $limit;
    $userinfor = [];
    $alertset = false;
    $alertup = false;
    $alertdoitp = false;
    $nv_id = isset($_GET['value'])? $_GET['value'] : null;
    $pb_id = isset($_GET['id'])? $_GET['id'] : null;
    
    $sopb = isset($_GET['sopb'])? $_GET['sopb'] : null;
    $tenpb = isset($_GET['tenpb'])? $_GET['tenpb'] : null;
    $mota = isset($_GET['mota'])? $_GET['mota'] : null;
    $idup = isset($_GET['idpb'])? $_GET['idpb'] : null;
    
if($sopb &&  $tenpb && $mota && $idup)
{
   
   $sql1 = "UPDATE user
   SET id_PhongBan = '$sopb'
   WHERE id_PhongBan='$idup'";

   $sql2 = "UPDATE phongban
   SET id_PhongBan = '$sopb', Ten_phongban = '$tenpb', mota = '$mota'
   WHERE id_PhongBan='$idup'";

   $update1 = mysqli_query($conn,$sql1);
   $update2 = mysqli_query($conn,$sql2);
    if($update1 && $update2)
    { 
         $alertup = true;
    }
}


if($pb_id && $nv_id)
{
    $setql = "select * FROM user WHERE id_user = '$nv_id'";
    $nv = mysqli_query($conn,$setql);
    while($row = $nv->fetch_assoc())
    {
        $pb = "SELECT username,id_role from phongban WHERE `id_PhongBan` = '".$row['id_PhongBan']."'";
        $query = mysqli_query($conn,$pb);

        if($query->num_rows > 0)
        {
            while($row2 = $query->fetch_assoc())
            {
                if(is_null($row2['username']) && is_null($row2['id_role']))
                {
                    $update =  "UPDATE phongban SET `id_role` = '1',`username` = '".$row['username']."' WHERE id_PhongBan = '".$row['id_PhongBan']."'";
                    
                    $updaterole =  "UPDATE user SET `id_role` = '1' WHERE `username` = '".$row['username']."'";
                    
                    if(mysqli_query($conn,$update))
                    {
                        mysqli_query($conn,$updaterole);
                        $alertset = true;
                    }
                }
                else
                {
                    $update =  "UPDATE phongban SET `id_role` = '1',`username` = '".$row['username']."' WHERE id_PhongBan = '".$row['id_PhongBan']."'";

                    $updaterole =  "UPDATE user SET `id_role` = '1' WHERE `username` = '".$row['username']."'";
    
                    $updateusername =  "UPDATE phongban SET `username` = '".$row['username']."' WHERE `id_PhongBan` = '".$row['id_PhongBan']."'";
        
                    $updatenvuser = "UPDATE user SET `id_role` = '2' where `username` != '".$row['username']."' and id_PhongBan = '".$row['id_PhongBan']."'";
                
                    if(mysqli_query($conn,$update))
                    {
                        mysqli_query($conn,$updaterole);
                        mysqli_query($conn,$updatenvuser);
                        mysqli_query($conn,$updateusername);
                        $alertdoitp = true;
                    }
                }
            }
        }
    } 
    echo "<script>console.log('$nv_id')</script>";
    
}




if(mysqli_query($conn,"SELECT * from phongban LIMIT $start, $limit"))
{
    $count= mysqli_query($conn,"SELECT * from phongban LIMIT $start, $limit");
    $data = $count->fetch_all(MYSQLI_ASSOC);
}
else
{
    exit;
}

$count= mysqli_query($conn,"SELECT count(*) as total from phongban");
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
    <link rel="stylesheet" href="../style.css">
    <title>Danh sách phòng ban</title>
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
                       if($alertset)
                       {
                           echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                           <strong>Thành công!</strong> Thêm trưởng phòng thành công!   
                           <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                           <span aria-hidden='true'>&times;</span>
                           </button>
                           </div>";
                           echo "<script type='text/javascript'>
                           var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?page=".$page."'; 
                           window.history.pushState({ path: refresh }, '', refresh);
                           </script>";
                       }
                       if($alertdoitp)
                       {
                           echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                           <strong>Thành công!</strong> Đổi trưởng phòng thành công!   
                           <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                           <span aria-hidden='true'>&times;</span>
                           </button>
                           </div>";
                           echo "<script type='text/javascript'>
                           var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?page=".$page."'; 
                           window.history.pushState({ path: refresh }, '', refresh);
                           </script>";
                       } 

                        if($alertup)
                        {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong> Update phòng ban thành công!   
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
                            <h4>Danh sách phòng ban<h6>- <?= $total['total']?></h6></h4>
                        </div>
                        <div class="header-choose">
                            <a href="addphongban.php" class="user-add" style="text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path class="icon-adduser-vector-last" d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z" fill="#000000"></path>
                                        <rect class="icon-adduser-vector-first" fill="#000000" opacity="1" x="2" y="4" width="5" height="16" rx="1"></rect>
                                    </g>
                                </svg>
                                <span class="dis-bl-mobile">Thêm phòng ban</span>
                            </a>
                        </div>
                    </div>
                    <div class="header-content-table">
                        <div class="table-data-list">
                            <div class="list-management-user">
                                <table class="user-table">
                                    <tr class="title-table">
                                        <th>Mã phòng ban</th>
                                        <th class="dis-bl-mobile">Tên phòng ban</th>
                                        <th class="dis-bl-mobile">Trưởng phòng</th>
                                        <th>Chức năng</th>
                                    </tr>
                                    <?php 
                                    foreach($data as $pb) : ?>
                                    <tr class="title-content">
                                        <td><?=$pb['id_PhongBan'] ?></td>
                                        <td class="dis-bl-mobile"><?=$pb['Ten_phongban'] ?></td>
                                        <td class="dis-bl-mobile">
                                            <?php 
                                                if($pb['username']!=null)
                                                {
                                                    echo user($pb['username'])['fullname'];
                                                }
                                                else
                                                {
                                                    echo "Chưa Có";
                                                }
                                            ?>
                                        </td>
                                        <td class="see-edit-delete">
                                            <a href="./seedetailpb.php?id=<?=$pb['id']?>" class="link-see bd-rd-rem">
                                                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="eye" class="svg-inline--fa fa-eye fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19.5px" height="19.5px">
                                                    <path class="icon-see-vector-only" fill="currentColor" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                </svg>
                                            </a>
                                             <?php
                                               $param2 = json_encode(array('id'=>$pb['id'],'id_PhongBan'=>$pb['id_PhongBan'],'tenpb'=>$pb['Ten_phongban'],'mota'=>$pb['mota']));
                                            ?>
                                            <a  class="link-edit bd-rd-rem" onclick='showModalEditPb(<?php echo $param2?>)'>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">										
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">											
                                                        <rect x="0" y="0" width="24" height="24"></rect>											
                                                        <path class="icon-edit-vector-first" d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) " opacity="0.9"></path>											
                                                        <path class="icon-edit-vector-last" d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="1"></path>										
                                                    </g>									
                                                </svg>
                                            </a>
                                            <?php 
                                                $conn = open_database();
                                                $temp = $pb['id_PhongBan'];
                                                $user= mysqli_query($conn,"SELECT user.*,phongban.Ten_phongban from user inner JOIN phongban using(id_PhongBan) where user.id_PhongBan = '$temp'"); 
                                                if($user)
                                                {
                                                    while($row = $user->fetch_assoc()) {
                                                            array_push($userinfor,$row);
                                                    } 
                                                }
                                                $param = json_encode($userinfor);
                                                mysqli_close($conn);
                                            ?>        
                                            <a  class="link-change-password bd-rd-rem" onclick='showModalifpb(<?php echo $param?>)'> 
                                            <?php $userinfor = []?>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect class="icon-change-vector-last" fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
                                                        <rect class="icon-change-vector-last" fill="#000000" opacity="1" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
                                                    </g>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <ul class="list-user-page">
                                <div>
                                <li class="arrow-back-list-user">
                                    <a <?php if($page == 1)
                                                echo "";
                                            else
                                                echo "href=./listphongban.php?page=1";               
                                        ?>>
                                        <div><i class="fas fa-angle-double-left"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-back-list-user">
                                        <a <?php if($previous<1)
                                                echo "";
                                            else
                                                echo "href=./listphongban.php?page=".$previous;  
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
                                                echo "href=./listphongban.php?page=".$i;  
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
                                                echo "href=./listphongban.php?page=".$next;               
                                        ?>>
                                        <div><i class="fas fa-angle-right"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($page == $pages || $pages==0)
                                                echo "";
                                            else
                                                echo "href=./listphongban.php?page=".$pages;               
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


    <div class="modal-edit-pb js-modal-edit-pb">
        <div class="modal-edit-pb-bg">
            <div class="modal-close-edit" onclick="closeModalEditpb()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h2 id="modal-tenpb" class="add-user-title"></h2>
            <form>
                <div class="form-input-add">
                    <label>Số phòng ban</label>
                    <input class="bd-rd-rem" id="sopb" name="name" type="text" placeholder="Số phòng ban">
                </div>

                <div class=" alert alert-danger " id="error-so" style="display:none" >
                        Vui Lòng Nhập Số Phòng Ban
                </div>
                <div class=" alert alert-danger " id ="error-sotontai" style="display:none" >
                        Số Phòng Ban đã tồn tại
                </div>

                <div class="form-input-add">
                    <label>Tên phòng ban</label>
                    <input class="bd-rd-rem" id="tenpb" type="text" placeholder="Tên phòng ban">

                </div>

                <div class=" alert alert-danger " id ="error-ten" style="display:none">
                        Vui Lòng Nhập Tên Phòng Ban
                </div>

                <div class="form-input-add textarea-pb-label">
                    <label class="title-textarea-pb">Mô tả</label>
                    <textarea class="bd-rd-rem textarea-pb" id="motapb" type="text" placeholder="Mô tả phòng ban" re></textarea>
                </div>

                <div class=" alert alert-danger" id ="error-mota" style="display:none" >
                        Vui Lòng Nhập Mô tả Phòng Ban
                </div>
                <div class="btn-pb">
                    <div class="btn-no-pb">
                        <button type="button" onclick="closeModalEditpb()">Hủy</button>
                    </div>
                    <div class="btn-yes-pb">
                        <button type="button" onclick='modalluu(<?php echo json_encode($data) ?>)' type="button">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-task-user js-modal-task-user">
        <div class="modal-add-task-user">
            <div class="modal-close-edit" onclick="closeModalifpb()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h2 id = "modal-tenpbNV"class="add-user-title"></h2>
            <span id="truongphong" class="title-task-list-user"></span>
            <form method="POST">
                <div class="table-data-list-task js-table-data-list-task">
                    <div class="list-management-user">
                        <table class="user-table">
                            <tr class="title-table">
                                <th class="dis-bl dis-bl-mobile">id</th>
                                <th>Họ tên</th>
                                <th>Chức vụ</th>
                            </tr>
                            <tbody id = "table-pb" >
                            </tbody>
                        </table> 
                    </div>
                </div>
                <div class="btn-infor-save-task">
                    <div class="btn-no-save-task">
                        <button type="button" onclick="closeModalifpb()">Hủy bỏ</button>
                    </div>
                    <div class="btn-yes-save-task">
                        <button onclick="AddTruongPhong()" type="button">Lưu</button>
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