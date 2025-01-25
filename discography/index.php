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
  <link href="/assets/css/pace.min.css" rel="stylesheet" />
  <script src="/assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
  <!-- Vector CSS -->
  <link href="/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
  <!-- simplebar CSS-->
  <link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <!-- Bootstrap core CSS-->
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
  <!-- animate CSS-->
  <link href="/assets/css/animate.css" rel="stylesheet" type="text/css" />
  <!-- Icons CSS-->
  <link href="/assets/css/icons.css" rel="stylesheet" type="text/css" />
  <!-- Sidebar CSS-->
  <link href="/assets/css/sidebar-menu.css" rel="stylesheet" />
  <!-- Custom Style-->
  <link href="/assets/css/app-style.css" rel="stylesheet" />
  <!-- Bootstrap core JavaScript-->
  <script src="/assets/js/jquery.min.js"></script>
  <script src="/assets/js/popper.min.js"></script>
  <script src="/assets/js/bootstrap.min.js"></script>
</head>

<body class="bg-theme bg-theme1">
  <!-- loader scripts -->
  <script src="/assets/js/jquery.loading-indicator.js"></script>
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

        <!--Start Dashboard Content-->
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header">
                <i class="zmdi zmdi-album"></i> Your Discography
                <div class="card-action">
                  <a href="edit.php?new=1" class="text dropdown-toggle dropdown-toggle-nocaret">
                    <i class="zmdi zmdi-collection-plus"></i> Create new album
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover align-items-center table-flush table-borderless">
                    <thead>
                      <tr>
                        <th>Art</th>
                        <th>UPC</th>
                        <th>Release Name</th>
                        <th>Artist</th>
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
                          $mergedArtistnames = "";
                          $f = getFile($r->id);
                          $r2 = getRelease($_SESSION["userwtf"], 0, $r->id);
                          $track = array();
                          foreach ($r2->file as &$adu) {
                            foreach ($f as &$f2) {
                              if ($f2->id == $adu) {
                                $t = getTrack($f2->id);
                                $track[] = $t;
                                $artist = getArtist($t->id);
                                $n = 0;
                                foreach ($artist as &$adu) {
                                  if ($mergedArtistnames) {
                                    $n++;
                                  } else
                                    $mergedArtistnames .= $adu->name;
                                }
                                $mergedArtistnames .= " & " . $n . " more";
                              }
                            }
                          }
                          echo '
                          <tr>
                            <td><img loading="eager" src="' . (!isset($r->art) || ($r->art == "") ? '/assets/images/alb.png' : $r->art) . '" class="product-img" alt="product img"></td>
                            <td>' . ($r->upc ? $r->upc : "--") . '</td>
                            <td>' . ($r->name ? $r->name : "(untitled)") . '</td>
                            <td>' . ($mergedArtistnames ? $mergedArtistnames : "(none)") . '</td>
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
  <script src="/assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- sidebar-menu js -->
  <script src="/assets/js/sidebar-menu.js"></script>
  <!-- Custom scripts -->
  <script src="/assets/js/app-script.js"></script>
  <!-- Chart js -->

  <script src="/assets/plugins/Chart.js/Chart.min.js"></script>

  <!-- Index js -->
  <script type="text/javascript">
    n = new Date();
    document.getElementById("cccccyear").innerHTML = n.getFullYear();
  </script>

</body>

</html>