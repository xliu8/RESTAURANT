<?php

session_start();

require('../connect.php');

if (isset($_GET['commentid'])) {
    $commentid = $_GET['commentid'];

    // 删除语句
    $query = "DELETE FROM comment WHERE comment_id = :commentid";
    
    $statement = $db->prepare($query);
    $statement->bindParam(':commentid', $commentid);
    $statement->execute();

    header("Location: Comments_admin.php"); 
    exit;
} else {
    echo "comment id not provided!";
}

?>