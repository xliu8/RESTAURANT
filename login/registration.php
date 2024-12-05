<?php
    require('../connect.php');

    session_start(); 

    if ($_POST) {
        if (!empty($_POST['lastname']) && !empty($_POST['firstname']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['phone'])) {
            $fname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $uname = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pass= filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            
            $query = "INSERT INTO users (first_name, last_name, username, password, email, phone) VALUES (:firstname, :lastname, :username, :password, :email, :phone)";
            $statement = $db->prepare($query);
            
            $statement->bindValue(':firstname',$fname);
            $statement->bindvalue(':lastname',$lname);
            $statement->bindValue(':username',$uname);
            $statement->bindvalue(':password',$pass);
            $statement->bindValue(':email',$email);
            $statement->bindValue(':phone',$phone);

            
            if ($statement->execute()) {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['user_logged_in'] = true;
                $_SESSION['role'] = "users";
                header('Location: /web2/restaurant/comments/userComments.php?username='.$_SESSION['username']); 
                exit;
            } else {
                header('Location: registrationFail.php'); 
            }
        } else {
            header('Location: registrationFail.php'); 
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Captain Jack Yummy</title>
    <script src="validation.js"></script>
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
                <a class="nav-link  text-white" href="/web2/Restaurant/menu/menu.php">Menu</a>
                <a class="nav-link active text-white" aria-current="page" href="/web2/Restaurant/login/login.php">Login</a>
            </nav>
        </div>
    </div>

    <div class="background-c pt-5 pb-5 pl-5 pr-5">
        <form action="registration.php" method="post" class="row g-3 needs-validation" novalidate>
        <div class="col-md-3">
            <input type="text" class="form-control" id="firstName" name="firstname" placeholder="First Name" required>
            <div class="valid-feedback">
            Looks good!
            </div>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="lastName" name="lastname" placeholder="Last Name" required>
            <div class="valid-feedback">
            Looks good!
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group has-validation">
            <span class="input-group-text" id="inputGroupPrepend">@</span>
            <input type="text" class="form-control" id="userName" name="username" aria-describedby="inputGroupPrepend" placeholder="UserName" required>
            <div class="invalid-feedback">
                Please type a username.
            </div>
            </div>
        </div>
        <div class="col-md-3 pt-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="email address" required>
            <div class="invalid-feedback">
            Please provide a valid email.
            </div>
        </div>

        <div class="col-md-3 pt-3">
            <input type="phone" class="form-control" id="phone" name="phone" placeholder="phone number" required>
            <div class="invalid-feedback">
            Please provide a valid phone number.
            </div>
        </div>

        <div class="col-md-3 pt-3">
            <input type="text" class="form-control" id="password" name="password" placeholder="Password" required>
            <div class="invalid-feedback">
            Password cannot be empty.
            </div>
        </div>
        <div class="col-12 pt-3 text-white ">
            <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
            <label class="form-check-label" for="invalidCheck">
                Agree to terms and conditions
            </label>
            <div class="invalid-feedback pt-3">
                You must agree before submitting.
            </div>
            </div>
        </div>
        <div class="col-12 pt-3">
            <button class="login-sub" type="submit">Register</button>
        </div>
        </form>
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