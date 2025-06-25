<?php
session_start();

include('../db.connect.php');
include('../db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}

// Get dashboard stats
function dashboard() {
    $users = selectAll('users');
    // Only count authors and readers
    $users = array_filter($users, function($user) {
        return isset($user['role']) && ($user['role'] === 'author' || $user['role'] === 'reader');
    });
    $usersCount = count($users);

    $posts = selectAll('post');
    $postsCount = count($posts);

    $comments = selectAll('comments');
    $commentsCount = count($comments);

    return [
        'users' => $usersCount,
        'posts' => $postsCount,
        'comments' => $commentsCount
    ];
}

// Get functions 
$stats = dashboard();
$posts = posts();     
$users = users();     
$roles = role('');

// Filter out admins
$users = array_filter($users, function($user) {
    return isset($user['role']) && ($user['role'] === 'author' || $user['role'] === 'reader');
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/logo.png">
    <link rel="stylesheet" href="dashboard.css">
    <title>TechTala</title>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="contents">
        <h1 class="dash">Admin Dashboard</h1>

        <!-- Total users, comments and posts in database -->
        <div class="content1">
            <div class="box">
                <h1>Users</h1>
                <h2><?= $stats['users'] ?></h2>
                <img src="image/team.png" alt="">
            </div>

            <div class="box">
                <h1>Posts</h1>
                <h2><?= $stats['posts'] ?></h2>
                <img src="image/social-media.png" alt="">
            </div>

            <div class="box">
                <h1>Comments</h1>
                <h2><?= $stats['comments'] ?></h2>
                <img src="image/comment.png" alt="">
            </div>
        </div>

    </div>

    <div class="wrap">
        <div class="content2">
            <div class="view">
                <h4 class="caption">Recent Articles</h4>
                <a class="link" href="posts.php">View All</a>
            </div>

            <div class="table">
                <table class="post-mgt">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Date</th>
                    </tr>

                    <!-- Display articles -->
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td class="post-title"><?= htmlspecialchars($post['title']) ?></td>
                        <td><?= htmlspecialchars($post['username']) ?></td>
                        <td><?= date('F j, Y', strtotime($post['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="content3">
            <div class="view">
                <h4 class="caption">Users</h4>
                <a class="link" href="users.php">View All</a>
            </div>

            <div class="table">
                <table class="users">
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>

                    <!-- Display users -->
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php if (!empty($user['profile_picture'])): ?>
                            <img src="../profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                            
                                <?php else: ?>
                                    <img src="image/profile.png" alt="Default Profile">
                                <?php endif; ?>
                            <?= htmlspecialchars($user['username']) ?>
                        </td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
