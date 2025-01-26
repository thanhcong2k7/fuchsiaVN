<?php
session_start();
if (!isset($_SESSION["userwtf"]))
  header("Location: ../login/");
else {
  require '../assets/variables/sql.php';
  $user = getUser($_SESSION["userwtf"]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Update Profile - fuchsia Media Group
  </title>
  <!-- loader-->
  <link href="../assets/css/pace.min.css" rel="stylesheet" />
  <script src="../assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
  <!-- Vector CSS -->
  <link href="../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
  <!-- simplebar CSS-->
  <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <!-- Bootstrap core CSS-->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <!-- animate CSS-->
  <link href="../assets/css/animate.css" rel="stylesheet" type="text/css" />
  <!-- Icons CSS-->
  <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
  <!-- Sidebar CSS-->
  <link href="../assets/css/sidebar-menu.css" rel="stylesheet" />
  <!-- Custom Style-->
  <link href="../assets/css/app-style.css" rel="stylesheet" />
  <!-- Bootstrap core JavaScript-->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/popper.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
</head>

<body class="bg-theme bg-theme1">

  <!-- start loader -->
  <script src="../assets/js/jquery.loading-indicator.js"></script>
  <!-- Start wrapper-->
  <div id="wrapper">

    <!--Start sidebar-wrapper-->
    <?php include '../components/sidebar.php'; ?>
    <!--End sidebar-wrapper-->

    <!--Start topbar header-->
    <?php include '../components/topbar.php'; ?>
    <!--End topbar header-->

    <div class="clearfix"></div>

    <div class="content-wrapper">
      <div class="container-fluid">

        <div class="row mt-3">
          <div class="col-lg-4">
            <div class="card profile-card-2">
              <div class="card-img-block">
                <img class="img-fluid" src="/assets/images/cover.jpg" alt="Background" style="border-radius:8px;" loading="eager">
              </div>
              <div class="card-body pt-5">
                <img src="<?php echo $user->avatar; ?>" alt="profile-image" class="profile">
                <h5 class="card-title"><?php echo $user->name; ?>
                  <p class="card-text">Display: <?php echo $user->display; ?></p>
                </h5>
                <div class="icon-block">
                  <a href="javascript:void();"><i class="fa fa-facebook bg-facebook text-white"></i></a>
                  <a href="javascript:void();"> <i class="fa fa-twitter bg-twitter text-white"></i></a>
                  <a href="javascript:void();"> <i class="fa fa-google-plus bg-google-plus text-white"></i></a>
                </div>
              </div>
              <div class="card-body border-top border-light">
                <div class="media align-items-center">
                  <div>
                    <img src="../assets/images/timeline/paypal.png" class="skill-img" alt="skill img">
                  </div>
                  <div class="media-body text-left ml-3">
                    <div class="progress-wrapper">
                      <p>HTML5 <span class="float-right">65%</span></p>
                      <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width:65%"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="card">
              <div class="card-header">
                <i class="zmdi zmdi-account-box-mail"></i> Edit your profile information
              </div>
              <div class="card-body">
                <style>
                  .alert {
                    padding: 15px;
                    border-color: #6DD134;
                    border-radius: 5px;
                    border-width: 2px;
                    color: white;
                  }
                </style>
                <div class="alert row callout" role="alert" style="overflow: hidden;white-space: initial;">
                  <span>
                    <i class="zmdi zmdi-info-outline text-warning">
                    </i> <strong>Note:</strong> Updating payment info will be added soon!
                  </span>
                </div>
                <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified" hidden>
                  <li class="nav-item">
                    <a href="javascript:void();" data-target="#edit" data-toggle="pill" class="nav-link" id="ngonnnn"><i
                        class="icon-note"></i> <span class="hidden-xs">Edit</span></a>
                  </li>
                </ul>
                <div class="tab-content p-3">
                  <div class="tab-pane" id="edit">
                    <form action="profile.php" method="POST" enctype='multipart/form-data'>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Full name</label>
                        <div class="col-lg-9">
                          <input class="form-control" type="text" name="fullname" value="<?php echo $user->name; ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Display name</label>
                        <div class="col-lg-9">
                          <input class="form-control" type="text" name="displayname"
                            value="<?php echo $user->display; ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Email</label>
                        <div class="col-lg-9">
                          <input class="form-control" type="email" value="<?php echo $user->email; ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Change avatar</label>
                        <div class="col-lg-9">
                          <input class="form-control" type="file" name="avt" id="avt">
                        </div>
                      </div>
                      <canvas id="cvs" hidden></canvas>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">New Password</label>
                        <div class="col-lg-9">
                          <input class="form-control" name="pwd" id="pwd" minlength="8" type="password" value="">
                        </div>
                      </div>
                      <hr class="mt-1 mb-1" />
                      <br>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Paypal email</label>
                        <div class="col-lg-9">
                          <input class="form-control" type="email" value="" placeholder="Optional, can be added later.">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label"></label>
                        <div class="col-lg-9">
                          <input type="submit" class="btn btn-primary" id="sub" value="Save Changes">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <script>
                  document.getElementById("ngonnnn").click();
                </script>
              </div>
            </div>
          </div>

        </div>

        <!--start overlay-->
        <div class="overlay toggle-menu"></div>
        <!--end overlay-->

      </div>
      <!-- End container-fluid-->

    </div><!--End content-wrapper-->
    <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->

    <!--Start footer-->
    <footer class="footer">
      <div class="container">
        <div class="text-center">
          Copyright © <span id="cccccyear">year</span> fuchsia Media Group.
        </div>
      </div>
    </footer>
    <!--End footer-->

    <!--start color switcher-->
    <div class="right-sidebar">
      <div class="switcher-icon">
        <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
      </div>
      <div class="right-sidebar-content">

        <p class="mb-0">Gaussion Texture</p>
        <hr>

        <ul class="switcher">
          <li id="theme1"></li>
          <li id="theme2"></li>
          <li id="theme3"></li>
          <li id="theme4"></li>
          <li id="theme5"></li>
          <li id="theme6"></li>
        </ul>

        <p class="mb-0">Gradient Background</p>
        <hr>

        <ul class="switcher">
          <li id="theme7"></li>
          <li id="theme8"></li>
          <li id="theme9"></li>
          <li id="theme10"></li>
          <li id="theme11"></li>
          <li id="theme12"></li>
          <li id="theme13"></li>
          <li id="theme14"></li>
          <li id="theme15"></li>
        </ul>

      </div>
    </div>
    <!--end color switcher-->

  </div><!--End wrapper-->

  <!-- simplebar js -->
  <script src="../assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- sidebar-menu js -->
  <script src="../assets/js/sidebar-menu.js"></script>
  <!-- Custom scripts -->
  <script src="../assets/js/app-script.js"></script>
  <!-- Chart js -->

  <script src="../assets/plugins/Chart.js/Chart.min.js"></script>
  <script type="text/javascript">
    n = new Date();
    document.getElementById("cccccyear").innerHTML = n.getFullYear();
  </script>

</body>

</html>