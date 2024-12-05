<?php

session_start();

require('../connect.php');

if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    
    $query = "DELETE FROM users WHERE user_id = :userid";
    
    $statement = $db->prepare($query);
    $statement->bindParam(':userid', $userid);
    $statement->execute();

    header("Location: Users_admin.php"); 
    exit;
} else {
    echo "user id not provided!";
}

?>