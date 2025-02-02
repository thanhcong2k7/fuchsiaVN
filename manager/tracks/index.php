<?php
if (!headers_sent()) {
    foreach (headers_list() as $header)
        header_remove($header);
}
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Resource-Policy: cross-origin");
header("Cross-Origin-Opener-Policy: same-origin");
session_start();
if (!isset($_SESSION["userwtf"]))
    header("Location: /login/");
else {
    require '../../assets/variables/sql.php';
    $user = getUser($_SESSION["userwtf"]);
    $t = getTrackList($_SESSION["userwtf"]);
    $allArtists = getArtist($_SESSION["userwtf"]);
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
    <!-- FFMPEG.JS -->
    <script src="/assets/js/ffmpeg.min.js"></script>
</head>

<body class="bg-theme bg-theme1">
    <!-- loader scripts -->
    <script src="/assets/js/jquery.loading-indicator.js"></script>
    <!-- Start wrapper-->
    <div id="wrapper">

        <!--Start sidebar-wrapper-->
        <?php include '../../components/sidebar.php'; ?>
        <!--End sidebar-wrapper-->

        <!--Start topbar header-->
        <?php include '../../components/topbar.php'; ?>
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
                                <div class="row d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-3">
                                    <div>
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

                                            /* Both cards have a fixed height of 200px */
                                            .square-card,
                                            .auto-card {
                                                height: 250px;
                                            }

                                            /* The square card is a fixed 200x200 box */
                                            .square-card {
                                                width: 250px;
                                            }

                                            /* On mobile, force the second card to be the same width as the first */
                                            .auto-card {
                                                width: 250px;
                                            }

                                            /* On medium screens and up, let the second card expand horizontally */
                                            @media (min-width: 768px) {
                                                .auto-card {
                                                    width: auto;
                                                }
                                            }
                                        </style>
                                        <div class="dnd card card-body square-card text-center d-flex flex-column justify-content-center" style="justify-content: center;">
                                            <div style="margin:auto;">
                                                <div id="dnarea" class="row"
                                                    style="align: center; display: flex; justify-content: center;">
                                                    <input type="file" id="filee" accept="audio/*" />
                                                    <label for="filee" id="ok">
                                                        <span id="texttt"><i class="zmdi zmdi-file-plus"></i> Drop
                                                            audio file here...
                                                        </span>
                                                    </label>
                                                </div>
                                                <hr>
                                                <span>Status: <span id="status">Waiting for file input...</span></span>
                                                <div class="row"
                                                    style="display: block; padding-right: 20px; padding-left: 20px;">
                                                    <div class="progress my-3" style="height:4px;">
                                                        <div class="progress-bar" style="width:50%;" id="progbar"></div>
                                                    </div>
                                                </div>
                                                <script>
                                                </script>
                                                <audio id="output-video" controls></audio>
                                                <script>
                                                    document.addEventListener("DOMContentLoaded", function () {
                                                        const message = document.getElementById('status');
                                                        const { createFFmpeg, fetchFile } = FFmpeg;
                                                        const ffmpeg = createFFmpeg({
                                                            log: true,
                                                            progress: ({ ratio }) => {
                                                                message.innerHTML = `Transcoding: ${(ratio * 100.0).toFixed(2)}%`;
                                                                document.getElementById("progbar").style = "width:" + (ratio * 100.0).toFixed(2).toString() + "%";
                                                            },
                                                        });
                                                        const transcode = async () => {
                                                            document.getElementById("texttt").innerHTML =
                                                                '<i class="zmdi zmdi-file-plus"></i> Processing file: '
                                                                + document.getElementById("filee").files[0].name;
                                                            const name = document.getElementById("filee").files[0].name;
                                                            message.innerHTML = 'Loading ffmpeg-core.js';
                                                            await ffmpeg.load();
                                                            message.innerHTML = 'Start transcoding';
                                                            await ffmpeg.FS('writeFile', name, await fetchFile(document.getElementById("filee").files[0]));
                                                            await ffmpeg.run('-i', name, 'output.mp3');
                                                            message.innerHTML = 'Complete transcoding. <a onclick="adu()">Click to upload this track to server.</a>';
                                                            const data = await ffmpeg.FS('readFile', 'output.mp3');
                                                            const video = document.getElementById('output-video');
                                                            video.src = URL.createObjectURL(new Blob([data.buffer], { type: 'audio/mpeg' }));
                                                        }
                                                        //document.getElementById("filee").addEventListener('onchange', transcode);
                                                        const dropArea = document.getElementById("dnarea");
                                                        dropArea.addEventListener("dragover", function (e) { e.preventDefault(); });
                                                        dropArea.addEventListener("drop", function (e) {
                                                            console.log(e.dataTransfer.files[0].name);
                                                            const fileInput = document.getElementById("filee");
                                                            fileInput.files = e.dataTransfer.files;
                                                            const event = new Event('change', { bubbles: true });
                                                            fileInput.dispatchEvent(event);
                                                            e.preventDefault();
                                                        }, true);
                                                        document.getElementById("filee").addEventListener('change', transcode);
                                                    });
                                                    function adu() {
                                                        var urll = "https://script.google.com/macros/s/AKfycbxN_iQU0-OYm8SOiH0RI_M7oWHIkMI_ZKoWJcRMH7ayyMzygOQXaNZ8GNu0P2ZEba2X/exec";
                                                        var fileInput = document.getElementById("fileInput");
                                                        if (fileInput.files.length === 0) {
                                                            document.getElementById("message").innerText = "Vui lòng chọn một file!";
                                                            return;
                                                        }
                                                        var file = fileInput.files[0];
                                                        const GAS_DEPLOY = urll;
                                                        try {
                                                            const base64String = await encodeFileToBase64(file);

                                                            var formData = new FormData();
                                                            formData.append("name", file.name);
                                                            formData.append("type", file.type);
                                                            formData.append("file", base64String);

                                                            fetch(GAS_DEPLOY, {
                                                                method: 'POST',
                                                                body: formData
                                                            })
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    if (data.status === "success") {
                                                                        let fileID = getFileIdFromUrl(data.url);
                                                                        document.getElementById("message").innerText = fileID;
                                                                        console.log(fileID);
                                                                    } else {
                                                                        document.getElementById("message").innerText = "Lỗi: " + data.message;
                                                                    }
                                                                })
                                                                .catch(error => {
                                                                    document.getElementById("message").innerText = "Lỗi kết nối!";
                                                                    console.error(error);
                                                                });

                                                        } catch (error) {
                                                            document.getElementById("message").innerText = "Lỗi mã hóa tệp!";
                                                            console.error(error);
                                                        }
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col flex-grow-1">
                                        <div class="card auto-card">
                                            <div class="card-body overflow-auto">
                                                jjjjjj
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <br>
                                <div class="card">
                                    <div class="card-header"><i class="zmdi zmdi-collection-music"></i> Your Tracks
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Track ID</th>
                                                        <th>Track name</th>
                                                        <th>Album</th>
                                                        <th>Artist</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($t as &$tr) {
                                                        $albName = getRelease($_SESSION["userwtf"], 0, $tr)->name;
                                                        echo '
                                        <tr>
                                        <td>' . ($tr->id < 10 ? "0" . $tr->id : $tr->id) . '</td>
                                        <td>' . ($tr->name ? $tr->name : "(draft)") . '</td>
                                        <td>' . ($albName ? $albName : "[NULL]") . '</td>
                                        <td>' . ($tr->artistname ? $tr->artistname : "[NULL]") . '</td>
                                        <td>
                                            <a onclick="">Edit</a> / 
                                            <a class="text-error">Delete</a>
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
                    </div>
                </div>
                <!--End Dashboard Content-->

                <!--start overlay-->
                <div class="overlay toggle-menu"></div>
                <!--end overlay-->
            </div>
            <!-- End container-fluid-->
        </div>
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