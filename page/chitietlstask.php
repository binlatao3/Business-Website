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
                    document.querySelector('.list-items-task .taskhistory').style.display = 'block';
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
    if(!isset($_GET['idlichsu']))
    {
        header("Location: taskhistory.php");
        exit;
    }

    $idlichsu = $_GET['idlichsu'];

    if(is_lichsu_exits($idlichsu) == false)
    {
        header("Location: listtask.php");
        exit;
    }
    $trangthai = lichsu($idlichsu)['trangthai'];
    $idtask = lichsu($idlichsu)['id_task'];
    $idtraodoi = lichsu($idlichsu)['id_traodoi'];
    $ngay = lichsu($idlichsu)['ngay'];

    $filetype = '';
    $filetypearr = '';
    if(task($idtask)['tepdinhkem'])
    {
        $filetypearr = explode(".", task($idtask)['tepdinhkem']);
        $filetype = end($filetypearr);
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
                    <div class="see-detail-infor-task dis-flex dis-flex-monile">
                        <div>
                            <div class="task-title-detail">
                                <span>Thông tin chi tiết nhiệm vụ</span>
                            </div>
                            <div class="content-task-detail">
                                <div class="infor-task">
                                    <div class="infor-task-title">
                                        <label>Nhiệm vụ:</label>
                                        <span><?=task($idtask)['nhiemvu']?></span>
                                    </div>
                                    <div class="infor-task-title">
                                    <?php if(user(task($idtask)['username'])):?>
                                        <label>Nhân viên thực hiện:</label>
                                        <span><?=user(task($idtask)['username'])['fullname']?></span>
                                    <?php else:?>
                                        <label>Nhân viên thực hiện:</label>
                                        <span>Không có</span>
                                    <?php endif; ?>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Người giao:</label>
                                        <span><?=user(task($idtask)['nguoigiao'])['fullname']?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Hạn nộp:</label>
                                        <span><?=realdate(task($idtask)['giahan'])?></span>
                                        <span><?="Giờ: ".realtime(task($idtask)['giahan'])?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Ngày giao:</label>
                                        <span><?=realdate(task($idtask)['ngaygiao'])?></span>
                                        <span><?="Giờ: ".realtime(task($idtask)['ngaygiao'])?></span>
                                    </div>
                                    <div class="infor-task-title">
                                        <label>Mô tả:</label>
                                        <span><?=task($idtask)['mota']?></span>
                                    </div>
                                    <div class="infor-task-title infor-task-title-link">
                                        <label>Đính kèm:</label>

                                        <?php if(task($idtask)['tepdinhkem'] ==""): ?>
                                            <span>Không có</span>
                                        <?php else: ?>
                                            <a href=<?="../files/truongphong/"."TASK".task($idtask)['id_task'].".".$filetype ?> download=<?=task($idtask)['tepdinhkem']?>> <span><?=task($idtask)['tepdinhkem']?></span></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mr-bt-10">
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
                        </div>
                    </div>
                </div>
                <?php if($trangthai ==1): ?>
                    <div class="see-detail-task mr-top">
                    <div class="see-detail-infor-task">
                        <div class="task-title-detail">
                            <span>Bắt đầu tiến trình làm task</span>
                        </div>
                        <div class="content-task-detail">
                            <div class="infor-task">
                                <div class="infor-task-title">
                                    <label>Thời gian bắt đầu vào:</label>
                                    <span><?="ngày ".realdate($ngay)?></span>
                                    <span><?=realtime($ngay)." Phút"?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif($trangthai == 2): ?>
                    <div class="see-detail-task mr-top">
                    <div class="see-detail-infor-task">
                        <div class="task-title-detail">
                            <span>Task bị hủy</span>
                        </div>
                        <div class="content-task-detail">
                            <div class="infor-task">
                                <div class="infor-task-title">
                                    <label>Đã hủy vào:</label>
                                    <span><?="ngày ".realdate($ngay)?></span>
                                    <span><?=realtime($ngay)." Phút"?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif($trangthai ==3): ?>
                    <div class="see-detail-task mr-top">
                    <div class="see-detail-infor-task">
                        <div class="task-title-detail">
                            <span>Đợi trưởng phòng duyệt</span>
                        </div>
                        <div class="content-task-detail">
                            <div class="infor-task">
                                <div class="infor-task-title">
                                    <label>Đã nộp vào:</label>
                                    <span><?="ngày ".realdate($ngay)?></span>
                                    <span><?=realtime($ngay)." Phút"?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Tiêu đề: </label>
                                    <span><?=traodoi($idtraodoi)['tieude']?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Mô tả:</label>
                                    <span><span><?=traodoi($idtraodoi)['mota']?></span></span>
                                </div>
                                <div class="infor-task-title infor-task-title-link">
                                    <label>Đính kèm:</label>
                                    <?php
                                            if(traodoi($idtraodoi)['tepdinhkem'])
                                            {
                                                $filetypearr = explode(".", traodoi($idtraodoi)['tepdinhkem']);
                                                $filetype = end($filetypearr);
                                            }
                                        ?>
                                    <?php if(traodoi($idtraodoi)['tepdinhkem']): ?>
                                        <a href=<?="../files/traodoi/"."TASKTD".traodoi($idtraodoi)['id_traodoi'].".".$filetype ?> download=<?=traodoi($idtraodoi)['tepdinhkem']?>> <span><?=traodoi($idtraodoi)['tepdinhkem']?></span></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif($trangthai ==4): ?>
                    <div class="see-detail-task mr-top">
                    <div class="see-detail-infor-task">
                        <div class="task-title-detail">
                            <span>Trưởng phòng từ chối task</span>
                        </div>
                        <div class="content-task-detail">
                            <div class="infor-task">
                                <div class="infor-task-title">
                                    <label>Từ chối vào: </label>
                                    <span><?="ngày ".realdate($ngay)?></span>
                                    <span><?=realtime($ngay)." Phút"?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Tiêu đề:</label>
                                    <span><?=traodoi($idtraodoi)['tieude']?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Nhận xét:</label>
                                    <span><?=traodoi($idtraodoi)['mota']?></span>
                                </div>
                                <div class="infor-task-title infor-task-title-link">
                                    <label>Đính kèm:</label>
                                    <?php if(traodoi($idtraodoi)['tepdinhkem'] ==""): ?>
                                        <span>Không có</span>
                                    <?php else: ?>
                                        <a href=<?="../files/traodoi/"."TASKTD".traodoi($idtraodoi)['id_traodoi'].".".$filetype ?> download=<?=traodoi($idtraodoi)['tepdinhkem']?>> <span><?=traodoi($idtraodoi)['tepdinhkem']?></span></a>
                                    <?php endif; ?>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif($trangthai ==5): ?>
                    <div class="see-detail-task mr-top">
                    <div class="see-detail-infor-task">
                        <div class="task-title-detail">
                            <span>Task đã được hoàn thành</span>
                        </div>
                        <div class="content-task-detail">
                            <div class="infor-task">
                                <div class="infor-task-title">
                                    <label>Thời gian hoàn thành vào:</label>
                                    <span><?="ngày ".realdate($ngay)?></span>
                                    <span><?=realtime($ngay)." Phút"?></span>
                                </div>
                                <div class="infor-task-title">
                                    <label>Đánh giá của trưởng phòng: </label>
                                    <span><?=task($idtask)['danhgia']?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
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