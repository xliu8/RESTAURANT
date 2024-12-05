<?php
require('../connect.php');

session_start(); 

$query = "SELECT * FROM menu WHERE 1=1";
$name = isset($_GET['name']) ? $_GET['name'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$price  = isset($_GET['price']) ? $_GET['price'] : '';

if (!empty($name)) {
    $query .= " AND food_name LIKE :name";
}
if (!empty($type) && $type != 'All') {
    $query .= " AND category = :type";
}
if(!empty($price) && $price =='ASC'){
  $query .= " ORDER BY food_price ASC";
}
if(!empty($price) && $price =='DESC'){
  $query .= " ORDER BY food_price DESC";
}

$statement = $db->prepare($query);

if (!empty($name)) {
    $name = "%$name%";
    $statement->bindParam(':name', $name);
}
if (!empty($type) && $type != 'All') {
    $statement->bindParam(':type', $type);
}

$statement->execute();
$menuItems = $statement->fetchAll(PDO::FETCH_ASSOC);

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
                <a class="nav-link active text-white" aria-current="page" href="/web2/Restaurant/menu/menu.php">Menu</a>
                <a class="nav-link text-white" href="<?php echo $loginLink; ?>"><?php echo $navBtn; ?></a>
            </nav>
        </div>
    </div>

    <!--Menu Filter-->
    <div class="search">
        <form method="get" action="menu.php">
            <div >
                <input class="form-input" id="name" name="name" placeholder="Item Name"/>
            </div>
            <div >
                <select class="form-select" name="type" id="type">
                    <option value="All">All</option>
                    <option value="Appetizers">Appetizers</option>
                    <option value="Entrees">Entrees</option>
                    <option value="Sandwiches">Sandwiches</option>
                    <option value="Soup & Salad Combos">Soup & Salad Combos</option>
                    <option value="Fajitas">Fajitas</option>
                    <option value="Tacos">Tacos</option>
                    <option value="Enchiladas">Enchiladas</option>
                    <option value="Quiche">Quiche</option>
                    <option value="Green Salads">Green Salads</option>
                </select>
            </div>

            <div>
              <select class="form-select" name="price" id="price">
                <option value="">Default</option>
                <option value="DESC">From Highest to Lowest</option>
                <option value="ASC">From Lowest to Highest</option>
              </select>
            </div>
            
            <div >
                <input class="submit-btn" type="submit" value="Filter">
            <div>
        </div>
        </form>
    </div>

    <!--Menu Display-->
    <div class="all_menu background-c" >
        <div class="menu_post">
            <?php foreach ($menuItems as $menu): ?>
                <div class="menu_item">
                    <h3 class="food_title">
                        <a class="text-white" href="menu_item.php?id=<?= $menu['food_id'] ?>"><?= $menu['food_name'] ?></a>
                    </h3>
                    <p class="menu_content "><?= $menu['food_description']; ?></p >
                    <p class="price">$<?= $menu['food_price']; ?></p >
                </div>
            <?php endforeach; ?>
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
