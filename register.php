<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/main.css" />
  <link href="assets/img/LOGOrpl.png" rel="icon" />
  <title>Register</title>
</head>

<body>

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">

        <h1 class="sitename">SMK</h1>
        <span>.PK</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php">Home<br /></a></li>
          <li><a href="login.php" class="active">Login</a></li>
          

          <li><a href="register.php">Register></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <a class="btn-getstarted" href="#"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
      <?php else: ?>
        <a class="btn-getstarted" href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </header>

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-5 p-3 bg-white shadow box-area">
      <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
        style="background: #20664f">
        <div class="featured-image mb-3">
          <img src="images/1.png" class="img-fluid" style="width: 250px" />
        </div>
        <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600">Be Verified
        </p>
        <small class="text-white text-wrap text-center"
          style="width: 17rem; font-family: 'Courier New', Courier, monospace">Join experienced Designers on this
          platform.</small>
      </div>

      <div class="col-md-6 right-box">
        <form action="" method="post">
          <div class="row align-items-center">
            <div class="header-text mb-4">
              <h2>Hello, Again</h2>
              <p>We are happy to have you back.</p>
            </div>
            <div class="input-group mb-3">
              <input type="text" name="namauser" class="form-control form-control-lg bg-light fs-6"
                placeholder="Nama Lengkap" />
            </div>
            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control form-control-lg bg-light fs-6"
                placeholder="Nama" />
            </div>
            <div class="input-group mb-3">
              <input type="text" name="level" class="form-control form-control-lg bg-light fs-6" placeholder="Kelas" />
            </div>
            <div class="input-group mb-3">
              <input type="text" name="email" class="form-control form-control-lg bg-light fs-6"
                placeholder="Email address" />
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control form-control-lg bg-light fs-6"
                placeholder="Password" />
            </div>
            <div class="input-group mb-1">
              <input type="password" name="repeat_password" class="form-control form-control-lg bg-light fs-6"
                placeholder="Repeat Password" />
            </div>

            <div class="input-group mb-5 d-flex justify-content-between">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="formCheck" />
                <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
              </div>
            </div>
            <div class="input-group mb-3">
              <button type="submit" name="submit" class="btn btn-lg btn-primary w-100 fs-6"
                style="background: #20664f; color: #ffffff">Sign Up</button>
            </div>
          </div>
        </form>
        <div class="row">
          <small>Already have an account? <a href="login.php">Sign In</a></small>
        </div>
        <?php
        if (isset($_POST["submit"])) {
          $namauser = $_POST["namauser"];
          $userName = $_POST["username"];
          $level = $_POST["level"];
          $emailuser = $_POST["email"];
          $password = $_POST["password"];
          $passwordRepeat = $_POST["repeat_password"];

          $errors = array();

          // Check for empty fields
          if (empty($namauser) || empty($userName) || empty($level) || empty($emailuser) || empty($password) || empty($passwordRepeat)) {
            array_push($errors, "All fields are required");
          }

          // Validate email format
          if (!filter_var($emailuser, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
          }

          // Validate level (assuming it's a specific set of values)
          if (!in_array($level, array('admin', 'user'))) { // Example: Check if level is 'admin' or 'user'
            array_push($errors, "Invalid level");
          }

          // Check password length
          if (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters long");
          }

          // Check if passwords match
          if ($password !== $passwordRepeat) {
            array_push($errors, "Password does not match");
          }

          // Check if email already exists
          require_once "koneksi.php";
          $sql = "SELECT * FROM user WHERE emailuser = ?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("SQL error");
          }
          mysqli_stmt_bind_param($stmt, "s", $emailuser);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Email already exists!");
          }
          mysqli_stmt_close($stmt);

          // Handle errors
          if (count($errors) > 0) {
            foreach ($errors as $error) {
              echo "<div class='alert alert-danger'>$error</div>";
            }
          } else {
            // Hash the password for security
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into database
            $sql = "INSERT INTO user (namauser, username, level, emailuser, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              die("SQL error");
            }
            mysqli_stmt_bind_param($stmt, "sssss", $namauser, $userName, $level, $emailuser, $passwordHash);
            mysqli_stmt_execute($stmt);

            echo "<div class='alert alert-success'>You are registered successfully.</div>";
            header("Location: index.php");
            exit;
          }
        }
        ?>

      </div>
    </div>
  </div>

  <footer id="footer" class="footer dark-background">
    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">PPLG</span>
            </a>
            <div class="footer-contact pt-3">
              <p>SMK Negeri 10 Semarang</p>
              <p>Jl. Kokrosono No.75, Panggung Kidul, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50178</p>
              <p class="mt-3"><strong>Phone:</strong> <span>+62 857 9607 2571</span></p>
              <p><strong>Email:</strong> <span>joel.asmoro@gmail.com</span></p>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Terms of service</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><a href="#">Web Design</a></li>
              <li><a href="#">Web Development</a></li>
              <li><a href="#">Product Management</a></li>
              <li><a href="#">Marketing</a></li>
              <li><a href="#">Graphic Design</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Hic solutasetp</h4>
            <ul>
              <li><a href="#">Molestiae accusamus iure</a></li>
              <li><a href="#">Excepturi dignissimos</a></li>
              <li><a href="#">Suscipit distinctio</a></li>
              <li><a href="#">Dilecta</a></li>
              <li><a href="#">Sit quas consectetur</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Nobis illum</h4>
            <ul>
              <li><a href="#">Ipsam</a></li>
              <li><a href="#">Laudantium dolorum</a></li>
              <li><a href="#">Dinera</a></li>
              <li><a href="#">Trodelas</a></li>
              <li><a href="#">Flexo</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div
        class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">
        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>MyWebsite</strong>. All Rights Reserved
          </div>
          <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/herobiz-bootstrap-business-template/ -->
            Designed by <a href="">@joelnissi.aa</a>
          </div>
        </div>

        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
  </footer>