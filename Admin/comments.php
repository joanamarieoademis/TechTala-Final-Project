<?php
session_start();

include ('../db.connect.php');
include ('../db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}

// Get comments
function comments() {
    global $conn;
    
    $sql = "SELECT 
                c.id as comment_id,
                c.comment_text,
                c.created_at,
                c.users_id,
                c.post_id,
                u.username,
                p.title as post_title
            FROM comments c
            LEFT JOIN users u ON c.users_id = u.id
            LEFT JOIN post p ON c.post_id = p.id
            ORDER BY c.created_at DESC";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$comments = comments();

// Format date function
$date = assignDate('');
// Shorten the long comments
$text = shortText('');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/logo.png">
    <link rel="stylesheet" href="comment.css">
    <title>TechTala</title>
</head>
<body>

    <?php include("header.php") ?>

    <div class="container">
        <h1>Comment Manager</h1>

        <div class="s-main">
            <img class="s-image" src="image/search.png" alt="">
            <input class="s-box" type="text" placeholder="Search">
        </div>

        <div class="table">
            <table class="comment-mgt">
                <tr>
                    <th>Comment</th>
                    <th>On Post</th>
                    <th>User</th>
                    <th>Date</th>
                </tr>

                <!-- Display if no comments found -->
                <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="4" class="no-comments">
                            No comments found in the database.
                        </td>
                    </tr>
                <?php else: ?>

                    <!-- Display comments -->
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td class="comment-text">
                                <?php echo htmlspecialchars(shortText($comment['comment_text'])); ?>
                            </td>
                            <td class="post-title">
                                <?php if ($comment['post_id'] && $comment['post_title']): ?>
                                    <a href="comm.php?id=<?php echo $comment['post_id']; ?>#comment-<?php echo $comment['comment_id']; ?>" 
                                       class="post">
                                        <?php echo htmlspecialchars($comment['post_title']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="unknown-post">Unknown Post</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($comment['username'] ?? 'Unknown User'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(assignDate($comment['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>

        <!-- Getting the total comments -->
        <?php if (!empty($comments)): ?>
            <p class="total-users">
                Total comments: <?php echo count($comments); ?>
            </p>
        <?php endif; ?>
    </div>

</body>
</html>