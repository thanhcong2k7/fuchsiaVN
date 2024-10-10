<?php
	session_start();
	if (!isset($_SESSION["userwtf"])){
		header("Location: ./home/");
	}
	$conn = mysqli_connect("localhost", "lunaris_real", "1993Ak1993@", "lunaris_real");
	$res = $conn->query("SELECT * FROM user WHERE userID=".$_SESSION["userwtf"].";");
	while($row=$res->fetch_assoc()){
		$_SESSION["name"]=$row["name"];
		$_SESSION["labelName"]=$row["labelName"];
		$usertype=$row["usertype"];
	}
	$res = $conn->query("SELECT * FROM album;");
	$albumID=0;
	while($row=$res->fetch_assoc()){
		$albumID=$row["albumID"];
	}
	if (!isset($_GET["albumID"])){
		$albumID++;
		$res = $conn->query("INSERT INTO album (albumID,albumType,status,userID) VALUES (".$albumID.",1,0,".$_SESSION["userwtf"].");");
	} else
		$albumID=$_GET["albumID"];
	$artist=array();
	$artistid=array();
	$res = $conn->query("SELECT * FROM author WHERE userID=".$_SESSION["userwtf"].";");
	$artistCnt=0;
	while($row=$res->fetch_assoc()){
		$artistid[]=$row["authorID"];
		$artist[]=$row["authorName"];
		$artistCnt++;
	}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lunaris Media Group</title>
	<link rel="apple-touch-icon" sizes="76x76" href="./assets/img/favicon.png">
	<link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/nucleo-icons.css">
    <link rel="stylesheet" href="./assets/css/nucleo-svg.css">
    <link rel="stylesheet" href="./assets/css/soft-ui-dashboard.min.css?v=1.0.2">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="./assets/css/theme.css">
    <link rel="stylesheet" href="./assets/css/loopple/loopple.css">
</head>

<body class="g-sidenav-show">
    <nav class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start position-absolute ms-3 bg-white loopple-fixed-start" id="sidenav-main" data-sidebar="true" data-sidebar-value="53">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute right-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="javascript:;">
                <img src="https://loopple.s3.eu-west-3.amazonaws.com/images/XgAoIctIQCBqWufF09ViTDPagHqPyK4bXVjHLqfG.png" class="navbar-brand-img h-100" alt="...">
                <span class="ms-1 font-weight-bold">[<?php echo ($usertype==1?"Label":($usertype==2?"Artist":"Network"));?>] <strong><?php echo $_SESSION["labelName"]; ?></strong></span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href=".">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>shop </title>
                                <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                            <g id="shop-" transform="translate(0.000000, 148.000000)">
                                                <path class="color-background" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z" id="Path" opacity="0.598981585"></path>
                                                <path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="./discography.php">
                        <div class="icon icon-shape icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>office</title>
                                <i class="ni ni-chart-bar-32"></i>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Discography</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="artist.php">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>settings</title>
                                <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Rounded-Icons" transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                            <g id="settings" transform="translate(304.000000, 151.000000)">
                                                <polygon class="color-background" id="Path" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667"></polygon>
                                                <path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" id="Path" opacity="0.596981957"></path>
                                                <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Artist manager</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="track.php">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>settings</title>
                                <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Rounded-Icons" transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                            <g id="settings" transform="translate(304.000000, 151.000000)">
                                                <polygon class="color-background" id="Path" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667"></polygon>
                                                <path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" id="Path" opacity="0.596981957"></path>
                                                <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Audio manager</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./billing.php">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>credit-card</title>
                                <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Rounded-Icons" transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                            <g id="credit-card" transform="translate(453.000000, 454.000000)">
                                                <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" id="Path" opacity="0.593633743"></path>
                                                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z" id="Shape"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Billing</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:;">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>customer-support</title>
                                <i class="ni ni-circle-08"></i>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:;">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>document</title>
                                <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Rounded-Icons" transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                            <g id="document" transform="translate(154.000000, 300.000000)">
                                                <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" id="Path" opacity="0.603585379"></path>
                                                <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z" id="Shape"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="main-content" id="panel">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 mt-3 shadow-none border-radius-xl" id="navbarTop" data-navbar="true" data-navbar-value="49">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Lunaris</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Discography</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Release update/create form</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Type here...">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1" aria-hidden="true"></i>
                                <span class="d-sm-inline d-none"><?php echo $_SESSION["name"]; ?></span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell cursor-pointer" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="./assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New message</span> from Laur
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1" aria-hidden="true"></i>
                                                    13 minutes ago
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="./assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New album</span> by Travis Scott
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1" aria-hidden="true"></i>
                                                    1 day
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>credit-card</title>
                                                    <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g id="Rounded-Icons" transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                            <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                                                <g id="credit-card" transform="translate(453.000000, 454.000000)">
                                                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" id="Path" opacity="0.593633743"></path>
                                                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z" id="Shape"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    Payment successfully completed
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1" aria-hidden="true"></i>
                                                    2 days
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="card mb-4">
				<?php
					/*
					$res = $conn->query("SELECT * FROM track WHERE userID=".$_SESSION["userwtf"].";");
					$trackID=array();
					$fileName=array();
					$gID_track=array();
					while($row=$res->fetch_assoc()){
						$trackID[]=$row["trackID"];
						$fileName[]=$row["fileName"];
						$gID_track[]=$row["gID"];
					}*/
					$res=$conn->query("SELECT * FROM album WHERE albumID=".strval($albumID).";");
					$albumName="";
					$upc="";
					$artistRole="";
					while($row=$res->fetch_assoc()){
						$albumName=$row["albumName"];
						$statusID=$row["status"];
						$upc=strval($row["UPCNum"]);
						$artistrole=$row["artistRole"];
						$cline=$row["compLine"];
						$pline=$row["publishLine"];
						$wpu=$row["wpu"];
					}
				?>
                <div class="card-body">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-8">
								<h6 class="mb-0"><strong>Album ID: </strong><?php echo "LMG_".$_SESSION["userwtf"]."_".$albumID;?></h6>
							</div>
							<div class="col-4 text-end">
								<a href="#!" class="btn btn-sm bg-gradient-danger mb-0" data-bs-toggle="modal" data-bs-target="#submitmodal">Save/submit!</a>
							</div>
						</div>
					</div>
					<form action="./relproc.php" method="POST">
					<div class="row">
						<div class="col row">
							<div class="position-relative">
								<div class="blur-shadow-image">
								<center>
									<div id="drop-zone">
										<img id="imggg" src=<?php
											//if(isset($_GET["albumID"])){
												$res = $conn->query("SELECT artID FROM album WHERE albumID=".$albumID.";");
												$gID="";
												while($row=$res->fetch_assoc()){
													$gID=$row["artID"];
												}
												if($gID!=NULL)
													echo "https://lh3.googleusercontent.com/d/".$gID;
												else echo "./assets/img/1kx1k.jpg";
											
										?> alt="">
										<center><p id="dropTxt">Drop your artwork here. (1500x1500 or above)</p></center>
										<div id="changeornot" value="no"></div>
                                        <div name="albumID" value=<?php echo $albumID; ?> >
										<?php
											if(isset($_GET["albumID"]))
												echo "<script>const img = document.querySelector('#imggg');let p = document.querySelector('#dropTxt');img.style = 'display:block';p.style = 'display: none';</script>";
										?>
										<style>
										#drop-zone {
							                width: 300px;
											height: 300px;
											border: 2px dotted blue;
											display: flex;
											justify-content: center;
											align-items: center;
										}

										#imggg {
											object-fit: cover;
											width: 100%;
											height: 100%;
											display: none;
										}
										</style>
									</div>
								</center>
								</div>
							</div>
						</div>
						<div class="col ps-0 my-auto row">
							<div class="card-body text-left">
								<div class="p-md-0 pt-3">
									<h5 class="font-weight-bolder mb-0">Title: <?php echo $albumName?$albumName:"--";?></h5>
									<p class="text-uppercase text-sm font-weight-bold mb-2">UPC: <?php echo $upc?$upc:"--";?></p>
								</div>
								<p class="text-sm font-weight-bold mb-2">Status:
								<?php
									echo'
								<span class="badge badge-sm bg-gradient-'.($statusID==3?"warning":($statusID==2?"success":($statusID==0||$statusID==1?"secondary":"danger"))).'">'.($statusID==3?"warning erro":($statusID==2?"Accepted":($statusID==0?"Unfinished":($statusID==1?"Processing":"Rejected")))).'</span></p>';
								?></p>
								<script src="//code.jquery.com/jquery.min.js"></script>
								<script src="./assets/js/jquery.checkImageSize.js"></script>
								<input type="file" id="imgInp" data-min-width="3000" data-min-height="3000" class="btn btn-sm bg-gradient-primary" accept="image/x-png, image/jpeg" onchange="changefile()">
								<script>
								imgInp.onchange = evt => {
									const [file] = imgInp.files;
									if (file) {
										imggg.src = URL.createObjectURL(file);
										changeornot.setAttribute("value","yes");
										const myImage = new Image();
										myImage.src = URL.createObjectURL(file);
										console.log(myImage.width);
										console.log(myImage.height);
									}
								}
								$("input[type=file]").checkImageSize({minWidth:3000,minHeight:3000,maxWidth:10000,maxHeight:10000,showError:true,ignoreError:false});
								</script>
							</div>
						</div>
					</div>
						<div class="modal fade" id="submitmodal" tabindex="-1" role="dialog" aria-labelledby="eCxampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
								  <div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Submit now?</h5>
									<button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">&times;</span>
									</button>
								  </div>
								  <div class="modal-body">
									<p class="text-left">By submitting this form, you wont be able to make any changes during our checking process. Also, you can only edit this form again once it is accepted/rejected.</p>
								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
									<button name="btnSave" value="save" type="submit" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Save changes only</button>
									<button name="btnSubmit" type="submit" class="btn bg-gradient-danger" value="submit">Submit now</button>
								  </div>
								</div>
							</div>
						</div>
						<br />
						<h6 class="heading-small text-muted mb-4">Album metadata:</h6>
						<div>
						    <div class="container row">
						    <div class="col">
							<div class="row">
								<div class="form-group">
									<label class="form-control-label" for="input-username">Title:</label>
										<input name="relTitle" type="text" class="form-control" placeholder="Release title (Example: Alone, Faded, ...)" value=<?php echo '"'.($albumName?$albumName:"").'"';?>>
								</div>
							</div>
							<div class="w-100"></div>
							<div class="row">
								<div class="form-group">
									<label class="form-control-label" for="input-username">Artist(s):</label>
									<div id="dropdown-c">
										<div id="artist-0">
											<select class="form-control" name="arole-0">
												<option value="1">Primary</option>
												<option value="2">Featured</option>
												<option value="3">Remixer</option>
												<option value="4">Producer</option>
											</select>
											<select class="form-control" name="aname-0">
												<option>--Choose one--</option>
												<?php
													for($i=0; $i<count($artist); $i++)
														echo '<option value="'.$artistid[$i].'">'.$artist[$i].' - LMG_'.strval($_SESSION["userwtf"]).'_'.strval($artistid[$i]).'</option>';
												?>
											</select>
											<br />
										</div>
										<button type="button" class="btn bg-gradient-primary" id="add-dropdown">Add another artist</button>
										<script>
											j = 1;
											const addDropdownButton = document.getElementById("add-dropdown");
											addDropdownButton.addEventListener("click", function() {
												var elem = document.querySelector(`#artist-${j-1}`);
												var clone = elem.cloneNode(true);
												clone.id=`artist-${j}`;
												elem.after(clone);
                                                document.querySelector("div[id='artist-"+j.toString()+"'] select[name='arole-"+(j-1).toString()+"']").setAttribute('name',"arole-"+j.toString());
                                                document.querySelector("div[id='artist-"+j.toString()+"'] select[name='aname-"+(j-1).toString()+"']").setAttribute('name',"aname-"+j.toString());
                                                j=j+1;
											});
										</script>
									</div>
								</div>
							</div>
							</div>
							<div class="col">
							<div class="row">
							    <div class="form-group">
									<label class="form-control-label" for="input-username">Label name:</label>
									<input type="text" class="form-control" name="labelname" value="<?php echo $_SESSION["labelName"]; ?>" placeholder="Label Name" <?php echo ($usertype==1?"disabled":($usertype==2?"disabled":""));?>>
								</div>
							</div>
							<div class="row">
							    <div class="form-group">
									<label class="form-control-label" for="input-username">Copyright holder(s):</label>
									<input type="text" class="form-control" value="<?php echo $cline; ?>" name="cline" placeholder="Composition Copyright (actually (C) Line). Example: {Year: 2024,2023,...} {Artist name/Label name... Normally use primary artist name}">
									</div>
							
									<div class="form-group">
									<label class="form-control-label" for="input-username">Line holder(s):</label>
									<input type="text" class="form-control" value="<?php echo $pline; ?>" name="pline" placeholder="Sound Recording Copyright (actually (P) Line). Example: {Year: 2024,2023,...} {Artist name/Label name... Normally use label name}">
								        </div>	
								
							    <div class="form-group">
									<label class="form-control-label" for="input-username">WRITERS & PUBLISHERS(s):</label>
									<input type="text" class="form-control" value="<?php echo $wpu; ?>" name="cline" placeholder="Writers & Publishers">	
								</div>
							</div>
							<div class="row">
							    <div class="form-group">
									<label class="form-control-label" for="input-username">Choose your audio file:</label>
									<div id="dropdown-c">
										<div id="fileChoose">
											<select class="form-control" name="fileN">
												<option>--Choose one--</option>
													<?php
														$res=$conn->query("SELECT * FROM storage where userID=".$_SESSION["userwtf"].";");
														while ($row=$res->fetch_assoc()){
															$fID[]=$row["fileID"];
															$fName[]=$row["fName"];
														}
														for($i=0; $i<count($fID); $i++)
															echo '<option value="'.$fID[$i].'">'.$fName[$i].'</option>';
													?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<?
							    $res=$conn->query("SELECT relDate FROM album WHERE albumID=".$albumID.";");
							    while($row=$res->fetch_assoc())
							        $rDate=$row["relDate"];
							?>
							<div class="row">
							    <div class="form-group">
                                    <label for="example-datetime-local-input" class="form-control-label">Release Date</label>
                                    <input class="form-control" type="date" value="<?php echo $rDate;?>" name="rDate">
                                </div>
							</div>
							</div>
							</div>
							<?php
								$newRel=!isset($_GET["albumID"]);
								$n=0;
								$k=0;
								$artist_tmp=array();
								$arole_tmp=array();
								$fID_choose=0;
								if (!$newRel){
									$res = $conn->query("SELECT * FROM album WHERE albumID=".strval($albumID).";");
									while($row=$res->fetch_assoc()){
										$n=count(json_decode($row["authorID"]));
										$artist_tmp=json_decode($row["authorID"]);
										$arole_tmp=json_decode($row["artistRole"]);
										$fID_choose=$row["fileID"];
									}
								}
								for ($i=0; $i<$n; $i++){
									echo '<script>document.getElementById("add-dropdown").click();</script>';
									for ($j=0; $j<count($artist_tmp); $j++){
										$mergedTxtID="artist-".strval($k);
										$arole_str="";
										$ar_str="";
										for($l=0; $l<count($artist); $l++){
											if($artist_tmp[$i] == $artistid[$l]){
												$arole_str=$artist[$l];
												$ar_str=$artist[$l]." - LMG_".strval($_SESSION["userwtf"])."_".strval($artistid[$l]);
											}
										}
										//
										//echo "<script>alert('".$ar_str."');</script>";
										echo '<script>
											var options = document.querySelector(\'div[id="'.$mergedTxtID.'"] select[name="arole-'.strval($k).'"] \').options;
											for (var i = 0; i < options.length; i++) {
												if (options[i].text == "'.$arole_str.'") {
													options[i].selected = true;
													break;
												}
											}
										</script>';
										echo '<script>
											var options = document.querySelector(\'div[id="'.$mergedTxtID.'"] select[name="aname-'.strval($k).'"] \').options;
											for (var i = 0; i < options.length; i++) {
												if (options[i].text == "'.$ar_str.'") {
													options[i].selected = true;
													break;
												}
											}
											</script>';
										$k++;
									}
								}
								if ($fID_choose>0)
									echo '<script>
										var options = document.querySelector(\'div[id="fileChoose"] select[name="fileN"] \').options;
										for (var i = 0; i < options.length; i++) {
											if (options[i].text == "'.$fName[$fID_choose-1].'") {
												options[i].selected = true;
												console.log("'.$fID_choose.'");
												break;
											}
										}
									</script>';
							?>
						</div>
					</form>
				</div>
		</div>
    </div>
        <footer class="footer pt-3 pb-4">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © 2024,
                            <a href="." class="font-weight-bold text-capitalize" target="_blank"> lunaris media group</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link text-muted" target="_blank">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link pe-0 text-muted" target="_blank">License</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script src="./assets/js/plugins/Chart.extension.js"></script>
    <script src="./assets/js/soft-ui-dashboard.min.js?v=1.0.2"></script>
    <script src="./assets/js/loopple/loopple.js"></script>
</body>