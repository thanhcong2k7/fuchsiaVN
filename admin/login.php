<?php
session_start();
require 'assets/variables/sql.php';
if (isset($_COOKIE["saveses"])) {
  echo '<script>console.log("saved session exists. now check");</script>';
  $ip = $_SERVER["REMOTE_ADDR"];
  $ress = query("select * from sessions where ip='" . $ip . "';");
  while ($row = $ress->fetch_assoc()) {
    $uuuid = $row["userID"];
    $sus = query("select pwd from user where userID=" . $uuuid . ";");
    $p = "";
    $iv = $row["iv"];
    while ($roww = $sus->fetch_assoc()) {
      $p = $roww["pwd"];
    }
    try {
      $dec = openssl_decrypt($_COOKIE["saveses"], "AES-128-CTR", $p, 0, $iv);
      if ($dec == $row["secret"]) {
        $_SESSION["userwtf"] = $row["userID"];
        //setcookie("saveses","", time()-3600, "/");
        header("Location: index.php");
      }
    } catch (Exception $e) {
      echo 'Caught exception: ' . $e->getMessage();
    }
    echo '<script>console.log("failed to initialize saved session");</script>';
    setcookie("saveses", "", -1, "/");
    query("delete from sessions where ip='" . $ip . "';");
    //header("Location: ../");
  }
}
if (isset($_SESSION["saipass"]))
  echo "<script>alert('" . $_SESSION["saipass"] . "');</script>";
unset($_SESSION["saipass"]);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form action="login_hehe.php" method="POST">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="userslot" type="username" placeholder="name@example.com" />
                                                <label for="userslot">Email address</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="pwdslot" type="password" placeholder="Password" />
                                                <label for="pwdslot">Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="password.html">Forgot Password?</a>
                                                <input type="submit" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.html">Need an account? Sign up!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
