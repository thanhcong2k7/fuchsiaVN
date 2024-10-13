<?php
session_start();
if (!isset($_SESSION["userwtf"]))
  header("Location: ../login/");
else {
  require '../assets/variables/sql.php';
  $user = getUser($_SESSION["userwtf"]);
  $release = getRelease($_SESSION["userwtf"]);
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
  <title>Discography - fuchsia Media Group</title>
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
  <!-- loader scripts -->
  <script src="../assets/js/jquery.loading-indicator.js"></script>
  <!-- Start wrapper-->
  <div id="wrapper">

    <!--Start sidebar-wrapper-->
    <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
      <div class="brand-logo">
        <a href="index.html">
          <img src="../assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
          <h5 class="logo-text">fuchsia Partner</h5>
        </a>
      </div>
      <ul class="sidebar-menu do-nicescrol">
        <li class="sidebar-header">MAIN MENU</li>
        <li>
          <a href="../">
            <i class="zmdi zmdi-view-dashboard"></i> <span>Homepage</span>
          </a>
        </li>

        <li>
          <a href="">
            <i class="zmdi zmdi-album"></i> <span>Discography</span>
          </a>
        </li>

        <li>
          <a href="../analytics/">
            <i class="zmdi zmdi-format-list-bulleted"></i> <span>Analytics</span>
          </a>
        </li>

        <li>
          <a href="../revenue/">
            <i class="zmdi zmdi-balance-wallet"></i> <span>Revenue</span>
          </a>
        </li>

        <li>
          <a href="../settings/">
            <i class="zmdi zmdi-assignment-account"></i> <span>Your account</span>
          </a>
        </li>
        <li class="sidebar-header">TOOLBOX</li>
        <li><a href="manager/artist/"><i class="zmdi zmdi-accounts text-warning"></i> <span>Artists</span></a></li>
        <li><a href="../manager/tracks/"><i class="zmdi zmdi-audio text-success"></i> <span>Tracks</span></a></li>
        <li><a href="ticket/"><i class="zmdi zmdi-tag text-info"></i> <span>Support</span></a></li>
        <li><a href="../login/login.php?logout=yes"><i class="zmdi zmdi-run text-danger"></i> <span>Log out?</span></a>
        </li>
      </ul>

    </div>
    <!--End sidebar-wrapper-->

    <!--Start topbar header-->
    <header class="topbar-nav">
      <nav class="navbar navbar-expand fixed-top">
        <ul class="navbar-nav mr-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link toggle-menu" href="javascript:void();">
              <i class="icon-menu menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <form class="search-bar">
              <input type="text" class="form-control" placeholder="Find releases">
              <a href="javascript:void();"><i class="icon-magnifier"></i></a>
            </form>
          </li>
        </ul>

        <ul class="navbar-nav align-items-center right-nav-link">
          <li class="nav-item dropdown-lg">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown"
              href="javascript:void();">
              <i class="fa fa-envelope-open-o"></i></a>
          </li>
          <li class="nav-item dropdown-lg">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown"
              href="javascript:void();">
              <i class="fa fa-bell-o"></i></a>
          </li>
          <li class="nav-item">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
              <span class="user-profile"><img src="../assets/images/gallery/ava_sample.png" class="img-circle"
                  alt="user avatar"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="dropdown-item user-details">
                <a href="javaScript:void();">
                  <div class="media">
                    <div class="avatar"><img class="align-self-start mr-3" src="../assets/images/gallery/ava_sample.png"
                        alt="user avatar"></div>
                    <div class="media-body">
                      <h6 class="mt-2 user-title"><?php echo $user->display; ?></h6>
                      <p class="user-subtitle"><?php echo $user->email; ?></p>
                    </div>
                  </div>
                </a>
              </li>
              <li class="dropdown-divider"></li>
              <li class="dropdown-item"><i class="icon-wallet mr-2"></i> Account</li>
              <li class="dropdown-divider"></li>
              <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
              <li class="dropdown-divider"></li>
              <a class="dropdown-item" href="login/login.php?logout=yes"><i class="icon-power mr-2"></i> Logout</a>
            </ul>
          </li>
        </ul>
      </nav>
    </header>
    <!--End topbar header-->

    <div class="clearfix"></div>

    <div class="content-wrapper">
      <div class="container-fluid">

        <!--Start Dashboard Content-->
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Your Discography</h5>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>UPC</th>
                        <th>Release Name</th>
                        <th>Artwork</th>
                        <th>Status</th>
                        <th>Release date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?
                      if ($release == null)
                        echo 'Error occurred while fetching data from server. Please create a ticket about this and try again later...';
                      else
                        foreach ($release as &$r) {
                          echo '
				<tr>
                    <td>' . ($r->id < 10 ? "0" . $r->id : $r->id) . '</td>
		    <td>' . ($r->upc ? $r->upc : "--") . '</td>
                    <td>' . ($r->name ? $r->name : "(draft)") . '</td>
                    <td><img src="' . (!isset($r->art) ? 'https://via.placeholder.com/50x50' : $r->art) . '" class="product-img" alt="' . $r->name . ' Artwork"></td>
                    <td class="text' . ($r->status == 0 ? "" : ($r->status == 1 ? "-success" : ($r->status == 2 ? "-error" : "-info"))) . '">
		    ' . ($r->status == 0 ? "DRAFT" : ($r->status == 1 ? "DELIVERED" : ($r->status == 2 ? "ERROR" : "CHECKING"))) . '
		    </td>
                    <td>' . ($r->relDate ? $r->relDate : "--/--/----") . '</td>
		    <td>
			<a href="edit.php?id=' . $r->id . '">Edit</a> / 
			<a class="text-error" href="edit.php?id=' . $r->id . '&delete=1">Delete</a>
		    </td>
                  </tr>';
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
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
  <script src="../assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- sidebar-menu js -->
  <script src="../assets/js/sidebar-menu.js"></script>
  <!-- Custom scripts -->
  <script src="../assets/js/app-script.js"></script>
  <!-- Chart js -->

  <script src="../assets/plugins/Chart.js/Chart.min.js"></script>

  <!-- Index js -->
  <script src="../assets/js/index.js"></script>
  <script type="text/javascript">
    n = new Date();
    document.getElementById("cccccyear").innerHTML = n.getFullYear();
  </script>

</body>

</html>