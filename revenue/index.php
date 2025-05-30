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
  <title>Revenue - fuchsia Media Group
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
  <link rel="stylesheet" href="/assets/css/scroll-bar.css" />
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
        <div class="row">
          <!-- Left Column - Chart -->
          <div class="col-md-8 mb-3"> <!-- Added responsive column width and spacing -->
            <div class="card h-100"> <!-- Added h-100 for equal height -->
              <div class="card-header">
                <i class="zmdi zmdi-chart"></i> Your Revenue
              </div>
              <div class="card-body">
                <div class="chart-container-1" style="position: relative; height: 40vh; min-height: 300px;">
                  <canvas id="chart1"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Cards Stack -->
          <div class="col-md-4"> <!-- Added responsive column width -->
            <div class="card mb-3"> <!-- Added margin-bottom -->
              <div class="card-header">
                <i class="zmdi zmdi-money-box"></i> Account Balance
              </div>
              <div class="card-body">
                <h1 class="mb-3"><i class="zmdi zmdi-money"></i> 0.000</h1> <!-- Added margin-bottom -->
                <div class="small text"> <!-- Added styling for secondary text -->
                  <p class="mb-1">You are currently not eligible to withdraw!</p>
                  <p class="mb-0">Last transaction: never</p>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <i class="zmdi zmdi-receipt"></i> Type an amount...
              </div>
              <div class="card-body">
                <div class="form-group">
                  <input type="text" class="form-control" name="albumtitle" placeholder="Withdrawal amount in USD"
                    value="0">
                </div>
                <button type="submit" class="btn btn-light px-5 w-100" id="withdraw" disabled>
                  <i class="zmdi zmdi-plus-square"></i> Withdraw!
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header"><i class="zmdi zmdi-format-list-bulleted"></i> Transaction History</div>
              <div class="card-body">
                <?php
                //
                // Transaction list
                //
                  if(true){
                    echo '<center>Nothing here yet...</center>';
                  } else {
                    //Show history here
                  }
                ?>
              </div>
            </div>
          </div>
        </div>
        <!--start overlay-->
        <div class="overlay toggle-menu"></div>
        <!--end overlay-->
      </div>
      <!-- End container-fluid-->
      <!--End content-wrapper-->
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
    <script src="/assets/js/revenue.js"></script>

    <!-- Index js -->
    <script type="text/javascript">
      n = new Date();
      document.getElementById("cccccyear").innerHTML = n.getFullYear();
    </script>

</body>

</html>