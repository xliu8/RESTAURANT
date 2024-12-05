<?php
session_start();
require('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role']; // Assuming role comes from the form input

  // Choose the table based on the role
  $table = $role === 'admin' ? 'admin' : 'users';

  $query = "SELECT * FROM {$table} WHERE username = :username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);
  $statement->execute();
  $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Verify password and check if user exists
    if ($user && $user['password']==$password) {
      // Set session variables
      $_SESSION['user_logged_in'] = true;
      $_SESSION['role'] = $role;
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_id'] = $user['user_id'];
      
      // Redirect to the correct dashboard based on role
      $redirectPage = $role === 'admin' ? '/web2/Restaurant/admin/dashboard_admin.php' : '/web2/Restaurant/comments/userComments.php';
      header("Location: {$redirectPage}");
      exit;
    } else {
        // Login failed: username doesn't exist or password doesn't match
        // Handle the error accordingly
        $_SESSION['login_error'] = 'Incorrect username or password.';
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Captain Jack Yummy</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Anton&family=Dancing+Script&display=swap"
      rel="stylesheet"
    />
  </head>

  <body>
    <!--background-->
    <div class="background-c">

      <!--logo-->
      <div class="d-flex justify-content-center">
        <img
          src="/Web2/Restaurant/img/1ea580b3007a01c42c91248819ecd4e0.png"
          alt=""
          style="width: 10%"
        />
      </div>
    
    <!--nav bar-->
        <div class="nav-bar d-flex justify-content-center">
            <nav class="nav nav-pills nav-fill ">
                <a class="nav-link text-white" href="/Web2/Restaurant/index.php">Home</a>
                <a
                class="nav-link text-white"
                href="/Web2/Restaurant/special.php"
                >Special</a
                >
                <a class="nav-link text-white" href="/web2/Restaurant/menu/menu.php">Menu</a>
                <a class="nav-link active text-white" aria-current="page" href="#">Login</a>
            </nav>
        </div>
    </div>

<div class=" background-c pb-5">
    <div class="container text-white">
        <h2>Login</h2>
        <!-- Display any login errors -->
        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['login_error']; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Login as:</label>
                <select name="role" id="role" class="form-control">
                    <option name="users" value="users">Users</option>
                    <option name="admin" value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="registration.php" class="btn btn-secondary">Register</a> <!-- Update href with your actual registration page link -->
            </div>
        </form>
    </div>
    </div>

    <!-- Footer -->
    <div class="footer mw-100 text-light text-center pt-5 pb-5">
      <strong class="follow-us">FOLLOW US</strong>
      <div class="foot-logo">
        <!-- Social icons -->
        <a href="#" class="fab fa-facebook-f mx-2"
          ></a>
        <a href="#" class="fab fa-instagram mx-2"
          ></a>
        <a href="#" class="fab fa-twitter mx-2"
          ></a>
        <a href="#" class="fas fa-envelope mx-2"
          ></a>
      </div>

      <div class="address">
        <strong>ADDRESS</strong><br />
        233-123 Yummy Road<br />
        Brain Strom, AA<br />
        Y10, 3R3<br />

        <i class="fas fa-phone"></i> 555-555-5555
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>