<?php
session_start();
require '../assets/variables/sql.php';
if (!isset($_SESSION["userwtf"])) {
    //unset($_SESSION["userwtf"]);
    header("Location: /login/");
} else {
    $user = getUser($_SESSION["userwtf"]);
    $release = getRelease($_SESSION["userwtf"]);
    if (!$user) {
        unset($_SESSION["userwtf"]);
        echo "<script>window.location.href='/login/';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Dashboard of a Vietnamese indie distributor." />
    <meta name="author" content="fuchsia Media Group" />
    <title>Dashboard - fuchsia Media Group</title>
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
        <?php include '../components/sidebar.php'; ?>
        <!--End sidebar-wrapper-->

        <!--Start topbar header-->
        <?php include '../components/topbar.php'; ?>
        <!--End topbar header-->

        <div class="clearfix"></div>

        <div class="content-wrapper">
            <div class="container-fluid">
                hehe
            </div>
            <!--End wrapper-->

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
                var myVar = setInterval(function () {
                    myTimer()
                }, 1000);
                function myTimer() {
                    var d = new Date();
                    var t = d.toLocaleTimeString();
                    hr = d.getHours();
                    mn = d.getMinutes();
                    document.getElementById("codepro-hour").innerHTML = (hr < 10 ? "0" : "") + hr + ":" + (mn < 10 ? "0" : "") + mn;
                }
                n = new Date();
                if (n.getTimezoneOffset() == 0) t = n.getTime() + (7 * 60 * 60 * 1000);
                else t = n.getTime();
                n.setTime(t);
                var dn = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
                d = n.getDay();
                m = n.getMonth() + 1;
                y = n.getFullYear();
                var date = dn[d] + " " + (n.getDate() < 10 ? "0" : "") + n.getDate() + "/" + (m < 10 ? "0" : "") + m + "/" + y;
                document.getElementById("codepro-date").innerHTML = date;
                document.getElementById("timediffvn").innerHTML = Math.abs(n.getTimezoneOffset()) / 60;
                document.getElementById("cccccyear").innerHTML = n.getFullYear();
            </script>

</body>

</html>