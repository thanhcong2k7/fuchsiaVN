<?php
session_start();
if (!isset($_SESSION["userwtf"]))
    header("Location: /login/");
else {
    require '../../assets/variables/sql.php';
    $user = getUser($_SESSION["userwtf"]);
    $artist = fetchArtist($_SESSION["userwtf"]);
    if (isset($_SESSION["restricted"])) {
        $mergee = "";
        foreach (json_decode($_SESSION["restricted"]) as &$t) {
            $x = getTrackname($t);
            $mergee .= ($mergee ? ", " . $x : $x);
        }
        echo "<script>alert('Can not delete this artist, who is involving in these tracks: " . $mergee . "');</script>";
        unset($_SESSION["restricted"]);
    }
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
    <title>Artists Manager - fuchsia Media Group
    </title>
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
    <link rel="stylesheet" href="/assets/css/scroll-bar.css" />
</head>

<body class="bg-theme bg-theme1">
    <!-- loader scripts -->
    <script src="/assets/js/jquery.loading-indicator.js"></script>
    <!-- Start wrapper-->
    <div id="wrapper">

        <!--Start sidebar-wrapper-->
        <?php include "../../components/sidebar.php"; ?>
        <!--End sidebar-wrapper-->

        <!--Start topbar header-->
        <?php include "../../components/topbar.php"; ?>
        <!--End topbar header-->

        <div class="clearfix"></div>

        <div class="content-wrapper">
            <div class="container-fluid">

                <!--Start Dashboard Content-->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header"><i class="zmdi zmdi-accounts-list"></i> Artist Editor</div>
                            <div class="card-body">
                                <form action="artist.php" id="newArtist" method="POST">

                                    <div class="form-group row">
                                        <label for="artist-id" class="col-sm-2 col-form-label">Artist ID</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="artist-id" name="artist-id"
                                                placeholder="(Optional if you're creating new artist)">
                                        </div>

                                        <label for="alias" class="col-sm-2 col-form-label">Artist Name</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="alias" name="alias"
                                                placeholder="Alias. Example: Unknown Brain, Elektronomia, Japandee, Thereon, ...">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="spotifyID" class="col-sm-2 col-form-label">Spotify ID</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="spotifyID" name="spotifyID"
                                                placeholder="Spotify ID ONLY. Example: 3NtqIIwOmoUGkrS4iD4lxY">
                                        </div>

                                        <label for="amID" class="col-sm-2 col-form-label">Apple Music ID</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="amID" name="amID"
                                                placeholder="Apple Music ID ONLY. Example: 1726676105">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="email" name="email"
                                                placeholder="Artist's email (optional)">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-light px-5 w-100">
                                            <i class="zmdi zmdi-plus-square"></i> Submit
                                        </button>
                                    </div>

                                </form>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><i class="zmdi zmdi-accounts-list-alt"></i> Artist Manager
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Artist name</th>
                                                <th>Spotify</th>
                                                <th>Apple Music</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($artist == null)
                                                echo '<center>There\'s nothing here yet...</center>';
                                            else
                                                foreach ($artist as &$r) {
                                                    echo '
                          <tr>
                                      <td>' . ($r->id < 10 ? "0" . $r->id : $r->id) . '</td>
                                      <td>' . ($r->name ? $r->name : "(draft)") . '</td>
                                      <td> <a class="btn btn-link" href="https://open.spotify.com/artist/' . ($r->spot ? $r->spot : "--") . '">' . ($r->spot ? "Link" : "--") . '</a></td>
                                      <td> <a class="btn btn-link" href="https://music.apple.com/us/artist/' . ($r->applemusic ? $r->applemusic : "--") . '">' . ($r->applemusic ? "Link" : "--") . '</a></td>
                                      <td>' . ($r->email ? $r->email : "--") . '</td>
                          <td>
                        <a class="btn btn-link text-error" href="javascript:document.getElementById(\'artist-id\').value = \'' . $r->id . '\';document.getElementById(\'alias\').value = \'' . $r->name . '\';document.getElementById(\'spotifyID\').value = \'' . $r->spot . '\';document.getElementById(\'amID\').value = \'' . $r->applemusic . '\';document.getElementById(\'email\').value = \'' . $r->email . '\';void(0);">Edit</a> / 
                        <a class="btn btn-link text-danger" href="artist.php?id=' . $r->id . '&delete=1">Delete</a>
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

    <script type="text/javascript">
        n = new Date();
        document.getElementById("cccccyear").innerHTML = n.getFullYear();
    </script>
</body>

</html>