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
    resetinc("album");
    query("insert into album (userID) values (" . $_SESSION["userwtf"] . ");");
    $newid = creNew($_SESSION["userwtf"]);
    echo "<script>window.location.href='edit.php?id=" . $newid . "';</script>";
} else if (isset($_GET["delete"]) && isset($_GET["id"]) && isset($_SESSION["userwtf"])) {
    foreach ($release->file as &$trackDel)
        update("albumID", "", "track", "id=" . $trackDel);
    query("delete from album where albumID=" . $_GET["id"] . ";");
    echo "<script>window.location.href='.';</script>";
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
    </title><!-- loader-->
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
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

                <!--Start Dashboard Content-->
                <div class="row">
                    <div class="col">
                        <form action="save.php" method="POST" id="formdepchai" enctype='multipart/form-data'></form>
                        <div class="card">
                            <div class="card-header">
                                <i class="zmdi zmdi-border-color"></i> Release ID:
                                <?php echo "FMG" . $_GET["id"]; ?>
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
                                                    <input type="file" accept="image/*" id="input-file" name="artworkup"
                                                        hidden>
                                                    <div id="img-view">
                                                        <span id="texttt"><i class="zmdi zmdi-file-plus"></i> Upload
                                                            your
                                                            artwork here <br />(Min. 1500x1500)</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <?php
                                        if ($r2->art != null) {
                                            //https://drive.google.com/thumbnail?id=${id}
                                            echo "<script>document.getElementById(\"img-view\").style.backgroundImage = 'url(" . $r2->art . ")'; document.getElementById(\"texttt\").style.display = 'none';</script>";
                                        }
                                        ?>
                                        <div class="col" style="padding-top: 20px;">
                                            <h3><?php echo ($release->name ? $release->name : "(untitled)") . ($release->version ? " (" . $release->version . ")" : ""); ?>
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
                                            <input value="<?php echo $_GET["id"]; ?>" name="albumid" hidden>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="card">
                                        <div class="card-header"><i class="zmdi zmdi-album"></i> Tracks List
                                            <div class="card-action">
                                                <div class="dropdown">
                                                    <!-- Button trigger modal -->
                                                    <a href="" class="text dropdown-toggle dropdown-toggle-nocaret"
                                                        data-toggle="modal" data-target="#exampleModalLong">
                                                        <i class="zmdi zmdi-collection-plus"></i> Add more
                                                    </a>
                                                </div>
                                            </div>
                                            <style>
                                                /* HTML: <div class="loader"></div> */
                                                .loader {
                                                    width: 45px;
                                                    aspect-ratio: 1;
                                                    --c: no-repeat linear-gradient(#255 255 255);
                                                    background:
                                                        var(--c) 0% 50%,
                                                        var(--c) 50% 50%,
                                                        var(--c) 100% 50%;
                                                    background-size: 20% 100%;
                                                    animation: l1 1s infinite linear;
                                                }

                                                @keyframes l1 {
                                                    0% {
                                                        background-size: 20% 100%, 20% 100%, 20% 100%
                                                    }

                                                    33% {
                                                        background-size: 20% 10%, 20% 100%, 20% 100%
                                                    }

                                                    50% {
                                                        background-size: 20% 100%, 20% 10%, 20% 100%
                                                    }

                                                    66% {
                                                        background-size: 20% 100%, 20% 100%, 20% 10%
                                                    }

                                                    100% {
                                                        background-size: 20% 100%, 20% 100%, 20% 100%
                                                    }
                                                }
                                            </style>
                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="bg-color: #000000;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">
                                                                <i class="zmdi zmdi-playlist-audio"></i> Choose
                                                                files from your catalogue
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="loader" id="content"></div>
                                                            <div id="content"></div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light px-5"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->
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
                                                        if (height < 1500 || width < 1500 || height / width != 1) {
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
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col">
                                                    <label for="albumtitle">Album Title</label>
                                                    <input type="text" class="form-control" name="albumtitle"
                                                        placeholder="Name of your release"
                                                        value="<?php echo $release->name; ?>">
                                                </div>
                                                <div class="form-group col">
                                                    <label for="albumversion">Version line (optional)</label>
                                                    <input type="text" class="form-control" name="albumversion"
                                                        placeholder="Leave blank if there's only 1 track. Example: Remix, Instrumental, ..."
                                                        value="<?php echo $release->version; ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <script
                                                    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
                                                <link rel="stylesheet"
                                                    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.css" />
                                                <div class="form-group col" id="sandbox-container">
                                                    <label for="reldate">Release date (optional)
                                                        (mm/dd/yyyy)</label>
                                                    <input type="text" class="form-control" id="reldate" name="reldate"
                                                        placeholder="Pick your release date here (mm/dd/yyyy)"
                                                        value="<?php echo date_format(date_create($release->relDate), "m/d/Y"); ?>">
                                                </div>
                                                <div class="form-group col" id="sandbox-container2">
                                                    <label for="orgreldate">Original release date (optional)
                                                        (mm/dd/yyyy)</label>
                                                    <input type="text" class="form-control" id="orgreldate"
                                                        name="orgreldate"
                                                        placeholder="This is in case your album has been released before"
                                                        value="<?php echo date_format(date_create($release->orgReldate), "m/d/Y"); ?>">
                                                </div>
                                                <script>
                                                    $('#sandbox-container input').datepicker({
                                                        autoclose: true,
                                                        startDate: '-0d',
                                                        todayHighlight: true
                                                    });
                                                    $('#sandbox-container2 input').datepicker({
                                                        autoclose: true,
                                                        endDate: '+0d',
                                                        todayHighlight: true
                                                    });

                                                    $('#sandbox-container input').on('show', function (e) {
                                                        console.debug('show', e.date, $(this).data('stickyDate'));

                                                        if (e.date) {
                                                            $(this).data('stickyDate', e.date);
                                                        }
                                                        else {
                                                            $(this).data('stickyDate', null);
                                                        }
                                                    }); $('#sandbox-container2 input').on('show', function (e) {
                                                        console.debug('show', e.date, $(this).data('stickyDate'));

                                                        if (e.date) {
                                                            $(this).data('stickyDate', e.date);
                                                        }
                                                        else {
                                                            $(this).data('stickyDate', null);
                                                        }
                                                    });

                                                    $('#sandbox-container input').on('hide', function (e) {
                                                        console.debug('hide', e.date, $(this).data('stickyDate'));
                                                        var stickyDate = $(this).data('stickyDate');

                                                        if (!e.date && stickyDate) {
                                                            console.debug('restore stickyDate', stickyDate);
                                                            $(this).datepicker('setDate', stickyDate);
                                                            $(this).data('stickyDate', null);
                                                        }
                                                    }); $('#sandbox-container2 input').on('hide', function (e) {
                                                        console.debug('hide', e.date, $(this).data('stickyDate'));
                                                        var stickyDate = $(this).data('stickyDate');

                                                        if (!e.date && stickyDate) {
                                                            console.debug('restore stickyDate', stickyDate);
                                                            $(this).datepicker('setDate', stickyDate);
                                                            $(this).data('stickyDate', null);
                                                        }
                                                    });
                                                </script>
                                            </div>
                                            <hr class="mt-1 mb-1" />
                                            <br>
                                            <div class="row">
                                                <div class="form-group col">
                                                    <label for="albumtitle">UPC (optional)</label>
                                                    <input type="text" class="form-control" maxlength="12" name="upc"
                                                        placeholder="A valid 12-digit UPC for your release. Leave blank if you don't have one."
                                                        value="<?php echo $release->upc; ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="albumtitle">© Copyright year</label>
                                                    <input type="text" class="form-control" name="cyear"
                                                        placeholder="<?php echo date("Y"); ?>"
                                                        value="<?php echo date("Y"); ?>" required>
                                                </div>
                                                <div class="form-group col">
                                                    <label for="albumversion">© Copyright Line</label>
                                                    <input type="text" class="form-control" name="cline"
                                                        placeholder="Copyright line. Example: VINA Nation"
                                                        value="<?php echo $release->c; ?>" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="albumtitle">℗ Phonogram year</label>
                                                    <input type="text" class="form-control" name="pyear"
                                                        placeholder="<?php echo date("Y"); ?>"
                                                        value="<?php echo date("Y"); ?>" required>
                                                </div>
                                                <div class="form-group col">
                                                    <label for="albumversion">℗ Phonogram Line</label>
                                                    <input type="text" class="form-control" name="pline"
                                                        placeholder="Phonogram line. Example: VINA Nation"
                                                        value="<?php echo $release->p; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', () => {
                                            document.querySelectorAll('[maxlength]').forEach(input => {
                                                input.addEventListener('input', e => {
                                                    let val = e.target.value, len = +e.target.getAttribute('maxlength');
                                                    e.target.value = val.slice(0, len);
                                                });
                                            });
                                        });
                                    </script>
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="zmdi zmdi-store"></i> Stores/Services
                                        </div>
                                        <div class="card-body">
                                            <style>
                                                .alert {
                                                    padding: 15px;
                                                    border-color: #6DD134;
                                                    border-radius: 5px;
                                                    border-width: 2px;
                                                    color: white;
                                                }
                                            </style>
                                            <div class="row">
                                                <div class="col">
                                                    <link
                                                        href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css"
                                                        rel="stylesheet" />
                                                    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
                                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
                                                    <select name="" class="form-select" id="stores" multiple
                                                        data-placeholder="Choose anything">
                                                        <option></option>
                                                        <?php
                                                            $sus = getStore();
                                                            foreach ($sus as $s) {
                                                                /*
                                                                echo '<script>
                                                                        $("#stores").append(new Option("' . $s->name . '","store' . $s->id . '",false,false)).trigger("change");
                                                                        </script>
                                                                    ';*/
                                                                echo '<option id="' . $s->id . '">' . $s->name . '</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                    <script>
                                                        $(document).ready(function () {
                                                            $('#stores').select2({ width: "100%", placeholder: "Choose any stores" });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <span>
                                                                <i class="zmdi zmdi-shopping-cart"></i> Additional
                                                                Delivery Options
                                                            </span>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="col">
                                                                <div class="alert row callout" role="alert"
                                                                    style="overflow: hidden;white-space: initial;">
                                                                    <span>
                                                                        <i class="zmdi zmdi-info-outline text-warning">
                                                                        </i> <strong>Note:</strong> Enable Youtube
                                                                        Content ID only when you're confident that
                                                                        your song doesn't contain any copyrighted
                                                                        content!
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="card card-body row">
                                                                    <div class="icheck-material-white">
                                                                        <input type="checkbox" id="ytcid" name="ytcid"
                                                                            <?php echo ($release->ytcid ? "checked" : ""); ?> />
                                                                        <label for="ytcid"> YouTube Content
                                                                            ID
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-body row">
                                                                <div class="icheck-material-white">
                                                                    <input type="checkbox" id="scloud" name="scloud"
                                                                        <?php echo ($release->sc ? "checked" : ""); ?> />
                                                                    <label for="scloud"
                                                                        style="overflow: hidden;white-space: initial;">
                                                                        SoundCloud Monetization
                                                                        & Content Protection</label>
                                                                </div>
                                                            </div>
                                                            <div class="card card-body row">
                                                                <div class="icheck-material-white">
                                                                    <input type="checkbox" id="soundx" name="soundx"
                                                                        <?php echo ($release->sx ? "checked" : ""); ?> />
                                                                    <label for="soundx"> SoundExchange</label>
                                                                </div>
                                                            </div>
                                                            <div class="card card-body row">
                                                                <div class="icheck-material-white">
                                                                    <input type="checkbox" id="jdl" name="jdl" <?php echo ($release->jdl ? "checked" : ""); ?> />
                                                                    <label for="jdl"> Juno Download</label>
                                                                </div>
                                                            </div>
                                                            <div class="card card-body row">
                                                                <div class="icheck-material-white">
                                                                    <input type="checkbox" id="trl" name="trl" <?php echo ($release->trl ? "checked" : ""); ?> />
                                                                    <label for="trl"> Tracklib</label>
                                                                </div>
                                                            </div>
                                                            <div class="card card-body row">
                                                                <div class="icheck-material-white col">
                                                                    <input type="checkbox" id="bport" name="bport" <?php echo ($release->bp ? "checked" : ""); ?> />
                                                                    <label for="bport"> Beatport
                                                                    </label>
                                                                </div>
                                                                <input type="text" class="form-control row col"
                                                                    name="bporturl"
                                                                    placeholder="Page URL (Leave blank for a new one)"
                                                                    value="<?php echo $release->bp; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <center>
                                            <div>
                                                <input name="saveform" id="saveform" type="submit"
                                                    class="btn btn-light btn-round px-5" value="Save changes">
                                                <input name="distform" id="distform" type="submit"
                                                    class="text-warning btn btn-light btn-round px-5"
                                                    value="Distribute now">
                                            </div>
                                        </center>
                                    </div>
                                </div>
                                <script>
                                    const form = document.getElementById('formdepchai');
                                /*
                                form.addEventListener('submit', e => {
                                    e.preventDefault();
                                    const file = document.getElementById("input-file").files[0];
                                    const fr = new FileReader();
                                    fr.readAsArrayBuffer(file);
                                    fr.onload = f => {
                                        //api key imgbb 
                                        //const key = '9527a1c516dd2fca6551240ba89343ca';
                                        const url = "https://api.imgbb.com/1/upload";
                                        fetch(`${url}?key=${key}`);
                                        /*
                                        const url = "https://script.google.com/macros/s/AKfycbyRR07piipMl-FrzPBonQS5O3UX8dgp2sSMMXJkllDJdFj_VoZ4z0yLpzw3Mu8YSso/exec";  // <--- Please set the URL of Web Apps.
                                        const qs = new URLSearchParams({ filename: "<?php echo $_GET["id"]; ?>." + file.name.split('.').pop(), mimeType: file.type });
                                    fetch(`${url}?${qs}`, { method: "POST", body: JSON.stringify([...new Int8Array(f.target.result)]) })
                                        .then(res => res.json())
                                        .then(e => { console.log(JSON.parse(e).fileID); fetch("../assets/variables/update.php?req=1&id=<?php echo $albumID; ?>&fileID=" + JSON.parse(e).fileID + "&name=<?php echo $_GET["id"]; ?>." + file.name.split('.').pop(), { credentials: "same-origin" }).then(e => console.log(e)).catch(err => console.log(err)); })  // <--- You can retrieve the returned value here.
                                        .catch(err => console.log(err));
                                        //req = 1 -> album cover
                                        //req = 2 -> track
                                    }
                                });*/
                                </script>
                            </div>
                            </form>
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
        <script type="text/javascript">
            n = new Date();
            document.getElementById("cccccyear").innerHTML = n.getFullYear();
        </script>
</body>

</html>