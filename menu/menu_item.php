<?php
session_start(); 

require('../connect.php');

$menuid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if($menuid) {
    $query = "SELECT * FROM menu WHERE food_id= :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $menuid, PDO::PARAM_INT);
    $statement->execute();
    $id = $statement->fetch();

    $query2= "SELECT * FROM comment WHERE food_id= :id";
    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':id', $menuid, PDO::PARAM_INT);
    $statement2->execute();
    $comment=$statement2->fetch();


} else {
    exit("Invalid ID provided.");
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
      $link = "/Web2/Restaurant/comments/create.php?id=". $menuid; 
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
                <a class="nav-link active text-white" aria-current="page"href="/web2/Restaurant/menu/menu.php">Menu</a>
                <a class="nav-link text-white" href="<?php echo $loginLink; ?>"><?php echo $navBtn; ?></a>
            </nav>
        </div>
    </div>

    <!--Detail of item-->
    <div class="background-c d-flex pt-4">
      <div class="content_side pl-3">
        <h1 class="detail_title"><?= $id['food_name']?></h1>
        <p class="detail_content" ><?= $id['food_description']?></p>
        <p class="detail_price" >$ <?= $id['food_price']?></p> 

        <?php if(!$comment == null): ?>
        <h5 class="detail_rate">Rate: <?php
            $rate = $comment['rate']; 
            $totalStars = 5; 

          for ($i = 0; $i < $totalStars; $i++) {
            if ($i < $rate) {
              // 这代表已评的星星
                echo '<i class="fas fa-star"></i>';
            } else {
              // 这代表未评的星星
                echo '<i class="far fa-star"></i>';
            }       
          }
          ?></h5>
        <p class="detail_comment">Comment: <?= $comment['content']?></p>
        <?php else: ?>
        <p class="text-white pt-5">There is no comment yet!</p>
        <?php endif; ?>


        <div class="d-flex">
        <a href="<?php echo $link; ?>">
        <button class="details_btn" type="button">Leave your thought to this dish</button>
        </a>
        <a href="/web2/restaurant/comments/comments.php?id=<?=$id['food_id']?>">
        <button class="details_btn" type="button">See More</button></a>
        </div>  
      </div>
      <div class="img_side p-2 flex-lg-shrink-0">
        <img class="img-fluid " src="/web2/Restaurant/admin/<?= $id['img_url'] ?>" alt="food">
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