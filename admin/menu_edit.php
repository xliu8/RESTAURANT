<?php

session_start(); 

require('../connect.php');

if (isset($_GET['food_id'])) {
    // 获取菜单项详情
    $id = filter_input(INPUT_GET, 'food_id', FILTER_SANITIZE_NUMBER_INT);
    $query5 = "SELECT * FROM menu WHERE food_id= :id";
    $statement5 = $db->prepare($query5);
    $statement5->bindValue(':id', $id, PDO::PARAM_INT);
    $statement5->execute();
    $menu = $statement5->fetch();

$currentCategory = $menu['category']; // This should be the category of the current food item, fetched from the database or form submission
}

$categories = [
    "Appetizers", "Entrees", "Sandwiches", 
    "Soup & Salad Combos", "Fajitas", "Tacos", 
    "Enchiladas", "Quiche", "Green Salads"
];


if (isset($_POST['update'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $category = filter_input(INPUT_POST, 'category',  
    FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $fileUrl = null;
   
    $uploaded = false;
    $uploadPath = 'uploads'; // Ensure this directory exists and is writable
    // Check if a file has been uploaded
    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES['uploaded_file']['name']);
        $newname = dirname(__FILE__) . DIRECTORY_SEPARATOR . $uploadPath . DIRECTORY_SEPARATOR . $filename;

        if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $newname)) {
            $uploaded = true;
            $fileUrl = $uploadPath . '/' . $filename; // URL to be saved in database
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Prepare query to update menu item
    $query = "UPDATE menu SET food_name = :name, food_description = :description, food_price = :price, category = :category" .
    ($uploaded ? ", img_url = :url" : "") . " WHERE food_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':category', $category);
    if ($uploaded) {
    $statement->bindValue(':url', $fileUrl); // Only bind if a new file was uploaded
    }
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    if (!$statement->execute()) {
    print_r($statement->errorInfo());
    exit;
}
    if ($statement->execute()) {
        header("Location: menu_management.php");
        exit;
    } else {
    echo "Error updating record.";
    }

} elseif (isset($_POST['delete'])) {
    $foodid = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query4 = "DELETE FROM menu WHERE food_id = :id";
    $statement4 = $db->prepare($query4);
    $statement4->bindValue(':id', $foodid, PDO::PARAM_INT);
    
    $statement4->execute();
    
    header("Location: menu_management.php");
    exit;
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
                <a class="nav-link text-white" href="/web2/Restaurant/menu/menu.php">Menu</a>
                <a class="nav-link active text-white" aria-current="page"href="<?php echo $loginLink; ?>"><?php echo $navBtn; ?></a>
            </nav>
        </div>
    </div>

    <!--Detail of item-->
    <div class="background-c d-flex pt-4">
        <div class="col-md-6 pt-5">
                <form method="post" action="menu_edit.php" enctype="multipart/form-data">
                    <div class="content_side pl-3 ">
                        <input type="hidden" name="id" value="<?= $menu['food_id'] ?>">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="name" name="name" value="<?= $menu['food_name']?>" required>
                        </div>

                        <div class="mb-3">
                            <textarea class="form-control" id="description" name="description" rows="10" required><?= $menu['food_description']?></textarea>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="price" name="price" value="<?= $menu['food_price']?>" required>
                        </div>

                        <div class="mb-3">
                            <select class="form-select" name="category" id="category" aria-label="Default select example">
                                <?php foreach ($categories as $category):?>
                                <!-- Check if the current category matches the category from the list and set it as selected-->
                                <?php $selected = ($currentCategory == $category) ? 'selected' : '';?>
                                <option value="<?= htmlspecialchars($category); ?>" <?= $selected ?>><?= htmlspecialchars($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="text-white mb-3">
                            <label for="uploaded_file">Upload Image:</label>
                            <input type="file" name="uploaded_file" id="uploaded_file" />
                        </div>

                        <input class="login-sub" type="submit" value="update" name="update">
                        <input class="login-sub" type="submit" value="delete" name="delete">
                    </div>
                </form>
            </div>
            <div class="img_side mt-5 p-2 flex-lg-shrink-0">
        <img class="img-fluid " src="/web2/Restaurant/admin/<?= $menu['img_url'] ?>" alt="food">
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