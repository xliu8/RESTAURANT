<?php

session_start();

require('../connect.php');

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

$query= "SELECT * FROM Comment";
$statement = $db->prepare($query);
$statement->execute();
$comments=$statement->fetchAll();


// For each comment, fetch the associated food name
foreach ($comments as $comment) {
    $query2 = "SELECT food_name FROM menu WHERE food_id = :foodid";
    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':foodid', $comment['food_id'], PDO::PARAM_INT);
    $statement2->execute();
    $food = $statement2->fetch(PDO::FETCH_ASSOC);
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
                <a class="nav-link  text-white" aria-current="page"href="/web2/Restaurant/menu/menu.php">Menu</a>
                <a class="nav-link active text-white" href="<?php echo $loginLink; ?>"><?php echo $navBtn; ?></a>
            </nav>
        </div>
    </div>

    <div class="background-c pt-4 pb-5">
        <div class="content_side pl-3 ">
                <?php if ($comments): ?>
                    <?php foreach ($comments as $comment): ?>
                        <h5 class="comments_rate">Rate:
                    <?php
                        $rate = $comment['rate']; 
                        $totalStars = 5; 
                        for ($i = 0; $i < $totalStars; $i++) {
                        echo $i < $rate ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                    ?></h5>
                        <div class="comments_date"><?= htmlspecialchars($comment['comment_date']) ?>
                            <a class="edit" href="Comment_delete.php?commentid=<?= $comment['comment_id']?>" onclick="return confirmDelete(<?= $comment['comment_id'] ?>)">Delete</a>
                            <p>The comment is for <a href="/Web2/restaurant/menu/menu_item.php?id=<?= $comment['food_id'] ?>"><?= htmlspecialchars($food['food_name']) ?></a></p>
                        </div>
                        <p class="comments_content h4 pb-2 mb-4 text-warning border-bottom border-warning"><?= htmlspecialchars($comment['content']) ?></p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>There are no comments.</p>
                <?php endif; ?>    
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