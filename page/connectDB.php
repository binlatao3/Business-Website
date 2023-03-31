<?php
function is_cmnd_exits($cmnd){
  $sql = "select * from user where cmnd = '$cmnd'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function is_sdt_exits($sdt){
  $sql = "select * from user where sdt = '$sdt'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
} 

function is_lichsu_exits($id){
  $sql = "select * from lichsu where id_lichsu = '$id'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
} 

function open_database(){
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_sql = "cuoiky";
  
    $conn  = new mysqli($servername,$username,$password,$db_sql);
  
    if ($conn  -> connect_errno) {
      die('Connect error:' . $conn  -> connect_error);
    }
    return $conn ;
  }

function verify_password($user, $pass)
{
    $sql = "select * from user where username = '$user'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();
      $hashed_password = $data['password'];
      
      if(!password_verify($pass, $hashed_password))
      {
        return array('code'=>2,'error'=>'Sai mật khẩu');
      }
      else 
        return array('code'=>0,'error'=>'');
    }
    else
    {
        return array('code'=>1,'error'=>'Tài khoản không tồn tại');
    }
    mysqli_close($conn);
}

// function validate($user)
// {
//   $conn = open_database();
//   $sql1 = "SELECT * FROM user  WHERE username = '$user'";
//   $result1 = $conn->query($sql1);
//   if ($result1->num_rows > 0) {
//     while($row1 = $result1->fetch_assoc()) {
//       $achid = $row1['username'];
//       $sql2 = "SELECT * FROM role WHERE username = '$achid'";
//       $result2 = $conn->query($sql2);
//       if ($result2->num_rows > 0) {
//               while($row2 = $result2->fetch_assoc()) {
//                   return $row2['id_permisson'];
//               }
//       }
//     }
//   }
//   mysqli_close($conn);
// }

function user($user)
{
  $sql = "select * from user where username = '$user'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_user'=>$data['id_user'],'fullname'=>$data['fullname'],'role'=>$data['id_role'],'username'=>$data['username'],
      'password'=>$data['password'],'cmnd'=>$data['cmnd'],'sdt'=>$data['sdt'],'email'=>$data['email'],'address'=>$data['address'],
      'id_phongban'=>$data['id_PhongBan'],'active'=>$data['active'],'img'=>$data['img']);
    }
    mysqli_close($conn);
}

function traodoi($id)
{
  $sql = "select * from tasktraodoi where id_traodoi = '$id'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_traodoi'=>$data['id_traodoi'],'id_task'=>$data['id_task'],'tieude'=>$data['tieude'],
      'mota'=>$data['mota'],'username'=>$data['username'],'nguoigiao'=>$data['nguoigiao'],'tepdinhkem'=>$data['tepdinhkem']);
    }
    mysqli_close($conn);
}

function id($id)
{
  $sql = "select * from user where id_user = '$id'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_user'=>$data['id_user'],'fullname'=>$data['fullname'],'role'=>$data['id_role'],'username'=>$data['username'],
      'password'=>$data['password'],'cmnd'=>$data['cmnd'],'sdt'=>$data['sdt'],'email'=>$data['email'],'address'=>$data['address'],
      'id_phongban'=>$data['id_PhongBan'],'active'=>$data['active']);
    }
    mysqli_close($conn);
}

function task($id)
{
  $sql = "select * from task user where id_task = '$id'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_task'=>$data['id_task'],'nhiemvu'=>$data['nhiemvu'],'username'=>$data['username'],'nguoigiao'=>$data['nguoigiao'],
                    'mota' =>$data['mota'],'danhgia'=>$data['danhgia'],'giahan'=>$data['giahan'],'tepdinhkem'=>$data['tepdinhkem'],
                    'trangthai'=>$data['trangthai'],'ngaygiao'=>$data['ngaygiao']);
    }
    mysqli_close($conn);
}

function lichsu($id)
{
  $sql = "select * from lichsu user where id_lichsu = '$id'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_task'=>$data['id_task'],'id_lichsu'=>$data['id_lichsu'],
      'id_traodoi'=>$data['id_traodoi'],'trangthai'=>$data['trangthai'],'ngay'=>$data['ngay']);
    }
    mysqli_close($conn);
}

function getData($user)
{
  $sql = "select * from user where username = '$user'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return $data;
    }
    mysqli_close($conn);
}

function is_username_exits($username){
  $sql = "select * from user where username = '$username'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}
function check_user($user)
{
  $sql = "select * from user where username = '$user'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      return array('code'=>0,'error'=>'');

    }
    else
    {
        return array('code'=>1,'error'=>'Tài khoản không tồn tại');
    }
    mysqli_close($conn);
}

function is_pb_exits($mapb){
  $sql = "select * from phongban where id_Phongban = '$mapb'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function is_id_pb_exits($id){
  $sql = "select * from phongban where id = '$id'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function is_id_task_exits($id){
  $sql = "select * from task where id_task = '$id'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function is_email_exits($email){
  $sql = "select * from user where email = '$email'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);

  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function is_password_change($username){
  $sql = "select active from user where username = '$username'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);

  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
    if(is_username_exits($username))
    {
      $result = $stm->get_result();
      if($result->num_rows > 0){
          while($row = $result->fetch_assoc()) {

            return array('code'=>0,'active'=>$row['active']);
        }
      }
    }
    else{
      return array('code'=>1,'error'=>'Tài khoản không tồn tại');
    }
}

function active_account($username){
  $sql = "update user set active = 1 where username = '$username' and active = 0";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);

  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  return array('code'=>0,'message'=>'Update thành công');
  mysqli_close($mysqli);
}

function update_password($user,$pass)
{
    
    $encryptpassword = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "update user set password = '$encryptpassword' where username = '$user'";
    $conn = open_database();

    $stm = $conn->prepare($sql);

    if(!$stm->execute()){
      die('Query error: '.$stm->error);
    }
    mysqli_close($conn);
}

function lastrowtask(){
  $sql = "SELECT `AUTO_INCREMENT`
        FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'cuoiky'
        AND   TABLE_NAME   = 'task';";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
     $data = $result->fetch_assoc();
     return $data['AUTO_INCREMENT'];     
  }
  else{
    return false;
  }
}

function lastrowtraodoi(){
  $sql = "SELECT `AUTO_INCREMENT`
        FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'cuoiky'
        AND   TABLE_NAME   = 'tasktraodoi';";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
     $data = $result->fetch_assoc();
     return $data['AUTO_INCREMENT'];     
  }
  else{
    return false;
  }
}

function dataphongban($username)
{
  $sql = "select * from phongban where username = '$username'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id'=>$data['id'],'id_PhongBan'=>$data['id_PhongBan'],'id_role'=>$data['id_role'],
      'username'=>$data['username'],'Ten_phongban'=>$data['Ten_phongban'],'mota'=>$data['mota'])                           ;
    }
    mysqli_close($conn);
}

function realdate($ngay){
  $temp= explode("T", $ngay);
  $ngaynop= date("d/m/Y", strtotime($temp[0]));
  return $ngaynop;

}
function realtime($gio)
{
   return explode("T", $gio)[1];
}

function lastrownopdon(){
  $sql = "SELECT `AUTO_INCREMENT`
        FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'cuoiky'
        AND   TABLE_NAME   = 'ngaynghi';";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
     $data = $result->fetch_assoc();
     return $data['AUTO_INCREMENT'];     
  }
  else{
    return false;
  }
}
function getInfDon($nn)
{
  $sql = "select * from ngaynghi where id_nn = '$nn'";
  $conn = open_database();
  $query = mysqli_query($conn,$sql);

  if(mysqli_num_rows($query) >=1)
  {
    $stm = $conn->prepare($sql);
    if (!$stm->execute()) {
      return null;
    }

    $result = $stm->get_result();

    $data = $result->fetch_assoc();

    return array('id_nn'=>$data['id_nn'],'nguoiphanhoi'=>$data['nguoiphanhoi'],'ngaybatdau'=>$data['ngaybatdau'],
    'ngayketthuc'=>$data['ngayketthuc'],'tongngaynghi'=>$data['tongngaynghi'],'ngaytao'=>$data['ngaytao'],
    'ngayphanhoi'=>$data['ngayphanhoi'],'trangthai'=>$data['trangthai'],'mota'=>$data['mota'],
    'tepdinhkem'=>$data['tepdinhkem'],'active'=>$data['active']);
  }
  mysqli_close($conn);
}

?>