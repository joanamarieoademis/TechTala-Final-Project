<?php
session_start();
include '../db.connect.php';
include '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

$table = 'post';
$posts = selectAll($table);

usort($posts, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

$articles = $posts;

$user_id = $_SESSION['user_id'];
$own_articles = selectAll('post', ['users_id' => $user_id]);

if (!empty($own_articles)) {
    shuffle($own_articles);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="home.css">
</head>
<body>

    <?php include ("header.php")?>

    <div class="main">
        <img class="image" src="image/type.avif" alt="">
        <div class="content">
            <h1>Welcome back</h1>
            <p>Ready to start your new article?</p>
            <a href="create_newblog.php">Create Now</a>
        </div>
    </div>

    <div class="contents">

        <div class="cont1">
            <h1>Own Articles</h1>

            <div class="s-cont">
                <button class="s-button previous">&#10094;</button>

                <div class="cont" id="slider">
                    <?php if (!empty($own_articles)): ?>
                    <?php foreach ($own_articles as $post): ?>
                        <div class="art-own">
                            <div class="art-image">
                                <?php if (!empty($post['image'])): ?>
                                    <img src="images/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                                <?php else: ?>
                                    <img src="image/images.jpg" alt="Default Image">
                                <?php endif; ?>
                            </div>

                            <div class="art-cont">
                                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                                <p>
                                    <?php 
                                        $content = strip_tags($post['content']);
                                        echo htmlspecialchars(substr($content, 0, 100));
                                        if (strlen($content) > 100) echo '...';
                                    ?>
                                </p>
                                <a class="view-button" href="view.php?id=<?php echo $post['id']; ?>">View</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no">You haven't created any articles yet.</p>
                    <?php endif; ?>
                </div>

                <button class="s-button next">&#10095;</button>
            </div>
        </div>

        <div class="cont2">
            <h1>Recent Articles</h1>
            <div class="boxes">
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $post): ?>
                    <div class="recents">
                        <?php if (!empty($post['image'])): ?>
                            <img src="images/<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <?php else: ?>
                            <img src="image/images.jpg" alt="Default Image">
                        <?php endif; ?>
                        
                        <h2><a href="view.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                        
                        <p><?php 
                            $content = strip_tags($post['content']);
                            echo htmlspecialchars(substr($content, 0, 200));
                            if (strlen($content) > 200) echo '...';
                        ?></p>
                        
                        <div class="author">
                                <strong>By: <?php echo htmlspecialchars($post['username']); ?></strong>
                                <?php if (isset($post['created_at'])): ?>
                                    <span><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include ("footer.php")?>

    <script src="homepage.js"></script>

</body>
</html>