<?php
session_start();

include ('../db.connect.php');
include ('../db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}
// Post deletion handler
if (isset($_POST['delete'])) {
    $post_id = (int)$_POST['post'];
    $deleted = delete('post', $post_id);
    
    $alert = $deleted > 0 ? "Post deleted successfully!" : "Failed to delete comment.";
    $alert_type = $deleted > 0 ? 'success' : 'error';
}

// Gett posts function
$posts = posts();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/logo.png">
    <link rel="stylesheet" href="posts.css">
    <title>TechTala</title>
</head>
<body>
    <?php include("header.php") ?>

    <div class="container">
        <h1>Post Manager</h1>

        <?php if (isset($alert)): ?>
            <div class="alert <?= $alert_type === 'success' ? 'alert-success' : 'alert-error' ?>">
                <?= htmlspecialchars($alert) ?>
            </div>
        <?php endif; ?>

        <div class="s-main">
            <img class="s-image" src="image/search.png" alt="">
            <input class="s-box" type="text" placeholder="Search">
        </div>
        
        <div class="table">
            <table class="post-mgt">
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="5" class="no-post">
                            No Articles found in the database.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td class="post-title">
                                <?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['username'] ?? 'Unknown') ?></td>
                                <td><?= date('F j, Y', strtotime($post['created_at'])) ?></td>                            
                            </td>
                            
                            <td>
                                <div class="action-buttons">
                                    <!-- Viewing page see the authors blogs -->
                                    <a href="view.php?id=<?php echo $post['post_id']; ?>" class="edit">
                                        <img src="image/file.png" alt="Edit">
                                    </a>

                                    <!-- Deleting a post -->
                                    <form method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        <input type="hidden" name="post" value="<?= $post['post_id'] ?>">
                                        <button type="submit" name="delete" class="delete">
                                            <img src="image/delete.png" alt="Delete">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif;?>
            </table>
        </div>
        <?php if (!empty($post)): ?>
            <p class="total-users">
                Total Articles: <?php echo count($posts); ?>
            </p>
        <?php endif; ?>
    </div>

    <script src="delete.js"></script>
</body>
</html>