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
  <style>
    #viewReleaseModal .modal-content {
      background-color: rgba(0, 0, 0, 0.95);
      color: #fff;
    }

    #viewReleaseModal .modal-header {
      border-bottom: 1px solid #333;
    }

    #viewReleaseModal .modal-footer {
      border-top: 1px solid #333;
    }

    #releaseArt {
      max-width: 250px;
      border: 2px solid #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .loader {
      width: 3rem;
      height: 3rem;
      border-width: 0.25em;
    }
  </style>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
    integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
    crossorigin="anonymous"></script>
  <script src="/assets/js/bootstrap.min.js"></script>
  <script type="module" src="api/app.js"></script>
  <link rel="stylesheet" href="/assets/css/scroll-bar.css" />
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
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($release == null)
                        echo '<center>There\'s nothing here yet...</center>';
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
                          echo '<tr>
                            <td><img loading="eager" style="border-radius:5px; border-style:solid; border-color:white; border-width:1px;" src="' . (!isset($r->artp) || ($r->artp == "") ? '/assets/images/alb.png' : $r->artp) . '" class="product-img" alt="product img"></td>
                            <td>' . ($r->upc ? $r->upc : "--") . '</td>
                            <td>' . ($r->name ? $r->name : "(untitled)") . '</td>
                            <td>' . ($mergedArtistnames ? $mergedArtistnames : "(none)") . '</td>
                            <td class="text' . ($r->status == 0 ? "" : ($r->status == 1 ? "-success" : ($r->status == 2 ? "-error" : "-info"))) . '">
                          ' . ($r->status == 0 ? "DRAFT" : ($r->status == 1 ? "DELIVERED" : ($r->status == 2 ? "ERROR" : "CHECKING"))) . '
                          ';

                          // Check if rejection_reason column exists and if this is a rejected release
                          if ($r->status == 2) {
                            // Check if rejection_reason column exists
                            $columnExists = false;
                            $checkColumnQuery = "SHOW COLUMNS FROM album LIKE 'rejection_reason'";
                            $columnResult = $GLOBALS["conn"]->query($checkColumnQuery);
                            if ($columnResult && $columnResult->num_rows > 0) {
                              $columnExists = true;
                            }

                            // If column exists, check if this release has a rejection reason
                            if ($columnExists) {
                              $sql = "SELECT rejection_reason FROM album WHERE albumID = ?";
                              $stmt = $GLOBALS["conn"]->prepare($sql);
                              if ($stmt) {
                                $stmt->bind_param("i", $r->id);
                                $stmt->execute();
                                $stmt->bind_result($rejectionReason);
                                $stmt->fetch();
                                $stmt->close();

                                if (!empty($rejectionReason)) {
                                  echo ' <i class="zmdi zmdi-info-outline" title="This release was rejected. View details to see the reason." style="cursor: help;"></i>';
                                }
                              }
                            }
                          }

                          echo '
                          </td>
                                      <td>' . ($r->relDate ? $r->relDate : "--/--/----") . '</td>
                          <td>
                            <div class="dropdown">
                              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                Actions
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item view-release" href="#" data-release-id="' . $r->id . '">View release</a>
                                <a class="dropdown-item" href="edit.php?id=' . $r->id . '">Edit release</a>
                                <a class="dropdown-item" href="edit.php?id=' . $r->id . '&delete=1">Delete release</a>
                              </div>
                            </div>
                          </td>
                          </tr>
                  ';
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

  <script type="text/javascript">
    n = new Date();
    document.getElementById("cccccyear").innerHTML = n.getFullYear();
  </script>
  <!-- View Release Modal -->
  <div class="modal fade" id="viewReleaseModal" tabindex="-1" role="dialog" aria-labelledby="viewReleaseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewReleaseModalLabel">Release Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center" id="loadingSpinner">
            <div class="loader"></div> Loading release details...
          </div>
          <div id="releaseContent" style="display: none;">
            <div class="row">
              <div class="col-md-4 text-center">
                <img id="releaseArt" src="" class="img-fluid rounded mb-3" alt="Release Artwork">
              </div>
              <div class="col-md-8">
                <h4 id="releaseTitle"></h4>
                <p><strong>UPC:</strong> <span id="releaseUPC"></span></p>
                <p><strong>Artists:</strong> <span id="releaseArtists"></span></p>
                <p><strong>Status:</strong> <span id="releaseStatus"></span></p>
                <p id="rejectionReasonSection" style="color: red; display: none;">
                  <strong>Rejection Reason:</strong> <span id="rejectionReason"></span>
                </p>
                <p><strong>Release Date:</strong> <span id="releaseDate"></span></p>
                <p><strong>Original Release Date:</strong> <span id="originalReleaseDate"></span></p>
              </div>
            </div>
            <hr>
            <h5>Tracks</h5>
            <ul class="list-group" id="trackList">
              <!-- Track items will be added here by JS -->
            </ul>
            <hr>
            <h5>
              Create/Edit your stream link here:
            </h5>
            <div style='display:flex;flex-flow: row wrap;' class="input-group">
              <span class="col-auto input-group-text">https://stream.fuchsia.viiic.net/</span>
              <input type="text" class="form-control" style='float:left;' placeholder="abcxyz" value="" id="stream">
              <button class="btn btn-outline-success btn-sm" type="button" id="saveStream">Save</button>
            </div>
          </div>
          <div id="releaseError" class="alert alert-danger" style="display: none;"></div>
        </div>
        <div class="modal-footer">
          <a href="" id="btnEdit"><button type="button" class="btn btn-warning">Edit</button></a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>