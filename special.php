<?php
session_start(); 


// Check if the user is logged in and assign navigation based on the role
if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
  $username = $_SESSION['username'];
  $role = $_SESSION['role']; // Assuming you've stored the user's role in session

  // Set the display name and link based on the user's role
  $navBtn = $username; // Display the username on the button
  
  if($role === 'admin') {
      // Redirect to the admin dashboard if the user is an admin
      $link = "/Web2/Restaurant/admin/Comments_admin.php";
      $loginLink = "/Web2/Restaurant/admin/Dashboard_admin.php";
  } else {
      // Redirect to the user comments page if the user is not an admin
      // = "/Web2/Restaurant/comments/create.php?id=". $menuid; 
      $loginLink = "/Web2/Restaurant/comments/userComments.php";
  }
} else {
  // If the user is not logged in, show the login option
  $navBtn = "Login";
  $loginLink = "/Web2/Restaurant/login/login.php";
  $link = "/Web2/Restaurant/login/login.php"; 
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Captain Jack Yummy</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
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

    <div class="bg-image">
      <div class="overlay"></div>
    
      <!--logo-->
        <div class="d-flex justify-content-center logo">
          <img
            src="../Restaurant/img/1ea580b3007a01c42c91248819ecd4e0.png"
            alt=""
            style="width: 10%"
          />
        </div>
      </div>
    <!--nav bar-->
    <div
      class="z-3 position-absolute"
      style="top: 20%; left: 50%; transform: translateX(-50%)"
    >
      <nav class="nav nav-pills nav-fill">
        <a class="nav-link text-white" href="./index.php">Home</a>
        <a
          class="nav-link active text-white"
          aria-current="page"
          href="../Restaurant/special.php"
          >Special</a
        >
        <a class="nav-link text-white" href="../Restaurant/menu/menu.php"
          >Menu</a
        >
        <a class="nav-link text-white" href="<?php echo $loginLink; ?>"><?php echo $navBtn; ?></a>
      </nav>
    </div>

    <!--sepcial title-->
    <div
      class="z-2 position-absolute"
      style="top: 30%; left: 50%; transform: translateX(-50%)"
    >
      <p class="slogan2 text-center">Our Special</p>
    </div>

    <!--sepcial-->
    <ul
      class="menu z-3 position-absolute"
      style="top: 50%; left: 50%; transform: translateX(-50%)"
    >
      <li class="menu-item">
        <span class="menu-item-name"
          >Farfalle Pasta with Braised Pork in Tomato Cream</span
        >
        <span class="price">$12.95</span>
      </li>
      <li class="menu-item">
        <span class="menu-item-name"
          >Salmon & Crispy Tofu in Yellow Curry Sauce</span
        >
        <span class="price">$15.95</span>
      </li>
      <li class="menu-item">
        <span class="menu-item-name">Rare Roast Beef & Swiss</span>
        <div>
          <span class="price">$7.95 half</span>
          <span class="price">$9.25 full</span>
        </div>
      </li>
      <li class="menu-item">
        <span class="menu-item-name">Chipotle BBQ Pork</span>
        <span class="price">$9.50</span>
      </li>
    </ul>

    <div
      class="z-3 position-absolute"
      style="top: 85%; left: 50%; transform: translateX(-50%)"
    >
      <button type="button" class="button text-white" onclick="location.href='/web2/Restaurant/menu/menu.php'">Learn More</button>
    </div>

    <!-- Footer -->
    <div class="footer mw-100 text-light text-center pt-5">
      <strong class="follow-us">FOLLOW US</strong>
      <div class="foot-logo">
        <!-- Social icons -->
        <a href="#" class="fab fa-facebook-f mx-2"></a>
        <a href="#" class="fab fa-instagram mx-2"></a>
        <a href="#" class="fab fa-twitter mx-2"></a>
        <a href="#" class="fas fa-envelope mx-2"></a>
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
