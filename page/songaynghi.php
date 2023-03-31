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

    $ngaybatdau = '';
    $ngayketthuc = '';
    $tongngaynghi = '';
    $lydo = '';


    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $ngaybatdau = $_POST['start'];
        $ngayketthuc = $_POST['end'];
        $tongngaynghi = $_POST['tongngay'];
        $lydo = $_POST['lydo'];


        date_default_timezone_set("asia/ho_chi_minh");
        $ngaytao = date("Y-m-d\TH:i");
        
        $mysqli = open_database();
        $username  = $_SESSION['username'];
        
        if(isset($_FILES['tep']) && !empty($_FILES['tep']['name']))
        {
            
            $idtask = lastrowtask();

            if($idtask == false)
            {
                exit;
            }
            else
            {
                $idtask = 'TASK'.$idtask.'.'.$FileType;
            }
            move_uploaded_file($file_tmp,"../files/nopdon/".$idtask);
        }
        else
        {
            $file_name ='';
        }
        
        $nguoiphanhoi = '';
    
        if(user($username)['role'] == '2')
        {
            $pb = user($username)['id_phongban'];
            $tp = "SELECT * from user where id_role = '1' and id_PhongBan = '".$pb."'";
            $qrtp = mysqli_query($mysqli,$tp);
    
            if($qrtp->num_rows > 0)
            {
                while($row = $qrtp->fetch_assoc()) {
                    $nguoiphanhoi = $row['username'];
                }
            }
        }
        else if(user($username)['role'] == '1')
        {
            $gd = "SELECT * from user where id_role = '0'";
            $qrtp = mysqli_query($mysqli,$gd);
    
            if($qrtp->num_rows > 0)
            {
                while($row = $qrtp->fetch_assoc()) {
                    $nguoiphanhoi = $row['username'];
                }
            }
        }


        $file_name = '';

        if(isset($_FILES['tep']))
        {
            $file_name = $_FILES['tep']['name'];
            $file_size =$_FILES['tep']['size'];
            $file_tmp =$_FILES['tep']['tmp_name'];
            $file_type=$_FILES['tep']['type'];
            $FileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

            $idnn = lastrownopdon();

            if($idnn == false)
            {
                exit;
            }
            else
            {
                $idnn = 'DON'.$idnn.'.'.$FileType;
            }
            move_uploaded_file($file_tmp,"../files/nopdon/".$idnn);
        }
        else
        {
            $file_name ='';
        }
        

        $sql = "INSERT INTO ngaynghi(username,nguoiphanhoi,ngaybatdau,ngayketthuc,tongngaynghi,ngaytao,mota,tepdinhkem,active)
        VALUES ('$username','$nguoiphanhoi','$ngaybatdau','$ngayketthuc','$tongngaynghi','$ngaytao','$lydo','$file_name','1')";
        
        $query = mysqli_query($mysqli,$sql);
        
        $update = "UPDATE ngaynghi set id_nn = CONCAT('DN', LPAD(id, 4, '0')) where username = '$username'";
        if($query)
        {
            $qrl = mysqli_query($mysqli,$update);
            header("Location: listngaynghi.php?checkn=1");
        }

        mysqli_close($mysqli);
        
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
                <div class="see-detail-task">
                    <div class="see-detail-infor-task dis-flex">
                        <div>
                            <div class="task-title-detail">
                                <span>Thông tin ngày nghỉ</span>
                            </div>
                            <div class="content-task-detail">
                                <div class="infor-task">
                                <?php 
                                        if($_SESSION['username'] != 'default')
                                        {
                                            $nopdon = false;
                                            $mysqli = open_database();
                                            $active = '';
                                            if(user($_SESSION['username'])['role'] == '1')
                                            {
                                                $select = ("SELECT *from ngaynghi where username = '".$_SESSION['username']."'");
                                        
                                                $tongngay = 0;
                                                $songaysudung = 0;
                                                $querysele = mysqli_query($mysqli,$select);
                                                if($querysele->num_rows > 0)
                                                {
                                                    while($row = $querysele->fetch_assoc()) {
                                                        $tongngay = 15;
                                                        $songaysudung += $row['tongngaynghi'];
                                                        if($songaysudung == 15)
                                                        {
                                                            $active = 0;
                                                        }
                                                        else
                                                        {
                                                            $active = $row['active'];
                                                        }

                                                        if($row['trangthai'] == 2)
                                                        {
                                                            $songaysudung -= $row['tongngaynghi'];
                                                        }

                                                        $songayconlai = $tongngay - (int)$songaysudung;
                                                        $ngayphanhoi = explode('T',$row['ngayphanhoi'])[0];
                                                        $ngaynopdon = date('Y-m-d', strtotime($ngayphanhoi. ' + 7 days'));
                                                        $currentday =  date('Y-m-d');
                                                        if($currentday ==  $ngaynopdon)
                                                        {
                                                            $nopdon = true;
                                                        }

                                                    }
                                                }
                                                else
                                                {
                                                    $tongngay = 15;
                                                    $songaysudung = 0;
                                                    $songayconlai = $tongngay - $songaysudung;
                                                }
                                            }
                                            else if(user($_SESSION['username'])['role'] == '2')
                                            {
                                                $select = ("SELECT * from ngaynghi where username = '".$_SESSION['username']."'");
                                        
                                                $tongngay = 0;
                                                $songaysudung = 0;
                                                $querysele = mysqli_query($mysqli,$select);
                                                if($querysele->num_rows > 0)
                                                {
                                                    while($row = $querysele->fetch_assoc()) {
                                                        $tongngay = 12;
                                                        $songaysudung += $row['tongngaynghi'];
                                                        if($songaysudung == 12)
                                                        {
                                                            $active = 1;
                                                        }
                                                        else
                                                        {
                                                            $active = $row['active'];
                                                        }
                                                        if($row['trangthai'] == 2)
                                                        {
                                                            $songaysudung -= $row['tongngaynghi'];
                                                        }
                                                        
                                                        $songayconlai = $tongngay - (int)$songaysudung;
                                                        $ngayphanhoi = explode('T',$row['ngayphanhoi'])[0];
                                                        $ngaynopdon = date('Y-m-d', strtotime($ngayphanhoi. ' + 7 days'));
                                                        $currentday =  date('Y-m-d');
                                                        if($currentday == $ngaynopdon)
                                                        {
                                                            $nopdon = true;
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $tongngay = 12;
                                                    $songaysudung = 0;
                                                    $songayconlai = $tongngay - $songaysudung;
                                                }
                                            }
                                        }
                                    ?>
                                    <?php if($_SESSION['username'] != 'default'):?>
                                        <div class="infor-task-title">
                                            <label>Tổng số ngày nghỉ:</label>
                                            <span><?=$tongngay?></span>
                                        </div>
                                        <div class="infor-task-title">
                                            <label>Số ngày đã sử dụng:</label>
                                            <span><?=$songaysudung?></span>
                                        </div>
                                        <div class="infor-task-title">
                                            <label>Số ngày còn lại:</label>
                                            <span id = "songayconlai" value = "<?=$songayconlai?>"><?=$songayconlai?></span>
                                        </div>
                                        <div class="btn-infor-date">
                                            <?php if($active == '0' && $nopdon && $songayconlai > '0' || $active == ''):?>
                                                <div class="btn-date">
                                                    <button type="button" onclick="showModalNd()">Nộp đơn</button>
                                                </div>
                                            <?php else:?>
                                                <div class="btn-date">
                                                    
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
    </div>

    <div class="modal-delete-user js-modal-no-task">
        <div class="modal-delete">
            <div class="modal-close-edit" onclick="closeModalNd()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h4 class="pd-top mr-bottom" id = "user" value = "user<?=user($_SESSION['username'])['role']?>">Yêu cầu nghỉ phép</h4>
            <form id="submit" method="POST" enctype="multipart/form-data">
                <div class="form-input-add">
                    <label>Ngày bắt đầu</label>
                    <input class="bd-rd-rem date-addtask" id="start" value="<?=$ngaybatdau?>" name="start" type="date" >
                </div>
                <div id = "error-ngaybatdau" class="alert alert-danger error-ngaybatdau" style="display:none">
                </div>
                <div class="form-input-add">
                    <label>Ngày kết thúc</label>
                    <input class="bd-rd-rem date-addtask" id="end" value="<?=$ngayketthuc?>" name="end" type="date" >
                </div>
                <div id = "error-ngayketthuc" class="alert alert-danger error-ngayketthuc" style="display:none">
                </div>
                <div class="form-input-add">
                    <label>Tổng số ngày nghỉ</label>
                    <input readonly class="bd-rd-rem date-addtask" name = "tongngay" id="tongngay" type="number" value = "<?=$tongngaynghi?>">
                </div>
                <div id = "error-tongngaynghi "class="alert alert-danger error-tongngaynghi" style="display:none"></div>
                <div class="form-input-add">
                    <label>Lý do</label>
                    <textarea class="textarea-pb" id="lydo" name = "lydo" type="text" value = "<?=$lydo?>" placeholder="Lý do xin nghỉ"></textarea>
                </div>
                <div id = "error-lydo" class="alert alert-danger error-lydo" style="display:none">
                </div>
                <div class="form-input-file">
                    <label>Đính kèm (nếu có)</label>
                    <div class="custom-file">
                        <input name="tep" type="file" class="custom-file-input js-limit-word" id="document" maxlength="20">
                        <label class="custom-file-label" for="document">Choose file</label>
                    </div>
                </div>
                <div class = "form-group">
                    <div class="progress" style = "display: none;height: 12px;">
                        <div id = "progress-bar" class="progress-bar bg-success" style="width:0%;border-radius: 6px;"></div>
                    </div> 
                </div>
                <div class="form-group">
                    <div class="alert alert-danger" id = "error" style = "display: none;"></div>
                </div>
                <div class="btn-infor-save">
                    <div class="btn-no-infor">
                        <button type="button" onclick="closeModalNd()">Hủy</button>
                    </div>
                    <div class="btn-yes-evaluate">
                        <button onclick = "upload()" type="button">Gửi</button>
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