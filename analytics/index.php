<?php
session_start();
if (!isset($_SESSION["userwtf"]))
    header("Location: ../login/");
else {
    require '../assets/variables/sql.php';
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
    <title>Analytics - fuchsia Media Group
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
    <link rel="stylesheet" href="/assets/css/scroll-bar.css" />
</head>

<body class="bg-theme bg-theme1">

    <!-- start loader -->
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
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="zmdi zmdi-chart"></i> Total Views
                            </div>
                            <div class="card-body">
                                <div class="chart-container-1" style="position: relative; height: 300px;">
                                    <canvas id="viewsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="zmdi zmdi-money-box"></i> Total Revenue
                            </div>
                            <div class="card-body">
                                <div class="chart-container-1" style="position: relative; height: 300px;">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <script src="../assets/plugins/Chart.js/Chart.min.js"></script>
    <script type="text/javascript">
        n = new Date();
        document.getElementById("cccccyear").innerHTML = n.getFullYear();
        function unixtodate(unix_timestamp) {
            var now = new Date(unix_timestamp * 1000);
            const year = now.getFullYear();
            const month = now.getMonth() + 1; // Add 1 because getMonth() is zero-indexed
            const day = now.getDate();
            const formattedDate = `${year}/${String(month).padStart(2, '0')}`;
            return (formattedDate); // Example output: 2025-07-08
        }
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/api/analytics.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    if (data.length === 0) {
                        alert('No analytics data available.</td></tr>');
                        return;
                    }

                    let totalViews = [];
                    let totalRevenue = [];
                    let months1 = [];
                    let months2 = [];
                    console.log(unixtodate(1751964077));
                    data.forEach(item => {
                        let temp1 = item.raw_view;
                        totalViews.push(view1);
                        months1.push(unixtodate(item.date));
                    });
                    var view1 = {
                        type: 'line',
                        label: "",
                        data: [1, 2, 3, 4],
                        backgroundColor: 'rgba(255, 255, 255, 0.2)',
                        borderColor: 'transparent',
                        borderWidth: 1
                    };

                    // Render Views Chart
                    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
                    new Chart(viewsCtx, {
                        data: {
                            labels: months,
                            datasets: totalViews
                        },
                        options: {
                            maintainAspectRatio: false,
                            legend: {
                                display: false,
                                labels: {
                                    fontColor: '#ddd',
                                    boxWidth: 40
                                }
                            },
                            tooltips: {
                                displayColors: false
                            },
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontColor: '#ddd'
                                    },
                                    gridLines: {
                                        display: true,
                                        color: "rgba(221, 221, 221, 0.08)"
                                    },
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontColor: '#ddd'
                                    },
                                    gridLines: {
                                        display: true,
                                        color: "rgba(221, 221, 221, 0.08)"
                                    },
                                }]
                            }

                        }
                    });

                    // Render Revenue Chart
                    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                    new Chart(revenueCtx, {
                        type: 'line',
                        data: {
                            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            datasets: [{
                                label: 'Total Revenue',
                                data: totalRevenue,
                                backgroundColor: 'rgba(255, 255, 255, 0.5)',
                                borderColor: 'transparent',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            legend: {
                                display: false,
                                labels: {
                                    fontColor: '#ddd',
                                    boxWidth: 40
                                }
                            },
                            tooltips: {
                                displayColors: false
                            },
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontColor: '#ddd'
                                    },
                                    gridLines: {
                                        display: true,
                                        color: "rgba(221, 221, 221, 0.08)"
                                    },
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontColor: '#ddd'
                                    },
                                    gridLines: {
                                        display: true,
                                        color: "rgba(221, 221, 221, 0.08)"
                                    },
                                }]
                            }

                        }
                    });

                })
                .catch(error => {
                    console.error('Error fetching analytics data:', error);
                    //document.getElementById('analyticsTableBody').innerHTML = `<tr><td colspan="5" class="text-center">Failed to load analytics data.</td></tr>`;
                    alert('[Server] Failed to load analytics data.')
                });
        });
    </script>

</body>

</html>