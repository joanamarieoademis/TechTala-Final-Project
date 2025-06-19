<?php
session_start();
require_once '../db.connect.php';
require_once '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}

// Get featured article (most comment)
$featuredQuery = "
    SELECT p.*, COUNT(c.id) AS comment_count 
    FROM post p 
    LEFT JOIN comments c ON p.id = c.post_id 
    GROUP BY p.id 
    ORDER BY comment_count DESC, p.created_at DESC 
    LIMIT 1
";
$featuredStmt = executeQuery($featuredQuery, []);
$featuredPost = $featuredStmt->get_result()->fetch_assoc();

// Get latest 6 articles
$latestPostsQuery = "SELECT * FROM post ORDER BY created_at DESC LIMIT 6";
$latestStmt = executeQuery($latestPostsQuery, []);
$latestPosts = $latestStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all posts randomly
$allPostsQuery = "SELECT * FROM post ORDER BY RAND()";
$allPostsStmt = executeQuery($allPostsQuery, []);
$allPosts = $allPostsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="homepage.css">
</head>
<body>

    <?php include("header.php"); ?>

    <div class="contents">
        <div class="cont1">
            <div class="main-search">
                <img class="image" src="image/search.png" alt="">
                <input class="search" type="text" placeholder="Search">
            </div>
            <div class="cont">
                <h1>Discover Stories That Inspire</h1>
                <h4>Thoughtful articles on mindfulness, travel adventures, and living a balanced life in today's fast-paced world.</h4>
            </div>
        </div>

        <div class="cont2">
            <!-- Featured article (most comments article) -->
            <?php if ($featuredPost): ?>
                <img src="<?php echo htmlspecialchars($featuredPost['image'] ? '../Author/images/' . $featuredPost['image'] : 'image/type.avif'); ?>" alt="Featured Image">
                <div class="cont2-body">
                    <h2><?php echo htmlspecialchars($featuredPost['title']); ?></h2>
                    <p><?php echo htmlspecialchars(shortText($featuredPost['content'], 400)); ?></p>
                </div>
                <div class="cont2-footer">
                    <div class="author">
                        <strong>By: <?php echo htmlspecialchars($featuredPost['username']); ?></strong><br>
                        <span><?php echo assignDate($featuredPost['created_at']); ?></span>
                    </div>
                </div>
                <a href="postpage.php?id=<?php echo $featuredPost['id']; ?>">Read More</a>
            <?php else: ?>
                <!-- Display this content if no posts exist -->
                <img src="image/type.avif" alt="Featured Image">
                <div class="cont2-body">
                    <h2>Finding Peace in Chaos</h2>
                    <p>Discover practical techniques to incorporate mindfulness into your daily routine, even when your schedule seems overwhelming. Learn how small moments of presence can transform your workday.</p>
                </div>
                <div class="cont2-footer">
                    <div class="author">
                        <strong>By: Admin</strong><br>
                        <span><?php echo date('M j, Y'); ?></span>
                    </div>
                </div>
                <a href="#">Read More</a>
            <?php endif; ?>
        </div>

        <main class="main-content">
            <!-- Top 6 recent articles -->
            <h1 class="title">Latest Articles</h1>
            <div class="post-container">
                <?php if (!empty($latestPosts)): ?>
                    <?php foreach ($latestPosts as $post): ?>
                        <a href="postpage.php?id=<?php echo $post['id']; ?>" class="post-box">
                            <img src="<?php echo htmlspecialchars($post['image'] ? '../Author/images/' . $post['image'] : 'image/images.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-image">
                            <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                            <p class="post-details"><?php echo htmlspecialchars(shortText($post['content'], 200)); ?></p>
                            <div class="author">
                                <strong>By: <?php echo htmlspecialchars($post['username']); ?></strong>
                                <span><?php echo assignDate($post['created_at']); ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Display this content if no posts exist -->
                    <a href="#" class="post-box">
                        <img src="image/images.jpg" alt="Post 1" class="post-image">
                        <h2 class="post-title">No Posts Available</h2>
                        <p class="post-details">There are currently no posts to display. Check back later for new content!</p>
                        <div class="author">
                            <strong>By: Admin</strong>
                            <span><?php echo date('M j, Y'); ?></span>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <!-- All posts in random order -->
            <h1 class="title">Articles</h1>
            <div class="art-posts-container">
                <?php if (!empty($allPosts)): ?>
                    <?php foreach ($allPosts as $post): ?>
                        <a href="postpage.php?id=<?php echo $post['id']; ?>" class="art-post">
                            <div class="art-post-image">
                                <img src="<?php echo htmlspecialchars($post['image'] ? '../Author/images/' . $post['image'] : 'image/images.jpg'); ?>" 
                                    alt="<?php echo htmlspecialchars($post['title']); ?>" class="latest-post-image">
                            </div>
                            <div class="art-post-content">
                                <h2 class="art-post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                                <p class="art-post-details"><?php echo htmlspecialchars(shortText($post['content'], 400)); ?></p>
                                <div class="art-author">
                                    <strong>By: <?php echo htmlspecialchars($post['username']); ?></strong>
                                    <span><?php echo assignDate($post['created_at']); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Display this content if no posts exist -->
                    <a href="#" class="art-post">
                        <div class="art-post-image">
                            <img src="image/images.jpg" alt="No Posts" class="latest-post-image">
                        </div>
                        <div class="art-post-content">
                            <h2 class="art-post-title">No Articles Available</h2>
                            <p class="art-post-details">There are currently no articles to display. Check back later for new content!</p>
                            <div class="art-author">
                                <strong>By: Admin</strong>
                                <span><?php echo date('M j, Y'); ?></span>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>