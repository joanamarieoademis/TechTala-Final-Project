<?php
session_start();
include '../db.connect.php';
include '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}

// Get user info
$user = null;
$user_sql = "SELECT username, email, created_at FROM users WHERE id = ?";
if ($stmt = $conn->prepare($user_sql)) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($username, $email, $created_at);
    if ($stmt->fetch()) {
        $user = [
            'username' => $username,
            'email' => $email,
            'created_at' => $created_at
        ];
    }
    $stmt->close();
}

if (!$user) {
    echo "<p style='color:red;text-align:center;'>User not found.</p>";
    exit();
}

// Fetch all comments by the user (latest first)
$comments = [];
$comment_sql = "SELECT c.id, c.post_id, c.parent_id, c.comment_text, c.created_at, c.users_id, u.username, p.title
                FROM comments c
                JOIN users u ON c.users_id = u.id
                JOIN post p ON c.post_id = p.id
                WHERE c.users_id = ?
                ORDER BY c.created_at DESC";
if ($stmt = $conn->prepare($comment_sql)) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($id, $post_id, $parent_id, $comment_text, $created_at, $users_id_c, $username, $title);
    while ($stmt->fetch()) {
        $comments[] = [
            'id' => $id,
            'post_id' => $post_id,
            'parent_id' => $parent_id,
            'comment_text' => $comment_text,
            'created_at' => $created_at,
            'users_id' => $users_id_c,
            'username' => $username,
            'title' => $title
        ];
    }
    $stmt->close();
}

// Collect parent_ids that are not null/0
$parentIds = [];
foreach ($comments as $c) {
    if (!empty($c['parent_id']) && $c['parent_id'] != 0) {
        $parentIds[] = $c['parent_id'];
    }
}
$parentIds = array_unique($parentIds);

// Fetch parent comments if any (latest first)
if (!empty($parentIds)) {
    $in = implode(',', array_map('intval', $parentIds));
    $parent_sql = "SELECT c.id, c.post_id, c.parent_id, c.comment_text, c.created_at, c.users_id, u.username, p.title
                   FROM comments c
                   JOIN users u ON c.users_id = u.id
                   JOIN post p ON c.post_id = p.id
                   WHERE c.id IN ($in)
                   ORDER BY c.created_at DESC";
    $result = $conn->query($parent_sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Only add parent if not already in $comments
            $alreadyExists = false;
            foreach ($comments as $c) {
                if ($c['id'] == $row['id']) {
                    $alreadyExists = true;
                    break;
                }
            }
            if (!$alreadyExists) {
                $comments[] = [
                    'id' => $row['id'],
                    'post_id' => $row['post_id'],
                    'parent_id' => $row['parent_id'],
                    'comment_text' => $row['comment_text'],
                    'created_at' => $row['created_at'],
                    'users_id' => $row['users_id'],
                    'username' => $row['username'],
                    'title' => $row['title']
                ];
            }
        }
    }
}

function buildCommentTree($comments) {
    $tree = [];
    foreach ($comments as $comment) {
        $parentID = $comment['parent_id'];
        if ($parentID === null || $parentID === '0' || $parentID === 0 || $parentID === '') {
            $parentID = null;
        }
        $tree[$parentID][] = $comment;
    }
    return $tree;
}

$commentTree = buildCommentTree($comments);

// :ookup array for comments by id for easy parent lookup
$commentsById = [];
foreach ($comments as $c) {
    $commentsById[$c['id']] = $c;
}

function displayCommentsList($tree, $parent_id = null, $level = 0, $commentsById = []) {
    if (empty($tree[$parent_id])) return;
    foreach ($tree[$parent_id] as $comment) {
        $postUrl = "postpage.php?id=" . urlencode($comment['post_id']) . "#comment-" . $comment['id'];
        // Reply
        $isReply = !empty($comment['parent_id']) && isset($commentsById[$comment['parent_id']]);
        $marginLeft = $isReply ? 0 : ($level * 30);

        echo '<li style="margin-left:' . $marginLeft . 'px; cursor:pointer;" onclick="window.location.href=\'' . $postUrl . '\'">';
        echo '<div style="font-size:18px;font-weight:600;color:#2C3E50;margin-bottom:8px;">';
        echo '<a href="' . $postUrl . '" style="color:#2C3E50;text-decoration:none;">' . htmlspecialchars($comment['title']) . '</a>';
        echo '</div>';

        // Reply, show parent comment and username 
        if ($isReply) {
            $parent = $commentsById[$comment['parent_id']];
            echo '<div style="background:#f4f4f4;padding:10px 15px;border-radius:7px;margin-bottom:10px;">';
            echo '<span style="font-weight:600;color:#2980b9;">' . htmlspecialchars($parent['username']) . '</span>: ';
            echo '<span style="color:#555;">' . htmlspecialchars($parent['comment_text']) . '</span>';
            echo '</div>';
        }

        echo '<strong>' . htmlspecialchars($comment['username']) . '</strong><br>';
        echo '<span>' . htmlspecialchars($comment['comment_text']) . '</span>';
        echo '<p>';
        echo '<span>' . date('M d, Y H:i', strtotime($comment['created_at'])) . '</span>';
        echo ' <a href="#" style="color:#2980b9;text-decoration:none;font-weight:500;margin-left:10px;">Reply</a>';
        echo '</p>';
        echo '</li>';
        displayCommentsList($tree, $comment['id'], $level + 1, $commentsById);
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
    <link rel="stylesheet" href="comments.css">
</head>
<body>
    <?php include ("header.php")?>

    <div class="main">
        <div class="details">
            <ul>
                <li class="name"><h1><span><?php echo htmlspecialchars($user['username']); ?></span></h1></li>
                <li class="email"><span><?php echo htmlspecialchars($user['email']); ?></span></li>
                <li class="date">Date Joined: <span>
                    <?php echo $user['created_at'] ? date('F j, Y', strtotime($user['created_at'])) : 'N/A'; ?>
                </span></li>
            </ul>
        </div>
        <h1 class="title">My Comments</h1>
        <div class="comments">
            <ul>
                <?php
                foreach ($comments as $comment) {
                    $postUrl = "postpage.php?id=" . urlencode($comment['post_id']) . "#comment-" . $comment['id'];
                    // Reply
                    $isReply = !empty($comment['parent_id']) && isset($commentsById[$comment['parent_id']]);
                    echo '<li style="cursor:pointer;" onclick="window.location.href=\'' . $postUrl . '\'">';
                    echo '<div style="font-size:17px;font-weight:600;color:#2C3E50;margin-bottom:8px;">';
                    echo '<a href="' . $postUrl . '" style="color:#2C3E50;text-decoration:none;">' . htmlspecialchars($comment['title']) . '</a>';
                    echo '</div>';

                    if ($isReply) {
                        $parent = $commentsById[$comment['parent_id']];
                        echo '<div style="background:#f4f4f4;padding:10px 15px;border-radius:7px;margin-bottom:10px;">';
                        echo '<span style="font-weight:600;color:#2980b9;">' . htmlspecialchars($parent['username']) . '</span>: ';
                        echo '<span style="color:#555;">' . htmlspecialchars($parent['comment_text']) . '</span>';
                        echo '</div>';
                    }

                    echo '<strong>' . htmlspecialchars($comment['username']) . '</strong><br>';
                    echo '<span>' . htmlspecialchars($comment['comment_text']) . '</span>';
                    echo '<p>';
                    echo '<span>' . date('M d, Y H:i', strtotime($comment['created_at'])) . '</span>';
                    echo ' <a href="#" style="color:#2980b9;text-decoration:none;font-weight:500;margin-left:10px;">Reply</a>';
                    echo '</p>';
                    echo '</li>';
                }
                if (empty($comments)) {
                    echo '<li>No comments found.</li>';
                }
                ?>
            </ul>
        </div>
    </div>

     <?php
    if (isset($conn)) {
        $conn->close();
    }
    ?>
</body>
</html>