<?php
session_start();
if (!isset($_SESSION["userwtf"]))
    header("Location: ../login/");
else {
    require '../assets/variables/sql.php';
    $user = getUser($_SESSION["userwtf"]);
    $release = getRelease($_SESSION["userwtf"], 0, $_GET["id"]);
}
if(isset($_GET["new"]) && isset( $_SESSION["userwtf"])){
    query("insert into album (userID) values (".$_SESSION["userwtf"].");");
    $newid = creNew($_SESSION["userwtf"]);
    echo "<script>window.location.href='edit.php?id=".$newid."';</script>";
}
if (isset($_GET["delete"]) && isset( $_GET["id"]) && isset( $_SESSION["userwtf"] )){
    query("delete from album where albumID=".$_GET["id"].";");
    echo "<script>window.location.href='./index.php';</script>";
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
    <title>Release Editor -
        "<?php echo ($release->name ? $release->name : "(untitled)"); ?>"
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
                    <a href="../discography/">
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
                <li><a href="../manager/artist/"><i class="zmdi zmdi-accounts text-warning"></i>
                        <span>Artists</span></a>
                </li>
                <li><a href="../manager/tracks/"><i class="zmdi zmdi-audio text-success"></i>
                        <span>Tracks</span></a>
                </li>
                <li><a href="../ticket/"><i class="zmdi zmdi-bug text-info"></i> <span>Found a bug?</span></a></li>
                <li><a href="../login/login.php?logout=yes"><i class="zmdi zmdi-run text-danger"></i> <span>Log
                            out?</span></a></li>
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
                            <span class="user-profile"><img src="../assets/images/gallery/ava_sample.png"
                                    class="img-circle" alt="user avatar"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item user-details">
                                <a href="javaScript:void();">
                                    <div class="media">
                                        <div class="avatar"><img class="align-self-start mr-3"
                                                src="../assets/images/gallery/ava_sample.png" alt="user avatar">
                                        </div>
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
                            <a class="dropdown-item" href="login/login.php?logout=yes"><i class="icon-power mr-2"></i>
                                Logout</a>
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
                            <form action="save.php" method="POST">
                                <div class="card-header">
                                    <i class="zmdi zmdi-border-color"></i> Catalog ID:
                                    <?php echo "FMG" . $_GET["id"]; ?>
                                    <div class="card-action">
                                        <a href="" class="text-success"><span> Save changes <i
                                                    class="zmdi zmdi-assignment text-success"></i></span></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive overflow-hidden">
                                        <style>
                                            * {
                                                box-sizing: border-box;
                                            }

                                            .dnd {
                                                width: 100%;
                                                height: 100%;
                                                display: flex;
                                            }

                                            #drop-area {
                                                width: 300px;
                                                height: 300px;
                                                padding: 15px;
                                                text-align: center;
                                                border-radius: 7px dashed #000000;
                                            }

                                            #img-view {
                                                width: 100%;
                                                height: 100%;
                                                border-radius: 7px;
                                                border: 1px dashed #AFFFFFFF;
                                                background: #BF000000;
                                                background-position: center;
                                                background-size: cover;
                                            }

                                            #img-view img {
                                                width: 100%;
                                                margin-top: 25px;
                                            }

                                            #img-view span {
                                                display: block;
                                                font-size: 11px;
                                                color: #eeeeee;
                                                margin-top: 47%;
                                            }
                                        </style>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="dnd">
                                                    <label for="input-file" id="drop-area">
                                                        <input type="file" accept="image/*" id="input-file" hidden>
                                                        <div id="img-view">
                                                            <span><i class="zmdi zmdi-file-plus"></i> Upload your artwork here <br />(Min. 1500x1500)</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php
                                                $mergedArtistnames = "";
                                                $f = getFile($_GET["id"]);
                                                $r2 = getRelease($_SESSION["userwtf"], 0, $_GET["id"]);
                                                $track = array();
                                                foreach($r2->file as &$adu){
                                                    foreach($f as &$f2){
                                                        if($f2->id == $adu){
                                                            $t = getTrack($f2->id);
                                                            $track[] = $t;
                                                            $artist = getArtist($t->id);
                                                            foreach ($artist as &$adu) {
                                                                $mergedArtistnames .= ($mergedArtistnames!=""?", ":"").$adu->name;
                                                            }
                                                        } else continue;
                                                    }
                                                }
                                            ?>
                                            <div class="col-md-auto" style="padding-top: 20px;">
                                                <h3><?php echo ($release->name ? $release->name : "(untitled)"); ?>
                                                </h3>
                                                <span><span style="font-weight: bold;">UPC</span>:
                                                    <?php echo ($release->upc ? $release->upc : "(not set)"); ?></span>
                                                    <br />
                                                <span><span style="font-weight: bold;">Artists: </span><?php echo $mergedArtistnames; ?></span>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header"><i class="zmdi zmdi-album"></i> Tracks List
                                                <div class="card-action">
                                                    <div class="dropdown">
                                                        <a href="" class="text dropdown-toggle dropdown-toggle-nocaret">
                                                            <i class="zmdi zmdi-collection-plus"></i> Add more
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <table class="table align-items-center align-items-center table-borderless table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Track Name</th>
                                                                <th>Artists</th>
                                                                <th>Download</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach($track as &$tr){
                                                                    echo '
                                                                    <tr id="track'.$tr->id.'">
                                                                        <td>'.$tr->name.'</td>
                                                                        <td>1</td>
                                                                        <td>Yabucac</td>
                                                                        <td><a href="" class="text-info">GDrive</a></td>
                                                                        <td><a class="text-warning" onclick="document.getElementById(\'track1\').remove();">Delete</a></td>
                                                                    </tr>
                                                                    ';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <script>
                                                const dropArea = document.getElementById("drop-area");
                                                const inputFile = document.getElementById("input-file");
                                                const imageView = document.getElementById("img-view");
                                                inputFile.addEventListener("change", uploadImage);
                                                function uploadImage() {
                                                    let imgLink = URL.createObjectURL(inputFile.files[0]);
                                                    imageView.style.backgroundImage = `url(${imgLink})`;
                                                    imageView.textContent = "";
                                                }
                                                dropArea.addEventListener("dragover", function (e) { e.preventDefault(); });
                                                dropArea.addEventListener("drop", function (e) {
                                                    e.preventDefault();
                                                    inputFile.files = e.dataTransfer.files;
                                                    uploadImage();
                                                });
                                            </script>
                                        </div>
                                    </div>
                            </form>
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