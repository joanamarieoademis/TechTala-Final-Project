<?php
session_start();

include ('../db.connect.php');
include ('../db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}

// Getting the id of the post
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id <= 0) {
    header('Location: homepage.php');
    exit();
}

// Getting the specific blog or post
$post = selectOne('post', ['id' => $post_id]);

if (!$post) {
    header('Location: posts.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTala</title>
    <link rel="icon" href="../image/logo.png">
    <link rel="stylesheet" href="views.css">
</head>
<body>

    <?php include("header.php"); ?>
    
    <div class="main">
        <div class="contents">
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <?php if (!empty($post['image'])): ?>
                <img src="../Author/images/<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
            <?php else: ?>
                <img src="image/type.avif" alt="">
            <?php endif; ?>
            
            <?php echo $post['content']; ?>
            
            <h4><?php echo htmlspecialchars($post['username']); ?></h4>
            <span><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
        </div>
    </div>
    
</body>
</html>