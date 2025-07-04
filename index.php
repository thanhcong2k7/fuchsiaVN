<?php
session_start();
require 'assets/variables/sql.php';
if (!isset($_SESSION["userwtf"])) {
  //unset($_SESSION["userwtf"]);
  header("Location: /login/");
} else {
  $user = getUser($_SESSION["userwtf"]);
  $release = getRelease($_SESSION["userwtf"]);
  if (!$user) {
    unset($_SESSION["userwtf"]);
    echo "<script>window.location.href='./login/';</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Dashboard of a Vietnamese indie distributor." />
  <meta name="author" content="fuchsia Media Group" />
  <title>Dashboard - fuchsia Media Group</title>
  <!-- loader-->
  <link href="assets/css/pace.min.css" rel="stylesheet" />
  <script src="assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
  <!-- Vector CSS -->
  <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
  <!-- simplebar CSS-->
  <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <!-- Bootstrap core CSS-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <!-- animate CSS-->
  <link href="assets/css/animate.css" rel="stylesheet" type="text/css" />
  <!-- Icons CSS-->
  <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
  <!-- Sidebar CSS-->
  <link href="assets/css/sidebar-menu.css" rel="stylesheet" />
  <!-- Custom Style-->
  <link href="assets/css/app-style.css" rel="stylesheet" />
  <!-- Bootstrap core JavaScript-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="/assets/css/scroll-bar.css" />
</head>

<body class="bg-theme bg-theme1">
  <!-- loader scripts -->
  <script src="assets/js/jquery.loading-indicator.js"></script>
  <!-- Start wrapper-->
  <div id="wrapper">

    <!--Start sidebar-wrapper-->
    <?php include 'components/sidebar.php'; ?>
    <!--End sidebar-wrapper-->

    <!--Start topbar header-->
    <?php include 'components/topbar.php'; ?>
    <!--End topbar header-->

    <div class="clearfix"></div>

    <div class="content-wrapper">
      <div class="container-fluid">

        <!--Start Dashboard Content-->

        <div class="card mt-3">
          <div class="card-content">
            <div class="row row-group m-0">
              <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0">It is <span id="codepro-hour"></span>
                    <span class="float-right"><i class="zmdi zmdi-time"></i></span>
                  </h5>
                  <div class="progress my-3" style="height:3px;">
                    <div class="progress-bar" style="width:100%"></div>
                  </div>
                  <p class="mb-0 text-white small-font">
                    <span id="codepro-date" class="text-m"></span>
                    <span class="float-right">GMT+<span id="timediffvn"></span></span>
                  </p>
                </div>
              </div>
              <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <?php
                  $cnt = count($release);
                  $draftcnt = 0;
                  foreach ($release as &$rel)
                    if ($rel->status == 0)
                      $draftcnt++;
                  ?>
                  <h5 class="text-white mb-0">Drafts: <?php echo $draftcnt; ?><span class="float-right"><i
                        class="zmdi zmdi-album"></i></span></h5>
                  <div class="progress my-3" style="height:3px;">
                    <div class="progress-bar" style="width:100%"></div>
                  </div>
                  <p class="mb-0 text-white small-font">Total Releases <span
                      class="float-right"><?php echo $cnt; ?></span></p>
                </div>
              </div>
              <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0">0,000 USD <span class="float-right"><i class="fa fa-usd"></i></span>
                  </h5>
                  <div class="progress my-3" style="height:3px;">
                    <div class="progress-bar" style="width:<?php echo (round(0 / 50 * 100)) ?>%"></div>
                  </div>
                  <a class="mb-0 text-white small-font" href="/revenue/">Go to Revenue <span class="float-right"><i
                        class="zmdi zmdi-long-arrow-right"></i></span></a>
                </div>
              </div>
              <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0">0 <span class="float-right"><i class="fa fa-eye"></i></span></h5>
                  <div class="progress my-3" style="height:3px;">
                    <div class="progress-bar" style="width:100%"></div>
                  </div>
                  <p class="mb-0 text-white small-font">Total Stream <span class="float-right">+0% <i
                        class="zmdi zmdi-long-arrow-up"></i></span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-lg-8 col-xl-8">
            <div class="card">
              <div class="card-header"><i class="zmdi zmdi-format-list-bulleted"></i> Stream Statistics
                <div class="card-action">
                  <div class="dropdown">
                    <a href="analytics/" class="dropdown-toggle dropdown-toggle-nocaret">
                      <i class="zmdi zmdi-long-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <ul class="list-inline">
                  <li class="list-inline-item"><i class="fa fa-circle mr-2 text-white"></i>Estimated Income</li>
                  <li class="list-inline-item"><i class="fa fa-circle mr-2 text-light"></i>Streams</li>
                </ul>
                <div class="chart-container-1">
                  <canvas id="chart1"></canvas>
                </div>
              </div>
              <div class="row m-0 row-group text-center border-top border-light-3">
                <div class="col-12 col-lg-4">
                  <div class="p-3">
                    <h5 class="mb-0">0</h5>
                    <small class="mb-0">New Releases <span> <i class="fa fa-arrow-up"></i> 0%</span></small>
                  </div>
                </div>
                <div class="col-12 col-lg-4">
                  <div class="p-3">
                    <h5 class="mb-0">0</h5>
                    <small class="mb-0">Last Month Stream <span> <i class="fa fa-arrow-up"></i> 0%</span></small>
                  </div>
                </div>
                <div class="col-12 col-lg-4">
                  <div class="p-3">
                    <h5 class="mb-0">0</h5>
                    <small class="mb-0">Last Month Revenue <span> <i class="fa fa-arrow-up"></i> 0%</span></small>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 col-xl-4">
            <div class="card">
              <div class="card-header"><i class="zmdi zmdi-balance-wallet"></i> Monthly Revenue
                <div class="card-action">
                  <div class="dropdown">
                    <a href="revenue/" class="dropdown-toggle dropdown-toggle-nocaret">
                      <i class="zmdi zmdi-long-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="chart-container-2">
                  <canvas id="chart2"></canvas>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table align-items-center">
                  <tbody>
                    <tr>
                      <td><i class="fa fa-circle text-white mr-2"></i>YouTube</td>
                      <td>5,856</td>
                      <td>$0.23</td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-circle text-light-1 mr-2"></i>Spotify</td>
                      <td>2,602</td>
                      <td>$21.3</td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-circle text-light-2 mr-2"></i>Zing MP3</td>
                      <td>1,802</td>
                      <td>$0.013</td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-circle text-light-3 mr-2"></i>YT Music</td>
                      <td>1,105</td>
                      <td>$0.12</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div><!--End Row-->

        <div class="row">
          <div class="col-12 col-lg-12">
            <div class="card">
              <div class="card-header"><i class="zmdi zmdi-album"></i> Recent 5 releases
                <div class="card-action">
                  <div class="dropdown">
                    <a href="discography/" class="text dropdown-toggle dropdown-toggle-nocaret">
                      <i class="zmdi zmdi-long-arrow-right"></i>
                    </a>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table align-items-center table-flush table-borderless">
                    <thead>
                      <tr>
                        <th>Release Name</th>
                        <th>Artwork</th>
                        <th>UPC</th>
                        <th>Status</th>
                        <th>Release date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $r = getRelease($_SESSION["userwtf"], 5);
                      foreach ($r as &$re) {
                        echo '
                            <tr>
                              <td>' . ($re->name ? $re->name : "(untitled)") . '</td>
                              <td><img src="' . (!isset($re->artp) ? '/assets/images/alb.png' : $re->artp) . '" class="product-img" alt="product img" loading="eager"></td>
                              <td>FMG' . $re->id . '</td>
                              <td>' . ($re->status == 0 ? "DRAFT" : ($re->status == 1 ? "DELIVERED" : ($re->status == 2 ? "ERROR" : "CHECKING"))) . '</td>
                              <td>' . ($re->relDate ? $re->relDate : "--/--/----") . '</td>
                              <td>
                                <a href="/discography/edit.php?id=' . $re->id . '">Edit</a> / 
			                          <a class="text-error" href="/discography/edit.php?id=' . $re->id . '&delete=1">Delete</a>
                              </td>
                            </tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!--End Row-->

            <!--End Dashboard Content-->

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
      <script src="/assets/plugins/simplebar/js/simplebar.js"></script>
      <!-- sidebar-menu js -->
      <script src="/assets/js/sidebar-menu.js"></script>
      <!-- Custom scripts -->
      <script src="/assets/js/app-script.js"></script>
      <!-- Chart js -->
      <script src="/assets/plugins/Chart.js/Chart.min.js"></script>
      <!-- Index js -->
      <script src="/assets/js/index.js"></script>

      <script type="text/javascript">
        var myVar = setInterval(function () {
          myTimer()
        }, 1000);
        function myTimer() {
          var d = new Date();
          var t = d.toLocaleTimeString();
          hr = d.getHours();
          mn = d.getMinutes();
          document.getElementById("codepro-hour").innerHTML = (hr < 10 ? "0" : "") + hr + ":" + (mn < 10 ? "0" : "") + mn;
        }
        n = new Date();
        if (n.getTimezoneOffset() == 0) t = n.getTime() + (7 * 60 * 60 * 1000);
        else t = n.getTime();
        n.setTime(t);
        var dn = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
        d = n.getDay();
        m = n.getMonth() + 1;
        y = n.getFullYear();
        var date = dn[d] + " " + (n.getDate() < 10 ? "0" : "") + n.getDate() + "/" + (m < 10 ? "0" : "") + m + "/" + y;
        document.getElementById("codepro-date").innerHTML = date;
        document.getElementById("timediffvn").innerHTML = Math.abs(n.getTimezoneOffset()) / 60;
        document.getElementById("cccccyear").innerHTML = n.getFullYear();
      </script>

</body>

</html>