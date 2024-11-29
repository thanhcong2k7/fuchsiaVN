<?php
session_start();
if (!isset($_SESSION["userwtf"]))
    header("Location: /login/");
else {
    require '../../assets/variables/sql.php';
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
    <title>Tracks Manager - fuchsia Media Group
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
    <script src="/assets/js/flac/EmsWorkerProxy.js"></script>
</head>

<body class="bg-theme bg-theme1">
    <!-- loader scripts -->
    <script src="/assets/js/jquery.loading-indicator.js"></script>
    <!-- Start wrapper-->
    <div id="wrapper">

        <!--Start sidebar-wrapper-->
        <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
            <div class="brand-logo">
                <a href="/">
                    <img src="/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
                    <h5 class="logo-text">fuchsia Partner</h5>
                </a>
            </div>
            <ul class="sidebar-menu do-nicescrol">
                <li class="sidebar-header">MAIN MENU</li>
                <li>
                    <a href="/">
                        <i class="zmdi zmdi-view-dashboard"></i> <span>Homepage</span>
                    </a>
                </li>

                <li>
                    <a href="/discography/">
                        <i class="zmdi zmdi-album"></i> <span>Discography</span>
                    </a>
                </li>

                <li>
                    <a href="/analytics/">
                        <i class="zmdi zmdi-format-list-bulleted"></i> <span>Analytics</span>
                    </a>
                </li>

                <li>
                    <a href="/revenue/">
                        <i class="zmdi zmdi-balance-wallet"></i> <span>Revenue</span>
                    </a>
                </li>

                <li>
                    <a href="/settings/">
                        <i class="zmdi zmdi-assignment-account"></i> <span>Your account</span>
                    </a>
                </li>

                <li class="sidebar-header">TOOLBOX</li>
                <li><a href="/manager/artist/"><i class="zmdi zmdi-accounts text-warning"></i> <span>Artists</span></a>
                </li>
                <li><a href="/manager/tracks/"><i class="zmdi zmdi-audio text-success"></i> <span>Tracks</span></a></li>
                <li><a href="/ticket/"><i class="zmdi zmdi-tag text-info"></i> <span>Support</span></a></li>
                <li><a href="/login/login.php?logout=yes"><i class="zmdi zmdi-run text-danger"></i> <span>Log
                            out?</span></a>
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
                            <span class="user-profile"><img src="/assets/images/gallery/ava_sample.png"
                                    class="img-circle" alt="user avatar"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item user-details">
                                <a href="javaScript:void();">
                                    <div class="media">
                                        <div class="avatar"><img class="align-self-start mr-3"
                                                src="/assets/images/gallery/ava_sample.png" alt="user avatar">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-2 user-title"><?php echo $user->display; ?></h6>
                                            <p class="user-subtitle"><?php echo $user->email; ?></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-header">TOOLBOX</li>
                            <li><a href="/manager/artist/"><i class="zmdi zmdi-accounts text-warning"></i>
                                    <span>Artists</span></a></li>
                            <li><a href="/manager/tracks/"><i class="zmdi zmdi-audio text-success"></i>
                                    <span>Tracks</span></a></li>
                            <li><a href="/ticket/"><i class="zmdi zmdi-tag text-info"></i> <span>Support</span></a></li>
                            <li><a href="/login/login.php?logout=yes"><i class="zmdi zmdi-run text-danger"></i>
                                    <span>Log out?</span></a>
                            </li>
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
                            <div class="card-header"><i class="zmdi zmdi-collection-music"></i> Tracks Manager</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <style>
                                            #filee {
                                                display: none;
                                            }

                                            #ok {
                                                height: 100px;
                                                width: 90%;
                                                Border-radius: 6px;
                                                text-align: center;
                                                Border: 1px dashed #999;
                                                margin: 0 auto;
                                            }

                                            #ok span {
                                                display: block;
                                                font-size: 11px;
                                                color: #eeeeee;
                                                margin: auto;
                                                padding: 35px 0;
                                            }

                                            #ok:hover {
                                                border-color: #AFFFFFFF;
                                            }

                                            * {
                                                box-sizing: border-box;
                                            }

                                            .dnd {
                                                width: 100%;
                                                height: 100%;
                                                display: flex;
                                                justify-content: center;
                                            }
                                        </style>
                                        <div class="dnd card card-body" style="justify-content: center;">
                                            <center>
                                                <div class="row"
                                                    style="align: center; display: flex; justify-content: center;">
                                                    <input type="file" id="filee" accept=".wav,.flac" />
                                                    <label for="filee" id="ok">
                                                        <span><i class="zmdi zmdi-file-plus"></i> Drop
                                                            audio file here <br>(.WAV/.FLAC only!)
                                                        </span>
                                                    </label>
                                                </div>
                                                <hr>
                                                <span>Status: <p id="status"></p></span>
                                                <div class="row"
                                                    style="display: block; padding-right: 20px; padding-left: 20px;">
                                                    <div class="progress my-3" style="height:4px;">
                                                        <div class="progress-bar" style="width:50%;" id="progbar"></div>
                                                    </div>
                                                </div>
                                                <script type='text/javascript'>
                                                    var $prog = $('#progbar'),
                                                        $console = $('#console'),
                                                        $input = $('#filee'),
                                                        worker = new Worker('/assets/js/flac/EmsWorkerProxy.js');
                                                    $input.change(function () {
                                                        var f = this.files[0],
                                                            fr = new FileReader();
                                                        $input.attr('disabled', 'disabled');
                                                        fr.addEventListener('loadend', function () {
                                                            var encodedName = f.name.replace(/\.[^\.]+$/, '.flac');
                                                            var args = [
                                                                f.name
                                                            ];
                                                            var inData = {};
                                                            inData[f.name] = new Uint8Array(fr.result);
                                                            var outData = {};
                                                            outData[encodedName] = {
                                                                'MIME': 'audio/flac'
                                                            };
                                                            worker.postMessage({
                                                                command: 'encode',
                                                                args: args,
                                                                outData: outData,
                                                                fileData: inData
                                                            });
                                                        });
                                                        fr.readAsArrayBuffer(f);
                                                    });
                                                    worker.onmessage = function (e) {
                                                        if (e.data && e.data.reply === 'progress') {
                                                            vals = e.data.values;
                                                            if (vals[1]) {
                                                                $prog.val(vals[0] / vals[1] * 100);
                                                            }
                                                        } else if (e.data && e.data.reply === 'done') {
                                                            $prog.val(100);
                                                            for (fileName in e.data.values) {
                                                                $('<a>')
                                                                    .text(fileName)
                                                                    .prop('href',
                                                                        window.URL.createObjectURL(e.data.values[fileName].blob)
                                                                    )
                                                                    .insertAfter($input);
                                                            }
                                                        }
                                                    };
                                                </script>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <br>
                                <div class="card">
                                    <div class="card-header"><i class="zmdi zmdi-collection-music"></i> Your Tracks
                                    </div>
                                    <div class="card-body">nyom nyom</div>
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
    <script src="/assets/js/index.js"></script>
    <script type="text/javascript">
        n = new Date();
        document.getElementById("cccccyear").innerHTML = n.getFullYear();
    </script>
</body>

</html>