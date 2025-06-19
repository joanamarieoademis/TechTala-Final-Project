<?php
session_start();

include ('../db.connect.php');
include ('../db.php');

// Checkif user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}

// Comment deletion handler
if (isset($_POST['delete'])) {
    $comment_id = (int)$_POST['comment'];
    $deleted = delete('comments', $comment_id);
    
    if ($deleted > 0) {
        $success_message = "Comment deleted successfully!";
    } else {
        $error_message = "Failed to delete comment.";
    }
}

// Get comments
function comments() {
    global $conn;
    
    $sql = "SELECT 
                c.id as comment_id,
                c.comment_text,
                c.created_at,
                c.users_id,
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
    <link rel="stylesheet" href="comments.css">
    <title>TechTala</title>
    
</head>
<body>

    <?php include("header.php") ?>

    <div class="container">
        <h1>Comment Manager</h1>

        <!-- Display success or error messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

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
                    <th>Action</th>
                </tr>

                <!-- Display if no comments found -->
                <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="5" class="no-comments">
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
                                <?php echo htmlspecialchars($comment['post_title'] ?? 'Unknown Post'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($comment['username'] ?? 'Unknown User'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(assignDate($comment['created_at'])); ?>
                            </td>
                            <td>
                                <!-- Confirmation message -->
                                <form method="POST" class="delete-form" 
                                      onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    <input type="hidden" name="comment" value="<?php echo $comment['comment_id']; ?>">
                                    <button type="submit" name="delete" class="delete">
                                        <img src="image/delete.png" alt="Delete">
                                    </button>
                                </form>
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

    <script src="delete.js"></script>

</body>
</html>