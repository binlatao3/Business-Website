<?php 

    session_start(); 
    require_once "./connectDB.php"; 

    if(isset($_SESSION['username']))
    {
        header("Location: ../index.php");
        exit;
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
    <title>Trang chủ</title>
</head>

<body>
<?php 

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);


    $error = '';
    $username = '';
    $password = '';

    if(isset($_POST['username'])  && isset($_POST['password']) && isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(empty($username))
        {
            $error = 'Hãy nhập tài khoản';
        }
        else if(empty($password))
        {
            $error = 'Hãy nhập mật khẩu';
        }
        else if($username === 'default' && $password === '123456')
        {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            header("Location: ../index.php");
            exit;
        }
        else 
        {
            $data = verify_password($username, $password);
            if($data['code'] === 1)
            {
                $error = $data['error'];
            }
            else if($data['code'] === 2)
            {
                $error = $data['error'];
            }
            else
            {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                header("Location: ../index.php");
                exit;
            }
        }
    }

    // if(isset($_POST['login'])){
    //     if($_POST['username'] == 'admin' && $_POST['password'] == '123456'){
    //         $_SESSION['username'] = $_POST['username'];
    //         header("Location: home.php");
    //         exit;
    //     }
    // }

?>
    <div class="section-login">
        <div class="login-logo">
            <div class="logo-company">
                <img src="../images/bg3.png" alt="">
                <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit</h3>
            </div>
            <div class="bg-login">
                <img src="../images/bg1.svg" alt="">
            </div>
        </div>

        <div class="login-input">
            <div class="form-user">
                <span>
                    <!-- <img src="./bg3.png" alt=""> -->
                    <h2>Members</h2>
                </span>
                <form method="POST" action="login.php">
                    <div class="form-input">
                        <label for="username">Tên đăng nhập</label>
                        <input class="bd-rd-rem" id="username" type="text" placeholder="Full Name"  value="<?= $username ?>" name="username">
                        <div class="error-username"></div>
                    </div>
                    <div class="form-input">
                        <label for="password">Mật Khẩu</label>
                        <input class="bd-rd-rem" id="password" type="password" placeholder="Password" value="<?= $password ?>" name="password">
                        <div class="error-password"></div>
                    </div>
                    <div class="form-group">
                        <?php
                            if(!empty($error)){
                                echo '<div class="alert alert-danger">'. $error .'</div>';
                            }
                        ?>
                        <button name="login" class="btn-login bd-rd-rem">Sign In</button>
                    </div>
                </form>
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
