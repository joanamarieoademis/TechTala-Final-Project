<?php
session_start();

include '../db.connect.php';
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

$table = 'post';
$user_id = $_SESSION['user_id'];

$conditions = ['users_id' => $user_id, 'deleted' => 0];
$own_articles = selectAll($table, $conditions);

if (!empty($own_articles)) {
    usort($own_articles, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
}

// Article deletion handler
if (isset($_GET['deletePost'])) {
    $delete_id = $_GET['deletePost'];
    $posts = selectOne('post', ['id' => $delete_id]);

    if ($posts && $posts['users_id'] == $user_id) {
        // Soft delete
        update('post', $delete_id, ['deleted' => 1]);
        header("Location: creations.php"); 
        exit();
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="creations.css">
</head>
<body>
    
    <?php include ("header.php")?>
        
    <div class="content">
        <h1>Edit Your Article</h1>
        <div class="main">
            <?php if (!empty($own_articles)): ?>
                <?php foreach ($own_articles as $post): ?>
                    <div class="box">
                        <?php if (!empty($post['image'])): ?>
                            <div class="image">
                                <img src="images/<?php echo htmlspecialchars($post['image']); ?>" 
                                    alt="<?php echo htmlspecialchars($post['title']); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="header-content">
                            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                            <p><?php 
                                $content = htmlspecialchars(remove($post['content']));
                                echo strlen($content) > 400 ? substr($content, 0, 400) . '...' : $content;
                            ?></p>                        
                            <div class="button">
                                <a class="edit" href="editpost.php?id=<?php echo $post['id']; ?>">Edit</a>
                                <a class="delete" href="?deletePost=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </div>

                            <div class="date">
                                <?php if (isset($post['created_at'])): ?>
                                    <span><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                    <p>You haven't created any articles yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>