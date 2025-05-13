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
    $artist = fetchArtist($_SESSION["userwtf"]);
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
    <link rel="stylesheet" href="app.css">
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
                            <div class="row">
                                <!-- Upload Card (1 part) -->
                                <div class="col-12 col-md-3 mb-4">
                                    <div class="card h-100"> <!-- Added h-100 for equal height -->
                                        <div class="card-header">
                                            <i class="zmdi zmdi-cloud-upload"></i> Upload File
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <div id="dnarea" class="row"
                                                style="align: center; display: flex; justify-content: center;">
                                                <input type="file" id="filee" accept="audio/*" />
                                                <label for="filee" id="ok">
                                                    <span id="texttt"><i class="zmdi zmdi-file-plus"></i> Drop
                                                        audio file here...
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="upload-status mt-3">
                                                <span id="status">Waiting for file input...</span>
                                                <div class="progress mt-2">
                                                    <div class="progress-bar" id="progbar"></div>
                                                </div>
                                            </div>
                                            <audio id="output-video" crossorigin controls class="mt-3"
                                                style="width:100%"></audio>
                                            <button class="btn btn-primary mt-auto" id="uploadBtn" disabled>
                                                <i class="zmdi zmdi-plus-square"></i> Upload
                                            </button>
                                            <audio src="noti.wav" style="display:none" preload="auto"
                                                id="notiSound"></audio>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-9">
                                    <div class="card h-100">
                                        <div class="card-header"><i class="zmdi zmdi-collection-music"></i> Your Tracks
                                            <div class="card-action"></div>
                                        </div>
                                            <table class="table table-responsive align-items-center table-flush table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
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
  <a href="#" data-toggle="modal" data-target="#editTrackModal" data-trackid="' . $tr->id . '">Edit</a> / 
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
                            <!-- Edit Track Modal -->
                            <div class="modal fade" id="editTrackModal" tabindex="-1" role="dialog"
                                aria-labelledby="editTrackModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTrackModalLabel"><i
                                                    class="zmdi zmdi-info-outline"></i> Edit Track Metadata</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                                            <form method="POST" action="">
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
                                                                    name="albumtitle" placeholder="" value=""
                                                                    list="primary-genre-option-list">
                                                            </div>
                                                            <div class="form-group col-md-6 mb-3">
                                                                <label class="d-block mb-1">Secondary Genre
                                                                    (optional)</label>
                                                                <input type="text" class="form-control" name="isrc"
                                                                    placeholder="Optional"
                                                                    value="<?php echo $track->isrc; ?>"
                                                                    list="primary-genre-option-list">
                                                            </div>
                                                        </div>
                                                        <datalist id="primary-genre-option-list">
                                                            <option data-id="0" value="Select primary genre">
                                                            </option>
                                                            <option data-id="1" value="Alternative"></option>
                                                            <option data-id="2" value="Alternative/Experimental">
                                                            </option>
                                                            <option data-id="4" value="Alternative/Gothic"></option>
                                                            <option data-id="5" value="Alternative/Grunge"></option>
                                                            <option data-id="3" value="Alternative/Indie Pop">
                                                            </option>
                                                            <option data-id="6" value="Alternative/Indie Rock">
                                                            </option>
                                                            <option data-id="7" value="Alternative/Rock"></option>
                                                            <option data-id="8" value="Ambient/New Age"></option>
                                                            <option data-id="9" value="Ambient/New Age/Meditation">
                                                            </option>
                                                            <option data-id="11" value="Blues"></option>
                                                            <option data-id="12" value="Blues/Contemporary Blues">
                                                            </option>
                                                            <option data-id="13" value="Blues/New Orleans Blues">
                                                            </option>
                                                            <option data-id="14" value="Blues/Traditional Blues">
                                                            </option>
                                                            <option data-id="15" value="Children's Music"></option>
                                                            <option data-id="16" value="Children's Music/Classic">
                                                            </option>
                                                            <option data-id="17" value="Children's Music/Holiday">
                                                            </option>
                                                            <option data-id="18" value="Children's Music/Stories">
                                                            </option>
                                                            <option data-id="19" value="Classical"></option>
                                                            <option data-id="20" value="Classical/Antique"></option>
                                                            <option data-id="21" value="Classical/Baroque"></option>
                                                            <option data-id="23" value="Classical/Chamber"></option>
                                                            <option data-id="22" value="Classical/Concert"></option>
                                                            <option data-id="24" value="Classical/Modern Compositions">
                                                            </option>
                                                            <option data-id="25" value="Classical/Opera"></option>
                                                            <option data-id="26" value="Classical/Orchestral">
                                                            </option>
                                                            <option data-id="27" value="Classical/Piano"></option>
                                                            <option data-id="28" value="Classical/Romantic">
                                                            </option>
                                                            <option data-id="29" value="Comedy"></option>
                                                            <option data-id="30" value="Country"></option>
                                                            <option data-id="31" value="Country/Bluegrass"></option>
                                                            <option data-id="32" value="Country/Contemporary">
                                                            </option>
                                                            <option data-id="33" value="Country/Honky Tonk">
                                                            </option>
                                                            <option data-id="34" value="Country/Nashville"></option>
                                                            <option data-id="35" value="Country/Pop"></option>
                                                            <option data-id="36" value="Country/Square Dance">
                                                            </option>
                                                            <option data-id="37" value="Easy Listening"></option>
                                                            <option data-id="38"
                                                                value="Easy Listening/Bar Jazz/Cocktail"></option>
                                                            <option data-id="39" value="Easy Listening/Bossa Nova">
                                                            </option>
                                                            <option data-id="40" value="Easy Listening/Lounge">
                                                            </option>
                                                            <option data-id="41" value="Easy Listening/Traditional">
                                                            </option>
                                                            <option data-id="42" value="Electronic"></option>
                                                            <option data-id="72782" value="Electronic/Acid House">
                                                            </option>
                                                            <option data-id="43" value="Electronic/Breaks"></option>
                                                            <option data-id="44" value="Electronic/Broken beat">
                                                            </option>
                                                            <option data-id="45" value="Electronic/Chill Out">
                                                            </option>
                                                            <option data-id="48"
                                                                value="Electronic/DJ Tools/Sample Packs"></option>
                                                            <option data-id="46" value="Electronic/Dance"></option>
                                                            <option data-id="47" value="Electronic/Deep House">
                                                            </option>
                                                            <option data-id="49"
                                                                value="Electronic/Downtempo - experimental">
                                                            </option>
                                                            <option data-id="50" value="Electronic/Drum &amp; Bass">
                                                            </option>
                                                            <option data-id="51"
                                                                value="Electronic/Dub/Reggae/Dancehall"></option>
                                                            <option data-id="52" value="Electronic/Dubstep/Grime">
                                                            </option>
                                                            <option data-id="53" value="Electronic/Electro House">
                                                            </option>
                                                            <option data-id="54" value="Electronic/Glitch Hop">
                                                            </option>
                                                            <option data-id="55" value="Electronic/Hard Dance">
                                                            </option>
                                                            <option data-id="56" value="Electronic/Hard Techno">
                                                            </option>
                                                            <option data-id="57" value="Electronic/Hardcore">
                                                            </option>
                                                            <option data-id="58" value="Electronic/Hardstyle">
                                                            </option>
                                                            <option data-id="59" value="Electronic/House"></option>
                                                            <option data-id="61"
                                                                value="Electronic/Indie Dance/Nu Disco"></option>
                                                            <option data-id="60" value="Electronic/Jazz"></option>
                                                            <option data-id="62" value="Electronic/Minimal">
                                                            </option>
                                                            <option data-id="63" value="Electronic/Pop Trance">
                                                            </option>
                                                            <option data-id="64" value="Electronic/Progressive House">
                                                            </option>
                                                            <option data-id="65" value="Electronic/Psy-Trance">
                                                            </option>
                                                            <option data-id="66" value="Electronic/Tech House">
                                                            </option>
                                                            <option data-id="67" value="Electronic/Techno"></option>
                                                            <option data-id="68" value="Electronic/Trance"></option>
                                                            <option data-id="69" value="Electronic/Trip Hop">
                                                            </option>
                                                            <option data-id="70" value="Experimental"></option>
                                                            <option data-id="51491" value="Fitness&amp;Workout">
                                                            </option>
                                                            <option data-id="24761" value="Flamenco"></option>
                                                            <option data-id="71" value="Folk"></option>
                                                            <option data-id="72" value="Funk/R&amp;B"></option>
                                                            <option data-id="73" value="Hip-Hop/Rap"></option>
                                                            <option data-id="74"
                                                                value="Hip-Hop/Rap/Gangsta &amp; Hardcore"></option>
                                                            <option data-id="75" value="Holiday/Christmas"></option>
                                                            <option data-id="76" value="Inspirational"></option>
                                                            <option data-id="77" value="Jazz"></option>
                                                            <option data-id="81" value="Jazz/Bebop"></option>
                                                            <option data-id="82" value="Jazz/Big Band"></option>
                                                            <option data-id="65129" value="Jazz/Brazilian Jazz">
                                                            </option>
                                                            <option data-id="83" value="Jazz/Classic"></option>
                                                            <option data-id="84" value="Jazz/Contemporary"></option>
                                                            <option data-id="78" value="Jazz/Dixie/Rag Time">
                                                            </option>
                                                            <option data-id="85" value="Jazz/Free Jazz"></option>
                                                            <option data-id="86" value="Jazz/Fusion"></option>
                                                            <option data-id="87" value="Jazz/Jazz Funk"></option>
                                                            <option data-id="79" value="Jazz/Latin Jazz"></option>
                                                            <option data-id="88" value="Jazz/Nu Jazz/Acid Jazz">
                                                            </option>
                                                            <option data-id="89" value="Jazz/Smooth Jazz"></option>
                                                            <option data-id="90" value="Jazz/Swing"></option>
                                                            <option data-id="91" value="Jazz/Traditional"></option>
                                                            <option data-id="80" value="Jazz/World"></option>
                                                            <option data-id="51525" value="Karaoke"></option>
                                                            <option data-id="92" value="Latin"></option>
                                                            <option data-id="51497" value="Latin/Bachata"></option>
                                                            <option data-id="51498" value="Latin/Banda"></option>
                                                            <option data-id="51499" value="Latin/Big Band"></option>
                                                            <option data-id="51500" value="Latin/Bolero"></option>
                                                            <option data-id="93" value="Latin/Bossa Nova"></option>
                                                            <option data-id="94" value="Latin/Brasil/Tropical">
                                                            </option>
                                                            <option data-id="51501" value="Latin/Christian">
                                                            </option>
                                                            <option data-id="51502" value="Latin/Conjunto"></option>
                                                            <option data-id="51503" value="Latin/Corridos"></option>
                                                            <option data-id="51504" value="Latin/Cuban"></option>
                                                            <option data-id="51505" value="Latin/Cumbia"></option>
                                                            <option data-id="51506" value="Latin/Duranguense">
                                                            </option>
                                                            <option data-id="51507" value="Latin/Electronica">
                                                            </option>
                                                            <option data-id="51508" value="Latin/Grupero"></option>
                                                            <option data-id="51509" value="Latin/Hip Hop"></option>
                                                            <option data-id="51510" value="Latin/Latin Rap">
                                                            </option>
                                                            <option data-id="51511" value="Latin/Mambo"></option>
                                                            <option data-id="51512" value="Latin/Mariachi"></option>
                                                            <option data-id="51514" value="Latin/Norteño"></option>
                                                            <option data-id="95" value="Latin/Pop"></option>
                                                            <option data-id="51515" value="Latin/Ranchera"></option>
                                                            <option data-id="51516" value="Latin/Reggaeton">
                                                            </option>
                                                            <option data-id="75453" value="Latin/Regional Mexicano">
                                                            </option>
                                                            <option data-id="96" value="Latin/Rock en Español">
                                                            </option>
                                                            <option data-id="51517" value="Latin/Salsa"></option>
                                                            <option data-id="97" value="Latin/Salsa/Merengue">
                                                            </option>
                                                            <option data-id="51518" value="Latin/Sierreño"></option>
                                                            <option data-id="51519" value="Latin/Sonidero"></option>
                                                            <option data-id="51520" value="Latin/Tango"></option>
                                                            <option data-id="51521" value="Latin/Tejano"></option>
                                                            <option data-id="51522" value="Latin/Tierra Caliente">
                                                            </option>
                                                            <option data-id="51523" value="Latin/Traditional Mexican">
                                                            </option>
                                                            <option data-id="51524" value="Latin/Vallenato">
                                                            </option>
                                                            <option data-id="98" value="New Age"></option>
                                                            <option data-id="99" value="Pop"></option>
                                                            <option data-id="100" value="Pop/Contemporary/Adult">
                                                            </option>
                                                            <option data-id="101" value="Pop/J-Pop"></option>
                                                            <option data-id="102" value="Pop/K-Pop"></option>
                                                            <option data-id="75450" value="Pop/Mandopop"></option>
                                                            <option data-id="103" value="Pop/Singer Songwriter">
                                                            </option>
                                                            <option data-id="75449" value="Punk"></option>
                                                            <option data-id="104" value="R&amp;B"></option>
                                                            <option data-id="105" value="Reggae"></option>
                                                            <option data-id="106" value="Rock"></option>
                                                            <option data-id="75462" value="Rock/Black Metal">
                                                            </option>
                                                            <option data-id="75457" value="Rock/Blues-Rock">
                                                            </option>
                                                            <option data-id="107" value="Rock/Brit-Pop"></option>
                                                            <option data-id="75459" value="Rock/British Invasion">
                                                            </option>
                                                            <option data-id="75460" value="Rock/Chinese Rock">
                                                            </option>
                                                            <option data-id="108" value="Rock/Classic"></option>
                                                            <option data-id="75461" value="Rock/Death Metal">
                                                            </option>
                                                            <option data-id="109" value="Rock/Glam Rock"></option>
                                                            <option data-id="75463" value="Rock/Hair Metal">
                                                            </option>
                                                            <option data-id="111" value="Rock/Hard Rock"></option>
                                                            <option data-id="110" value="Rock/Heavy Metal"></option>
                                                            <option data-id="75465" value="Rock/Jam Bands"></option>
                                                            <option data-id="75466" value="Rock/Korean Rock">
                                                            </option>
                                                            <option data-id="112" value="Rock/Progressive"></option>
                                                            <option data-id="75467" value="Rock/Psychedelic">
                                                            </option>
                                                            <option data-id="113" value="Rock/Rock 'n' Roll">
                                                            </option>
                                                            <option data-id="75468" value="Rock/Rockabilly">
                                                            </option>
                                                            <option data-id="75469" value="Rock/Russian Rock">
                                                            </option>
                                                            <option data-id="114" value="Rock/Singer/Songwriter">
                                                            </option>
                                                            <option data-id="75470" value="Rock/Southern Rock">
                                                            </option>
                                                            <option data-id="75471" value="Rock/Surf"></option>
                                                            <option data-id="75472" value="Rock/Tex-Mex"></option>
                                                            <option data-id="75473" value="Rock/Turkish Rock">
                                                            </option>
                                                            <option data-id="75448" value="Ska"></option>
                                                            <option data-id="115" value="Soul"></option>
                                                            <option data-id="116" value="Soundtrack"></option>
                                                            <option data-id="117" value="Soundtrack/Anime"></option>
                                                            <option data-id="118" value="Soundtrack/Musical">
                                                            </option>
                                                            <option data-id="119" value="Soundtrack/TV"></option>
                                                            <option data-id="120" value="Spiritual"></option>
                                                            <option data-id="121" value="Spiritual/Christian">
                                                            </option>
                                                            <option data-id="122" value="Spiritual/Gospel"></option>
                                                            <option data-id="126" value="Spiritual/Gregorian">
                                                            </option>
                                                            <option data-id="123" value="Spiritual/India"></option>
                                                            <option data-id="124" value="Spiritual/Judaica">
                                                            </option>
                                                            <option data-id="125" value="Spiritual/World"></option>
                                                            <option data-id="127" value="Spoken Word/Speeches">
                                                            </option>
                                                            <option data-id="75454" value="Trap"></option>
                                                            <option data-id="75455" value="Trap/Future Bass">
                                                            </option>
                                                            <option data-id="75456" value="Trap/Future Bass/Twerk">
                                                            </option>
                                                            <option data-id="128" value="Vocal/Nostalgia"></option>
                                                            <option data-id="129" value="World"></option>
                                                            <option data-id="130" value="World/African"></option>
                                                            <option data-id="75474"
                                                                value="World/African/African Dancehall"></option>
                                                            <option data-id="75475"
                                                                value="World/African/African Reggae"></option>
                                                            <option data-id="75476" value="World/African/Afrikaans">
                                                            </option>
                                                            <option data-id="75451" value="World/African/Afro-Beat">
                                                            </option>
                                                            <option data-id="75478" value="World/African/Afro-Folk">
                                                            </option>
                                                            <option data-id="75479" value="World/African/Afro-Fusion">
                                                            </option>
                                                            <option data-id="75480" value="World/African/Afro-House">
                                                            </option>
                                                            <option data-id="75452" value="World/African/Afro-Pop">
                                                            </option>
                                                            <option data-id="75482" value="World/African/Afro-Soul">
                                                            </option>
                                                            <option data-id="75483" value="World/African/Afrobeats">
                                                            </option>
                                                            <option data-id="75484" value="World/African/Alte">
                                                            </option>
                                                            <option data-id="75485" value="World/African/Amapiano">
                                                            </option>
                                                            <option data-id="75486" value="World/African/Benga">
                                                            </option>
                                                            <option data-id="75487" value="World/African/Bongo-Flava">
                                                            </option>
                                                            <option data-id="75488" value="World/African/Coupé-Décalé">
                                                            </option>
                                                            <option data-id="75489" value="World/African/Gqom">
                                                            </option>
                                                            <option data-id="75490" value="World/African/Highlife">
                                                            </option>
                                                            <option data-id="75491" value="World/African/Kizomba">
                                                            </option>
                                                            <option data-id="75492" value="World/African/Kuduro">
                                                            </option>
                                                            <option data-id="75493" value="World/African/Kwaito">
                                                            </option>
                                                            <option data-id="75494" value="World/African/Maskandi">
                                                            </option>
                                                            <option data-id="75495" value="World/African/Mbalax">
                                                            </option>
                                                            <option data-id="75496" value="World/African/Ndombolo">
                                                            </option>
                                                            <option data-id="75497"
                                                                value="World/African/Shangaan Electro"></option>
                                                            <option data-id="75498" value="World/African/Soukous">
                                                            </option>
                                                            <option data-id="75499" value="World/African/Taarab">
                                                            </option>
                                                            <option data-id="75500" value="World/African/Zouglou">
                                                            </option>
                                                            <option data-id="51493" value="World/Americas/Argentina">
                                                            </option>
                                                            <option data-id="131" value="World/Americas/Brazilian">
                                                            </option>
                                                            <option data-id="65131"
                                                                value="World/Americas/Brazilian/Axé"></option>
                                                            <option data-id="75501"
                                                                value="World/Americas/Brazilian/Baile Funk">
                                                            </option>
                                                            <option data-id="65126"
                                                                value="World/Americas/Brazilian/Black Music">
                                                            </option>
                                                            <option data-id="132"
                                                                value="World/Americas/Brazilian/Bossa Nova">
                                                            </option>
                                                            <option data-id="65133"
                                                                value="World/Americas/Brazilian/Chorinho"></option>
                                                            <option data-id="65128"
                                                                value="World/Americas/Brazilian/Folk"></option>
                                                            <option data-id="75502"
                                                                value="World/Americas/Brazilian/Forró"></option>
                                                            <option data-id="75503"
                                                                value="World/Americas/Brazilian/Frevo"></option>
                                                            <option data-id="65127"
                                                                value="World/Americas/Brazilian/Funk Carioca">
                                                            </option>
                                                            <option data-id="65130"
                                                                value="World/Americas/Brazilian/MPB"></option>
                                                            <option data-id="65135"
                                                                value="World/Americas/Brazilian/Marchinha"></option>
                                                            <option data-id="65136"
                                                                value="World/Americas/Brazilian/Pagode"></option>
                                                            <option data-id="65132"
                                                                value="World/Americas/Brazilian/Samba"></option>
                                                            <option data-id="65137"
                                                                value="World/Americas/Brazilian/Samba-Rock">
                                                            </option>
                                                            <option data-id="65139"
                                                                value="World/Americas/Brazilian/Samba-de-Raiz">
                                                            </option>
                                                            <option data-id="65134"
                                                                value="World/Americas/Brazilian/Samba-enredo">
                                                            </option>
                                                            <option data-id="65138"
                                                                value="World/Americas/Brazilian/Sambalanço">
                                                            </option>
                                                            <option data-id="75504"
                                                                value="World/Americas/Brazilian/Sertanejo"></option>
                                                            <option data-id="133" value="World/Americas/Cajun-Creole">
                                                            </option>
                                                            <option data-id="134" value="World/Americas/Calypso">
                                                            </option>
                                                            <option data-id="51494" value="World/Americas/Colombia">
                                                            </option>
                                                            <option data-id="135" value="World/Americas/Cuba-Caribbean">
                                                            </option>
                                                            <option data-id="136" value="World/Americas/Mexican">
                                                            </option>
                                                            <option data-id="137" value="World/Americas/North-American">
                                                            </option>
                                                            <option data-id="51495" value="World/Americas/Panama">
                                                            </option>
                                                            <option data-id="51496" value="World/Americas/Peru">
                                                            </option>
                                                            <option data-id="138" value="World/Americas/South-American">
                                                            </option>
                                                            <option data-id="139" value="World/Arabic"></option>
                                                            <option data-id="144" value="World/Asian/Central Asia">
                                                            </option>
                                                            <option data-id="140" value="World/Asian/China">
                                                            </option>
                                                            <option data-id="141" value="World/Asian/Indian">
                                                            </option>
                                                            <option data-id="75506" value="World/Asian/Indian/Assamese">
                                                            </option>
                                                            <option data-id="75507" value="World/Asian/Indian/Bengali">
                                                            </option>
                                                            <option data-id="75508"
                                                                value="World/Asian/Indian/Bengali/Rabindra Sangeet">
                                                            </option>
                                                            <option data-id="75509" value="World/Asian/Indian/Bhojpuri">
                                                            </option>
                                                            <option data-id="142" value="World/Asian/Indian/Bollywood">
                                                            </option>
                                                            <option data-id="75511"
                                                                value="World/Asian/Indian/Carnatic Classical">
                                                            </option>
                                                            <option data-id="75512"
                                                                value="World/Asian/Indian/Devotional &amp; Spiritual">
                                                            </option>
                                                            <option data-id="75513" value="World/Asian/Indian/Ghazals">
                                                            </option>
                                                            <option data-id="75514" value="World/Asian/Indian/Gujarati">
                                                            </option>
                                                            <option data-id="75515" value="World/Asian/Indian/Haryanvi">
                                                            </option>
                                                            <option data-id="75516"
                                                                value="World/Asian/Indian/Hindustani Classical">
                                                            </option>
                                                            <option data-id="75517"
                                                                value="World/Asian/Indian/Indian Classical">
                                                            </option>
                                                            <option data-id="75518"
                                                                value="World/Asian/Indian/Indian Folk"></option>
                                                            <option data-id="75519"
                                                                value="World/Asian/Indian/Indian Pop"></option>
                                                            <option data-id="75520" value="World/Asian/Indian/Kannada">
                                                            </option>
                                                            <option data-id="75521"
                                                                value="World/Asian/Indian/Malayalam"></option>
                                                            <option data-id="75522" value="World/Asian/Indian/Marathi">
                                                            </option>
                                                            <option data-id="75523" value="World/Asian/Indian/Odia">
                                                            </option>
                                                            <option data-id="75524" value="World/Asian/Indian/Punjabi">
                                                            </option>
                                                            <option data-id="75525"
                                                                value="World/Asian/Indian/Punjabi/Punjabi Pop">
                                                            </option>
                                                            <option data-id="75526"
                                                                value="World/Asian/Indian/Rajasthani"></option>
                                                            <option data-id="75527"
                                                                value="World/Asian/Indian/Regional Indian"></option>
                                                            <option data-id="75531" value="World/Asian/Indian/Sufi">
                                                            </option>
                                                            <option data-id="75528" value="World/Asian/Indian/Tamil">
                                                            </option>
                                                            <option data-id="75529" value="World/Asian/Indian/Telugu">
                                                            </option>
                                                            <option data-id="75530" value="World/Asian/Indian/Urdu">
                                                            </option>
                                                            <option data-id="143" value="World/Asian/Japan">
                                                            </option>
                                                            <option data-id="145" value="World/Asian/South Asia">
                                                            </option>
                                                            <option data-id="146" value="World/Australian/Pacific">
                                                            </option>
                                                            <option data-id="147" value="World/Ethnic"></option>
                                                            <option data-id="148" value="World/Europe/Eastern">
                                                            </option>
                                                            <option data-id="149" value="World/Europe/French">
                                                            </option>
                                                            <option data-id="150" value="World/Europe/German">
                                                            </option>
                                                            <option data-id="151" value="World/Europe/Northern">
                                                            </option>
                                                            <option data-id="152" value="World/Europe/Southern">
                                                            </option>
                                                            <option data-id="51492" value="World/Europe/Spain">
                                                            </option>
                                                            <option data-id="153" value="World/Europe/Western">
                                                            </option>
                                                            <option data-id="154" value="World/Mediterranean/Greece">
                                                            </option>
                                                            <option data-id="155" value="World/Mediterranean/Italy">
                                                            </option>
                                                            <option data-id="156" value="World/Mediterranean/Spain">
                                                            </option>
                                                            <option data-id="157" value="World/Russian"></option>
                                                            <option data-id="158" value="World/Worldbeat"></option>
                                                        </datalist>
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
                                                                    Indicate the minute at which the track should
                                                                    start
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
                                                        <input type="text" class="form-control" id="artist-search"
                                                            name="artist-search" placeholder="" value=""
                                                            list="artist-list">
                                                        <datalist id="artist-list">
                                                            <?php
                                                            foreach ($artist as &$ar) {
                                                                echo '<option value="' . $ar->id . '">' . $ar->name . '</option>';
                                                            }
                                                            ?>
                                                        </datalist>
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
                                                        <script>
                                                            var artistArray = [];
                                                            const arsearch = document.getElementById("artist-search");
                                                            const table = document.getElementById("selected-artists-table");
                                                            var index = 0;
                                                            arsearch.addEventListener("change", function (e) {
                                                                console.log(document.querySelector('option[value="' + e.target.value + '"]').label);
                                                                $('#selected-artists-table tbody').append(`
                                                                    <tr data-id="${e.target.value}" id="${index}">
                                                                        <td>${document.querySelector('option[value="' + e.target.value + '"]').label}</td>
                                                                        <td>
                                                                            <select class="role-selector" multiple>
                                                                                <option value="primary">Primary Artist</option>
                                                                                <option value="feat">Featured Artist</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <button class="btn btn-link text-danger p-0" onclick="removeArtist(this)">
                                                                                Remove
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                `);
                                                                artistArray.push({
                                                                    "id": e.target.value,
                                                                    "name": document.querySelector('option[value="' + e.target.value + '"]').label,
                                                                    "index": index
                                                                });
                                                                e.target.value = "";
                                                                index++;
                                                                document.getElementById("selected-artists").value = JSON.stringify(artistArray);
                                                                $(".role-selector").selectize({
                                                                    maxItems: null,
                                                                    valueField: 'id',
                                                                    labelField: 'title',
                                                                    searchField: 'title',
                                                                    options: [
                                                                        { id: 1, title: 'Primary Artist', value: "primary" },
                                                                        { id: 2, title: 'Featured Artist', value: 'feat' },
                                                                        { id: 3, title: 'Composer', value: 'composer' },
                                                                        { id: 4, title: 'Remixer', value: 'remixer' },
                                                                        { id: 5, title: 'Producer', value: 'producer' },
                                                                        { id: 6, title: 'Arranger', value: 'arranger' },
                                                                        { id: 7, title: 'Lyricist', value: 'lyricist' }
                                                                    ],
                                                                    create: false
                                                                });
                                                            });
                                                            function removeArtist(r) {
                                                                //artistArray;
                                                                var thisRow = r.parentNode.parentNode;
                                                                var returnedIndex = ((e) => {
                                                                    return parseInt(e.index) == parseInt(thisRow.id);
                                                                });
                                                                artistArray.splice(artistArray.findIndex(returnedIndex), 1);
                                                                table.deleteRow(thisRow.rowIndex);
                                                                document.getElementById("selected-artists").value = JSON.stringify(artistArray);
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="selected_artists" id="selected-artists">
                                                <!-- Lyrics -->
                                                <div class="card mt-3">
                                                    <div class="card-header">Lyrics</div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Language of the Lyrics</label>
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="instrumentalCheck" name="is_instrumental"
                                                                    onchange="toggleFields()">
                                                                <label class="custom-control-label"
                                                                    for="instrumentalCheck">This track is
                                                                    instrumental</label>
                                                            </div>
                                                            <select class="form-control" id="languageSelect"
                                                                name="lyrics_language">
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
                                                            <small class="form-text text-muted">Select the language
                                                                of
                                                                the lyrics or if it is an instrumental
                                                                track.</small>
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
                                                                    for="explicit3">Cleaned Version - Version of
                                                                    another
                                                                    track where the explicit content has been
                                                                    removed</label>
                                                            </div>
                                                            <small class="form-text text-muted">Indicate whether the
                                                                lyrics contain words or phrases that are considered
                                                                offensive, vulgar or inappropriate in some social
                                                                and
                                                                cultural contexts, especially for children.</small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="lyricsTranscription">Lyrics
                                                                Transcription</label>
                                                            <textarea class="form-control" id="lyricsTranscription"
                                                                name="lyrics_transcription" rows="4"
                                                                placeholder="Include the lyrics transcription. It must be accurate and match the audio.&#10;Follow the proper song structure and separate the lyrical sections and changes within a song with line breaks."></textarea>
                                                        </div>
                                                        <script>
                                                            function toggleFields() {
                                                                const isChecked = document.getElementById('instrumentalCheck').checked;
                                                                const fields = document.querySelectorAll(':scope > .form-control, .custom-control-input[type="radio"]');

                                                                fields.forEach(field => {
                                                                    if (field.id !== 'instrumentalCheck') {
                                                                        field.disabled = isChecked;
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
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
    <!-- Chart js -->

    <script src="/assets/plugins/Chart.js/Chart.min.js"></script>
    <!-- Something... -->
    <script src="app.js"></script>
    <script src="/assets/js/app-script.js"></script>
    <link rel="stylesheet" href="/assets/css/plyr.css">
    <script src="/assets/js/plyr.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        n = new Date();
        document.getElementById("cccccyear").innerHTML = n.getFullYear();
    </script>
</body>

</html>