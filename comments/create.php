<?php
session_start(); 
require('../connect.php');

// Check if the user is logged in
    $username = $_SESSION['username']; 
    $navBtn = $username;
    
    // Fetch user_id based on the username
    $queryUser = "SELECT user_id FROM users WHERE username = :username"; 
    $stmtUser = $db->prepare($queryUser);
    $stmtUser->bindValue(':username', $username, PDO::PARAM_STR);
    $stmtUser->execute();
    $user = $stmtUser->fetch();

// Fetch menu details
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    $queryMenu = "SELECT food_id FROM menu WHERE food_id = :id";
    $stmtMenu = $db->prepare($queryMenu);
    $stmtMenu->bindValue(':id', $id, PDO::PARAM_INT);
    $stmtMenu->execute();
    $menu = $stmtMenu->fetch();

    $query5 = "SELECT * FROM menu WHERE food_id= :id";
    $statement5 = $db->prepare($query5);
    $statement5->bindValue(':id', $id, PDO::PARAM_INT);
    $statement5->execute();
    $menuDetail = $statement5->fetch();

// Handle comment submission
if ($_POST) {
    if (!empty($_POST['rate']) && !empty($_POST['content'])) {
        $rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $food = filter_input(INPUT_POST, 'food_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = "INSERT INTO comment (food_id, user_id, rate, content) VALUES (:foodid, :userid, :rate, :content)";
        $statement = $db->prepare($query);
        $statement->bindValue(':foodid', $food, PDO::PARAM_INT);
        $statement->bindValue(':userid', $user, PDO::PARAM_INT);
        $statement->bindValue(':rate', $rate, PDO::PARAM_INT);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        
        if ($statement->execute()) {
            header('Location: userComments.php'); 
            exit;
        } else {
            echo "Fail to Post";
        }
    } else {
        echo "Rate or content cannot be empty.";
    }
}

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
      //$link = "/Web2/Restaurant/comments/create.php?id=". $menuid; 
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
    <script src="/web2/Restaurant/login/validation.js"></script>
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
                <a class="nav-link active text-white" aria-current="page" href="<?php echo $loginLink; ?>"><?php echo $navBtn; ?></a>
            </nav>
        </div>
    </div>

    <div>
        <h1 class="detail_title background-c d-flex pt-4 pl-4"><?= $menuDetail['food_name']?></h1>
    </div>

    <div class="background-c d-flex flex-row">
        <!--Detail of item-->

        <div class="d-flex pt-4 col-md-6">   
            <div class="content_side pl-3">
                <p class="detail_content" ><?= $menuDetail['food_description']?></p>
                <p class="detail_price" >$ <?= $menuDetail['food_price']?></p> 
                <img class="img-fluid rounded float-start w-50" src="/Web2/Restaurant/admin/<?= $menuDetail['img_url'] ?>" alt="food">
            </div>
        </div>

        <div class="col-md-6 pt-5">
            <form method="post" action="create.php">
                <div class="content_side pl-3 ">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    <input type="hidden" name="food_id" value="<?= $menu['food_id'] ?>">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="rate" name="rate" placeholder="Feedback your rate ?/5" required>
                    </div>

                    <div class="mb-3">
                        <textarea class="form-control" id="content" name="content" placeholder="Tell me what you think" rows="10" required></textarea>
                    </div>

                    <input class="login-sub" type="submit" value="create" name="create">
                </div>
            </form>
        </div>
    </div>


    <!-- Footer -->
    <div class="footer mw-100 text-light text-center pt-5">
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