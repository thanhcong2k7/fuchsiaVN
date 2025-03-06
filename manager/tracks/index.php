<?php
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Resource-Policy: cross-origin");
header("Cross-Origin-Opener-Policy: same-origin");
session_start();
if (!isset($_SESSION["userwtf"]))
    header("Location: /login/");
else {
    require '../../assets/variables/sql.php';
    $user = getUser($_SESSION["userwtf"]);
    $trackList = getTrackList($_SESSION["userwtf"]);
    $allArtists = getArtist($_SESSION["userwtf"]);
    if (isset($_GET["trackID"])) {
        $track = getTrack($_GET["trackID"]);
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
    <link rel="stylesheet" href="/assets/css/scroll-bar.css" />
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
                        <div class="card-body">
                            <div
                                class="row d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-3">
                                <div class="col fixed-card-container">
                                    <style>
                                        #filee {
                                            display: none;
                                        }

                                        #ok {
                                            height: 100px;
                                            width: 90%;
                                            border-radius: 6px;
                                            text-align: center;
                                            border: 1px dashed #999;
                                            margin: 0 auto;
                                        }

                                        #ok span {
                                            display: block;
                                            font-size: 11px;
                                            color: #eeeeee;
                                            margin: 0 auto;
                                            padding: 35px 0;
                                        }

                                        #ok:hover {
                                            border-color: #AFFFFFFF;
                                        }

                                        * {
                                            box-sizing: border-box;
                                            scroll-behavior: smooth;
                                        }

                                        .dnd {
                                            width: 100%;
                                            height: 100%;
                                            display: flex;
                                            justify-content: center;
                                        }

                                        /* Both cards have a fixed height of 300px */
                                        .square-card {
                                            height: 350px;
                                        }

                                        /* The square card is a fixed 350x350 box */
                                        .square-card {
                                            width: 100%;
                                        }

                                        /* On medium screens and up, let the second card expand horizontally */
                                        @media (min-width: 768px) {
                                            .auto-card {
                                                width: auto;
                                            }
                                        }

                                        .fixed-card-container {
                                            flex: 0 0 350px;
                                            width: 100%;
                                        }

                                        .auto-card {
                                            height: 397px;
                                            /* Match height with first card */
                                            min-width: 300px;
                                            /* Prevent card from becoming too narrow */
                                        }

                                        /* Mobile-first styles */
                                        @media (max-width: 800px) {
                                            .fixed-card-container {
                                                width: 100%;
                                                flex-basis: auto;
                                            }

                                            .auto-card {
                                                width: 100%;
                                            }
                                        }
                                    </style>
                                    <div class="dnd card">
                                        <div class="card-header">
                                            <i class="zmdi zmdi-cloud-upload"></i> Upload file
                                        </div>
                                        <div style="margin:auto; justify-content: center;"
                                            class="square-card card-body text-center d-flex flex-column justify-content-center">
                                            <div id="dnarea" class="row"
                                                style="align: center; display: flex; justify-content: center;">
                                                <input type="file" id="filee" accept="audio/*" />
                                                <label for="filee" id="ok">
                                                    <span id="texttt"><i class="zmdi zmdi-file-plus"></i> Drop
                                                        audio file here...
                                                    </span>
                                                </label>
                                            </div>
                                            <hr class="mt-1 mb-1" />
                                            <br>
                                            <span>Status: <span id="status">Waiting for file input...</span></span>
                                            <div class="row"
                                                style="display: block; padding-right: 20px; padding-left: 20px;">
                                                <div class="progress my-3" style="height:4px;">
                                                    <div class="progress-bar" style="width:50%;" id="progbar"></div>
                                                </div>
                                            </div>
                                            <link rel="stylesheet" href="/assets/css/plyr.css">
                                            <script src="/assets/js/plyr.min.js"></script>
                                            <audio id="output-video" crossorigin controls></audio>
                                            <hr class="mt-1 mb-1" />
                                            <label for="submit"></label>
                                            <button type="submit" class="btn btn-light px-5" id="uploadBtn" disabled>
                                                <i class="zmdi zmdi-plus-square"></i> Upload!
                                            </button>
                                            <script>
                                                const player = new Plyr('#output-video', {}); //Init Plyr.io audio control
                                            </script>
                                            <audio src="noti.wav" style="display:none" preload="auto"
                                                id="notiSound"></audio>
                                            <script src="app.js"></script>
                                        </div>
                                    </div>
                                </div>
                                <div class="col flex-grow-1" id="metadata">
                                    <div class="card auto-card" style="height:397px; width: 100%">
                                        <div class="card-header">
                                            <i class="zmdi zmdi-info-outline"></i> Track Metadata
                                            <div class="card-action">
                                                <div class="dropdown">
                                                    <a href="">Save </a> <i class="zmdi zmdi-arrow-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body overflow-auto">
                                            <form method="POST" action="">
                                                <!-- Track Section -->
                                                <!-- Track Section -->
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-8 mb-3">
                                                                <label class="d-block mb-1">Track Title</label>
                                                                <input type="text" class="form-control"
                                                                    name="tracktitle" placeholder="Name of your track"
                                                                    value="<?php echo $track->name; ?>">
                                                            </div>
                                                            <div class="form-group col-md-4 mb-3">
                                                                <label class="d-block mb-1">Track Version
                                                                    (optional)</label>
                                                                <input type="text" class="form-control"
                                                                    name="trackversion"
                                                                    placeholder="Remix, Original, ..."
                                                                    value="<?php echo $track->version; ?>">
                                                            </div>
                                                        </div>

                                                        <!-- Album/ISRC Section -->
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6 mb-3">
                                                                <label class="d-block mb-1">Primary Genre</label>
                                                                <input type="text" class="form-control"
                                                                    name="albumtitle" placeholder="" value="">
                                                            </div>
                                                            <div class="form-group col-md-6 mb-3">
                                                                <label class="d-block mb-1">Secondary Genre
                                                                    (optional)</label>
                                                                <input type="text" class="form-control" name="isrc"
                                                                    placeholder="Optional"
                                                                    value="<?php echo $track->isrc; ?>">
                                                            </div>
                                                        </div>

                                                        <!-- Phonogram Section -->
                                                        <div class="form-row align-items-center mb-3">
                                                            <div class="form-group col-md-4">
                                                                <label class="d-block mb-1">℗ P Year</label>
                                                                <input type="text" class="form-control" name="pyear"
                                                                    placeholder="<?php echo date("Y"); ?>"
                                                                    value="<?php echo date("Y"); ?>" required>
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <label class="d-block mb-1">℗ Phonogram Rights
                                                                    Holder</label>
                                                                <input type="text" class="form-control" name="pline"
                                                                    placeholder="Phonogram line. Example: VINA Nation"
                                                                    value="<?php echo $release->p; ?>" required>
                                                            </div>
                                                        </div>
                                                        <!-- Track ISRC -->
                                                        <div class="form-row">
                                                            <div class="form-group col mb-1">
                                                                <!-- Reduced bottom margin -->
                                                                <label class="d-block mb-1">Track ISRC</label>
                                                                <input type="text" class="form-control" name="preview"
                                                                    placeholder="00:15"
                                                                    value="<?php echo $track->isrc; ?>">
                                                                <!-- Fixed PHP comment -->
                                                                <small class="form-text text-muted mt-1"></small>
                                                            </div>
                                                        </div>
                                                        <!-- TikTok Preview Start Time -->
                                                        <div class="form-row">
                                                            <div class="form-group col mb-1">
                                                                <!-- Reduced bottom margin -->
                                                                <label class="d-block mb-1">Preview Start Time
                                                                    (mm:ss)</label>
                                                                <input type="text" class="form-control" name="preview"
                                                                    placeholder="00:15"
                                                                    value="<?php //echo $track->preview; ?>">
                                                                <!-- Fixed PHP comment -->
                                                                <small class="form-text text-muted mt-1">
                                                                    Indicate the minute at which the track should start
                                                                    playing. Please note that this only applies to
                                                                    channels that support this specification.
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Selected artists table -->
                                                <div class="card mt-3">
                                                    <div class="card-header">Select And Select Artists</div>
                                                    <div class="card-body">
                                                        <select class="" id="artist-search" multiple>
                                                            <option value="1">Artist 1</option>
                                                            <option value="2">Artist 2</option>
                                                            <option value="3">Artist 3</option>
                                                            <option value="4">Artist 4</option>
                                                        </select>
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table align-items-center table-flush table-hover"
                                                                id="selected-artists-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Artist Name</th>
                                                                        <th>Roles</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- Selected artists will appear here -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="selected_artists" id="selected-artists">
                                                <!-- Add this after the Selected Artists table -->
                                                <div class="card mt-3">
                                                    <div class="card-header">Lyrics</div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Language of the Lyrics</label>
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="instrumentalCheck" name="is_instrumental">
                                                                <label class="custom-control-label"
                                                                    for="instrumentalCheck">This track is
                                                                    instrumental</label>
                                                            </div>
                                                            <select class="form-control" id="languageSelect"
                                                                name="lyrics_language">
                                                                <option value="">Select language...</option>
                                                                <option value="en">English</option>
                                                                <option value="es">Spanish</option>
                                                                <option value="fr">French</option>
                                                                <option value="de">German</option>
                                                                <option value="ja">Japanese</option>
                                                                <option value="ko">Korean</option>
                                                                <option value="zh">Chinese</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                            <small class="form-text text-muted">Select the language of
                                                                the lyrics or if it is an instrumental track.</small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Explicit Content</label>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="explicit1"
                                                                    name="explicit_content" value="not_explicit"
                                                                    class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="explicit1">Not
                                                                    Explicit - Appropriate for all audiences</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="explicit2"
                                                                    name="explicit_content" value="explicit"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="explicit2">Explicit - Contains strong or
                                                                    inappropriate language</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="explicit3"
                                                                    name="explicit_content" value="cleaned"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="explicit3">Cleaned Version - Version of another
                                                                    track where the explicit content has been
                                                                    removed</label>
                                                            </div>
                                                            <small class="form-text text-muted">Indicate whether the
                                                                lyrics contain words or phrases that are considered
                                                                offensive, vulgar or inappropriate in some social and
                                                                cultural contexts, especially for children.</small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="lyricsTranscription">Lyrics
                                                                Transcription</label>
                                                            <textarea class="form-control" id="lyricsTranscription"
                                                                name="lyrics_transcription" rows="4"
                                                                placeholder="Include the lyrics transcription. It must be accurate and match the audio.&#10;Follow the proper song structure and separate the lyrical sections and changes within a song with line breaks."></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script>
                                                    // Add this script to handle instrumental checkbox
                                                    document.getElementById('instrumentalCheck').addEventListener('change', function (e) {
                                                        const isInstrumental = e.target.checked;
                                                        document.getElementById('languageSelect').disabled = isInstrumental;
                                                        document.getElementById('lyricsTranscription').disabled = isInstrumental;
                                                        document.querySelectorAll('[name="explicit_content"]').forEach(radio => {
                                                            radio.disabled = isInstrumental;
                                                        });

                                                        if (isInstrumental) {
                                                            document.getElementById('languageSelect').value = '';
                                                            document.getElementById('lyricsTranscription').value = '';
                                                            document.getElementById('explicit1').checked = true;
                                                        }
                                                    });
                                                </script>
                                            </form>

                                            <!-- Add these right before closing body tag -->
                                            <script
                                                src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                                            <script
                                                src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
                                            <link rel="stylesheet"
                                                href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css">

                                            <style>
                                                /* Hide selected items in Selectize input */
                                                .selectize-input.items>*:not(.selectize-input) {
                                                    display: none !important;
                                                }

                                                /* Make Selectize input look like normal form control */
                                                .selectize-input {
                                                    padding: 0.375rem 0.75rem !important;
                                                    border: 1px solid #ced4da !important;
                                                    border-radius: 0.25rem !important;
                                                    background-image: none !important;
                                                    box-shadow: none !important;
                                                }

                                                .selectize-input.items .item {
                                                    background: #e9ecef;
                                                    border-radius: 15px;
                                                    padding: 2px 10px;
                                                    margin: 2px;
                                                }

                                                .role-selector .selectize-input .item {
                                                    background: #e9ecef !important;
                                                    border-radius: 15px !important;
                                                    padding: 2px 10px !important;
                                                    margin: 2px !important;
                                                    display: inline-flex !important;
                                                    align-items: center;
                                                }

                                                .role-selector .selectize-input {
                                                    border: none !important;
                                                    padding: 0 !important;
                                                    box-shadow: none !important;
                                                    min-height: 34px;
                                                }
                                            </style>

                                            <script>
                                                $(document).ready(function () {
                                                    const $select = $('#artist-search').selectize({
                                                        plugins: ['remove_button'],
                                                        persist: false,
                                                        maxItems: null,
                                                        hideSelected: true,
                                                        valueField: 'id',
                                                        labelField: 'text',
                                                        searchField: 'text',
                                                        render: {
                                                            item: function () {
                                                                return '<div style="display: none;"></div>';
                                                            },
                                                            option: function (data) {
                                                                return `<div>${data.text}</div>`;
                                                            }
                                                        },
                                                        onItemAdd: function (value) {
                                                            this.setTextboxValue('');
                                                            updateSelectedTable();
                                                            updateHiddenInput();
                                                        },
                                                        onItemRemove: function (value) {
                                                            updateSelectedTable();
                                                            updateHiddenInput();
                                                        }
                                                    });

                                                    function updateSelectedTable() {
                                                        const selectize = $select[0].selectize;
                                                        const $tbody = $('#selected-artists-table tbody').empty();

                                                        selectize.items.forEach(id => {
                                                            const item = selectize.options[id];
                                                            $tbody.append(`
                <tr data-id="${id}">
                    <td>${item.text}</td>
                    <td>
                        <select class="role-selector" multiple>
                            <option value="composer">Composer</option>
                            <option value="lyricist">Lyricist</option>
                            <option value="producer">Producer</option>
                            <option value="performer">Performer</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-link text-danger p-0" 
                                onclick="removeArtist('${id}')">
                            Remove
                        </button>
                    </td>
                </tr>
            `);

                                                            // Initialize role selector with proper config
                                                            $('.role-selector:last').selectize({
                                                                plugins: ['remove_button'],
                                                                delimiter: ',',
                                                                persist: false,
                                                                create: false,
                                                                render: {
                                                                    item: function (data) {
                                                                        return `<div class="item">${data.text}</div>`;
                                                                    },
                                                                    option: function (data) {
                                                                        return `<div>${data.text}</div>`;
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    }

                                                    function updateHiddenInput() {
                                                        const artistData = [];
                                                        $('#selected-artists-table tbody tr').each(function () {
                                                            const id = $(this).data('id');
                                                            const roles = $(this).find('.role-selector').val() || [];
                                                            artistData.push({ id: id, roles: roles });
                                                        });
                                                        $('#selected-artists').val(JSON.stringify(artistData));
                                                    }
                                                });


                                                function removeArtist(id) {
                                                    const selectize = $('#artist-search')[0].selectize;
                                                    selectize.removeItem(id);
                                                    selectize.refreshOptions();
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header"><i class="zmdi zmdi-collection-music"></i> Your Tracks
                                    <div class="card-action"></div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Track ID</th>
                                                    <th>isrc</th>
                                                    <th>name</th>
                                                    <th>Album</th>
                                                    <th>Artist</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($trackList as &$tr) {
                                                    $albName = getRelease($_SESSION["userwtf"], 0, $tr->id)->name;
                                                    echo '
                                        <tr>
                                        <td>' . ($tr->id) . '</td>
                                        <td>' . ($tr->isrc ? $tr->isrc : "[NULL]") . '</td>
                                        <td>' . ($tr->name ? $tr->name : "(draft)") . '</td>
                                        <td>' . ($albName ? $albName : "[NULL]") . '</td>
                                        <td>' . ($tr->artistname ? $tr->artistname : "[NULL]") . '</td>
                                        <td>
                                            <a onclick="" href="?trackID=' . $tr->id . '">Edit</a> / 
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