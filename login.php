<?php

include 'header.php';
include 'contents.php';
error_reporting(0);
$sms=$pass=$email="";
if(isset($_POST['save'])){
  session_start();
  $pass=$_POST['password'];
  $email=$_POST['email'];

        //email
        if(empty($email)){
          $sms="Enter Email Please";
          
        }
          else{
            $email=$_POST['email'];
                  //password
            if(empty($pass)){
              $sms="Enter Password Please";
            }
              else{
                $pass=$_POST['password'];
                $re=mysqli_query($shop,"SELECT roleid,email,password FROM users where email='$email'");
                if(mysqli_num_rows($re)>0){
                  $rows= mysqli_fetch_array($re);
                  $pwd=$rows['password'];
                  if(password_verify($pass,$pwd)){
                      
                    $_SESSION=$rows;
                    $id=$rows['roleid'];
                    if($id==1){
                      header("location: Admin/index.php");
                      exit;
                }
                else{
                    header("location: shop.php");
                    exit;
                }
                  }else{
              
                    $sms="Wrong Password";
                  }
              

                }else{
                  $sms="Wrong Username";
                 
                }
                
              }
          }
}

?>
<!doctype html>
<html lang="en">
<?php headers(); ?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shoppers &mdash; <?php echo $shopname;?></title>
  <link rel="shortcut icon" type="image/png" href="images/logos/favicon.png" />
  <link rel="stylesheet" href="css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                   <strong><?php echo $shopname;?></strong>
                </a>
                <p class="text-center">Login Form</p>
                <form method="Post">
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remeber this Device
                      </label>
                    </div>
                    <a class="text-primary fw-bold" href="./index.php">Forgot Password ?</a>
                  </div>
                  <label class="form-label" style="color:red"><?php echo $sms; ?></label>
                  <input type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" value="Sign In" name="save">
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">No <?php echo $shopname;?> Account?</p>
                    <a class="text-primary fw-bold ms-2" href="./register.php">Create an account</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="libs/jquery/dist/jquery.min.js"></script>
  <script src="libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>