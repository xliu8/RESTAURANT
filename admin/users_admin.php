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

//Get the data of all users
$query = "SELECT * FROM users
ORDER BY created_at ASC, username ASC";
$statement = $db->prepare($query);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

// Filter the data for specific user by name
$query2 = "SELECT * FROM users WHERE 1=1";
$name = isset($_GET['username']) ? $_GET['username'] : '';
$query2 .= " AND username = :username";
$statement2 = $db->prepare($query2);
$statement2->bindParam(':username', $name);
$statement2->execute();
$users = $statement2->fetchAll(PDO::FETCH_ASSOC);


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
    <!--user filter-->
    <div class="search">
        <div>
            <form method="get" action="users_admin.php">
                <div >
                    <input class="form-input" id="username" name="username" placeholder="User Name"/>
                </div>         
                <div >
                    <input class="submit-btn" type="submit" value="Filter">
                <div>
            </form>
        </div>
    </div>

    <!--user display-->
    <div class="pt-5 pb-5 background-c text-white">
        <div class="container mt-3">
            <h1>Customers</h1>
            <table class="table text-white">
                <thead>
                    <tr>
                        <th scope="col" class="col-3">UserName</th>
                        <th scope="col" class="col-3">First Name</th>
                        <th scope="col" class="col-3">Last Name</th>
                        <th scope="col" class="col-3">Phone Number</th>
                        <th scope="col" class="col-1">Email</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr >
                            <td scope="row"><?= htmlspecialchars($row['username']) ?></td>
                            <td scope="row"><?= htmlspecialchars($row['first_name']) ?></td>
                            <td scope="row"><?= htmlspecialchars($row['last_name']) ?></td>
                            <td scope="row"><?= htmlspecialchars($row['phone']) ?></td>
                            <td scope="row"><?= htmlspecialchars($row['email']) ?></td>
                            
                            <td scope="row">
                                <div>
                                    <a href="users_delete.php?userid=<?= $row['user_id'] ?>" onclick="return confirmDelete(<?= $row['user_id'] ?>)">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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