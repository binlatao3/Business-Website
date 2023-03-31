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
if(!isset($_GET['id']))
{
    header("Location: listphongban.php");
}
else
{
    $id = $_GET['id'];
    if(is_id_pb_exits($id) ==false)
    {
        header("Location: listphongban.php");
    }
    $conn = open_database();
    if(mysqli_query($conn,"SELECT * from phongban where id = '$id'"))
    {
        $count= mysqli_query($conn,"SELECT * from phongban where id = '$id'");
        $data = $count->fetch_assoc();
    }
    else
    {
        exit;
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
                <div class="see-detail-task">
                    <div class="see-detail-infor-task">
                        <div class="task-title-detail">
                            <span>Thông tin chi tiết phòng ban</span>
                        </div>
                        <div class="content-task-detail">
                            <div class="infor-task">
                                <div class="infor-task-title">
                                    <label>Số phòng ban:</label>
                                    <span><?=$data['id_PhongBan']?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Phòng ban:</label>
                                    <span><?=$data['Ten_phongban']?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Trưởng phòng hiện tại:</label>
                                    <span>
                                    <?php 
                                      if($data['username']!=null)
                                      {
                                         echo user($data['username'])['fullname'];
                                      }
                                      else
                                      {
                                          echo "Chưa Có";
                                      }
                                    ?>
                                    </span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Mô tả phòng ban:</label>
                                    <span><?=$data['mota']?></span>
                                </div>
                            </div>
                        </div>
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