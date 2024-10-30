<?php
session_start();
if (!isset($_SESSION["userwtf"]))
    header("Location: ../login/");
else {
    require '../assets/variables/sql.php';
    $user = getUser($_SESSION["userwtf"]);
    $release = getRelease($_SESSION["userwtf"], 0, $_GET["id"]);
}
if (isset($_GET["new"]) && isset($_SESSION["userwtf"])) {
    query("insert into album (userID) values (" . $_SESSION["userwtf"] . ");");
    $newid = creNew($_SESSION["userwtf"]);
    echo "<script>window.location.href='edit.php?id=" . $newid . "';</script>";
}
if (isset($_GET["delete"]) && isset($_GET["id"]) && isset($_SESSION["userwtf"])) {
    query("delete from album where albumID=" . $_GET["id"] . ";");
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
                            <a class="dropdown-item" href="../login/login.php?logout=yes"><i
                                    class="icon-power mr-2"></i>
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
                            <form action="save.php" method="POST" id="formdepchai">
                                <div class="card-header">
                                    <i class="zmdi zmdi-border-color"></i> Catalog ID:
                                    <?php echo "FMG" . $_GET["id"]; ?>
                                    <div class="card-action">
                                        <a id="saveform" class="text-success" type="submit"><span> Save changes <i
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
                                                justify-content: center;
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
                                            <?php
                                            $mergedArtistnames = "";
                                            $f = getFile($_GET["id"]);
                                            $r2 = getRelease($_SESSION["userwtf"], 0, $_GET["id"]);
                                            $track = array();
                                            foreach ($r2->file as &$adu) {
                                                foreach ($f as &$f2) {
                                                    if ($f2->id == $adu) {
                                                        $t = getTrack($f2->id);
                                                        $track[] = $t;
                                                        $artist = getArtist($t->id);
                                                        foreach ($artist as &$adu) {
                                                            $mergedArtistnames .= ($mergedArtistnames != "" ? ", " : "") . $adu->name;
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="col">
                                                <div class="dnd">
                                                    <label for="input-file" id="drop-area">
                                                        <input type="file" accept="image/*" id="input-file" hidden>
                                                        <div id="img-view">
                                                            <span id="texttt"><i class="zmdi zmdi-file-plus"></i> Upload your
                                                                artwork here <br />(Min. 1500x1500)</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php
                                            if($r2->art != null){
                                                //https://drive.google.com/thumbnail?id=${id}
                                                echo "<script>document.getElementById(\"img-view\").style.backgroundImage = 'url(https://lh3.googleusercontent.com/d/".$r2->art.")'; document.getElementById(\"texttt\").style.display = 'none';</script>";
                                            }
                                            ?>
                                            <div class="col" style="padding-top: 20px;">
                                                <h3><?php echo ($release->name ? $release->name : "(untitled)"); ?>
                                                </h3>
                                                <span><span style="font-weight: bold;">UPC</span>:
                                                    <?php echo ($release->upc ? $release->upc : "(not set)"); ?></span>
                                                <br />
                                                <span><span style="font-weight: bold;">Artists:
                                                    </span><?php echo $mergedArtistnames; ?></span>
                                                <br />
                                                <span><span style="font-weight: bold;">Status:
                                                    </span><?php $r = $release;
                                                    echo ($r->status == 0 ? "DRAFT" : ($r->status == 1 ? "DELIVERED" : ($r->status == 2 ? "ERROR" : "CHECKING"))); ?></span>
                                            </div>
                                        </div>
                                        <br>
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
                                                    <table
                                                        class="table align-items-center align-items-center table-borderless table-sm">
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
                                                            foreach ($track as &$tr) {
                                                                echo '
                                                                    <tr id="track' . $tr->id . '">
                                                                        <td>' . $tr->id . '</td>
                                                                        <td>' . $tr->name . '</td>
                                                                        <td>' . $tr->artistname . '</td>
                                                                        <td><a href="" class="text-info">GDrive</a></td>
                                                                        <td><a class="text-warning" id="delete' . $tr->id . '" onclick="document.getElementById(\'track' . $tr->id . '\').remove();fetch(\'delete.php?albumid=' . $_GET["id"] . '&trackid=' . $tr->id . '\',{credentials:\'same-origin\'}).then((response)=>response.json()).then((responseData)=>{console.log(responseData.status);});">Delete</a></td>
                                                                    </tr>
                                                                    ';
                                                            }
                                                            ?>
                                                            <script>
                                                                document.addEventListener("submit", (e) => {
                                                                    //console.log()
                                                                });
                                                            </script>
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
                                                    var reader = new FileReader();
                                                    //Read the contents of Image File.
                                                    reader.readAsDataURL(inputFile.files[0]);
                                                    reader.onload = function (e) {
                                                        //Initiate the JavaScript Image object.
                                                        var image = new Image();
                                                        //Set the Base64 string return from FileReader as source.
                                                        image.src = e.target.result;
                                                        //Validate the File Height and Width.
                                                        image.onload = function () {
                                                            var height = this.height;
                                                            var width = this.width;
                                                            if (height < 1500 || width < 1500 || height/width!=1) {
                                                                alert("Double-check your image. Is it larger than 1500x1500 or a 1:1 image?");
                                                                return false;
                                                            }
                                                            let imgLink = URL.createObjectURL(inputFile.files[0]);
                                                            imageView.style.backgroundImage = `url(${imgLink})`;
                                                            imageView.textContent = "";
                                                            return true;
                                                        };
                                                    };
                                                }
                                                dropArea.addEventListener("dragover", function (e) { e.preventDefault(); });
                                                dropArea.addEventListener("drop", function (e) {
                                                    e.preventDefault();
                                                    inputFile.files = e.dataTransfer.files;
                                                    uploadImage();
                                                });
                                            </script>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <i class="zmdi zmdi-info"></i> Album Metadata
                                                <div class="card-action">
                                                    <div class="dropdown">
                                                        <a href="" class="text dropdown-toggle dropdown-toggle-nocaret">
                                                            <i class="zmdi zmdi-collection-plus"></i> Add more
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col">
                                                        <label for="albumtitle">Album Title</label>
                                                        <input type="text" class="form-control" id="albumtitle" placeholder="Name of your release">
                                                    </div>
                                                    <div class="form-group col">
                                                        <label for="albumversion">Version line</label>
                                                        <input type="text" class="form-control" id="albumversion" placeholder="Example: Remix, Instrumental, Remastered, ...">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
                                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.css"/>
                                                    <div class="form-group col" id="sandbox-container">
                                                        <label for="reldate">Release date</label>
                                                        <input type="text" class="form-control" id="reldate" placeholder="Pick your release date here (mm/dd/yyyy)">
                                                    </div>
                                                    <div class="form-group col" id="sandbox-container">
                                                        <label for="reldate">Original release date (optional)</label>
                                                        <input type="text" class="form-control" id="reldate" placeholder="This is in case your album has been released before">
                                                    </div>
                                                    <script>
                                                    $('#sandbox-container input').datepicker({
                                                        autoclose: true
                                                    });

                                                    $('#sandbox-container input').on('show', function(e){
                                                        console.debug('show', e.date, $(this).data('stickyDate'));
                                                        
                                                        if ( e.date ) {
                                                            $(this).data('stickyDate', e.date);
                                                        }
                                                        else {
                                                            $(this).data('stickyDate', null);
                                                        }
                                                    });

                                                    $('#sandbox-container input').on('hide', function(e){
                                                        console.debug('hide', e.date, $(this).data('stickyDate'));
                                                        var stickyDate = $(this).data('stickyDate');
                                                        
                                                        if ( !e.date && stickyDate ) {
                                                            console.debug('restore stickyDate', stickyDate);
                                                            $(this).datepicker('setDate', stickyDate);
                                                            $(this).data('stickyDate', null);
                                                        }
                                                    });
                                                    </script>
                                                </div>
                                                <div class="row">
                                                    <select data-placeholder="Choose language of your track">
                                                        <option value="AF">Afrikaans</option>
                                                        <option value="SQ">Albanian</option>
                                                        <option value="AR">Arabic</option>
                                                        <option value="HY">Armenian</option>
                                                        <option value="EU">Basque</option>
                                                        <option value="BN">Bengali</option>
                                                        <option value="BG">Bulgarian</option>
                                                        <option value="CA">Catalan</option>
                                                        <option value="KM">Cambodian</option>
                                                        <option value="ZH">Chinese (Mandarin)</option>
                                                        <option value="HR">Croatian</option>
                                                        <option value="CS">Czech</option>
                                                        <option value="DA">Danish</option>
                                                        <option value="NL">Dutch</option>
                                                        <option value="EN">English</option>
                                                        <option value="ET">Estonian</option>
                                                        <option value="FJ">Fiji</option>
                                                        <option value="FI">Finnish</option>
                                                        <option value="FR">French</option>
                                                        <option value="KA">Georgian</option>
                                                        <option value="DE">German</option>
                                                        <option value="EL">Greek</option>
                                                        <option value="GU">Gujarati</option>
                                                        <option value="HE">Hebrew</option>
                                                        <option value="HI">Hindi</option>
                                                        <option value="HU">Hungarian</option>
                                                        <option value="IS">Icelandic</option>
                                                        <option value="ID">Indonesian</option>
                                                        <option value="GA">Irish</option>
                                                        <option value="IT">Italian</option>
                                                        <option value="JA">Japanese</option>
                                                        <option value="JW">Javanese</option>
                                                        <option value="KO">Korean</option>
                                                        <option value="LA">Latin</option>
                                                        <option value="LV">Latvian</option>
                                                        <option value="LT">Lithuanian</option>
                                                        <option value="MK">Macedonian</option>
                                                        <option value="MS">Malay</option>
                                                        <option value="ML">Malayalam</option>
                                                        <option value="MT">Maltese</option>
                                                        <option value="MI">Maori</option>
                                                        <option value="MR">Marathi</option>
                                                        <option value="MN">Mongolian</option>
                                                        <option value="NE">Nepali</option>
                                                        <option value="NO">Norwegian</option>
                                                        <option value="FA">Persian</option>
                                                        <option value="PL">Polish</option>
                                                        <option value="PT">Portuguese</option>
                                                        <option value="PA">Punjabi</option>
                                                        <option value="QU">Quechua</option>
                                                        <option value="RO">Romanian</option>
                                                        <option value="RU">Russian</option>
                                                        <option value="SM">Samoan</option>
                                                        <option value="SR">Serbian</option>
                                                        <option value="SK">Slovak</option>
                                                        <option value="SL">Slovenian</option>
                                                        <option value="ES">Spanish</option>
                                                        <option value="SW">Swahili</option>
                                                        <option value="SV">Swedish </option>
                                                        <option value="TA">Tamil</option>
                                                        <option value="TT">Tatar</option>
                                                        <option value="TE">Telugu</option>
                                                        <option value="TH">Thai</option>
                                                        <option value="BO">Tibetan</option>
                                                        <option value="TO">Tonga</option>
                                                        <option value="TR">Turkish</option>
                                                        <option value="UK">Ukrainian</option>
                                                        <option value="UR">Urdu</option>
                                                        <option value="UZ">Uzbek</option>
                                                        <option value="VI">Vietnamese</option>
                                                        <option value="CY">Welsh</option>
                                                        <option value="XH">Xhosa</option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                            <script>
                                const form = document.getElementById('formdepchai');
                                form.addEventListener('submit', e => {
                                e.preventDefault();
                                const file = form.inputFile.files[0];
                                const fr = new FileReader();
                                fr.readAsArrayBuffer(file);
                                fr.onload = f => {
                                    
                                    const url = "https://script.google.com/macros/s/AKfycbyRR07piipMl-FrzPBonQS5O3UX8dgp2sSMMXJkllDJdFj_VoZ4z0yLpzw3Mu8YSso/exec";  // <--- Please set the URL of Web Apps.
                                    
                                    const qs = new URLSearchParams({filename: "<?php echo $_GET["id"];?>."+file.name.split('.').pop(), mimeType: file.type});
                                    fetch(`${url}?${qs}`, {method: "POST", body: JSON.stringify([...new Int8Array(f.target.result)])})
                                    .then(res => res.json())
                                    .then(e => {console.log(JSON.parse(e).fileID); fetch("../assets/variables/update.php?req=1&id=<?php echo $albumID; ?>&fileID="+JSON.parse(e).fileID+"&name=<?php echo $_GET["id"];?>."+file.name.split('.').pop(),{credentials:"same-origin"}).then(e => console.log(e)).catch(err => console.log(err));})  // <--- You can retrieve the returned value here.
                                    .catch(err => console.log(err));
                                    //req = 1 -> album cover
                                    //req = 2 -> track
                                }
                                });
                            </script>
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