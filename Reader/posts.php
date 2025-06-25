<?php
session_start();
include '../db.connect.php';
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

// Get all posts
$posts = selectAll('post', ['status' => 'published']);
$posts = array_filter($posts, function($post) {
    return empty($post['deleted']) || $post['deleted'] == 0;
});
// Get all comments for all posts
$comments = selectAll('comments', []);

// Get comments by post_id
$commentsByPost = [];
foreach ($comments as $comment) {
    $commentsByPost[$comment['post_id']][] = $comment;
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

function getLatestActivity($comment, $tree) {
    $latest = strtotime($comment['created_at']);
    $id = $comment['id'];
    if (!empty($tree[$id])) {
        foreach ($tree[$id] as $child) {
            $childLatest = getLatestActivity($child, $tree);
            if ($childLatest > $latest) {
                $latest = $childLatest;
            }
        }
    }
    return $latest;
}

function getPostLatestActivity($post, $comments) {
    $latest = strtotime($post['created_at']);
    if (!empty($post['updated_at'])) {
        $updated = strtotime($post['updated_at']);
        if ($updated > $latest) $latest = $updated;
    }
    foreach ($comments as $comment) {
        $cTime = strtotime($comment['created_at']);
        if ($cTime > $latest) $latest = $cTime;
    }
    return $latest;
}

// Sort posts by latest activity 
usort($posts, function($a, $b) use ($commentsByPost) {
    $aComments = $commentsByPost[$a['id']] ?? [];
    $bComments = $commentsByPost[$b['id']] ?? [];
    $aLatest = getPostLatestActivity($a, $aComments);
    $bLatest = getPostLatestActivity($b, $bComments);
    return $bLatest - $aLatest;
});

function displayComments($tree, $comments, $level = 0) {
    foreach ($comments as $comment) {
        echo '<div class="comm" style="margin-left:'.($level*30).'px">';
        echo '<strong>' . htmlspecialchars($comment['username']) . '</strong>';
        echo '<p>' . nl2br(htmlspecialchars($comment['comment_text'])) . '</p>';
        echo '<span class="comment-date">' . date('M j, Y H:i', strtotime($comment['created_at'])) . '</span>';
        if (!empty($tree[$comment['id']])) {
            $children = $tree[$comment['id']];
            usort($children, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            displayComments($tree, $children, $level+1);
        }
        echo '</div>';
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
    <link rel="stylesheet" href="posts.css">
</head>
<body>

    <?php include ("header.php")?>

    <div style="margin-top: 90px;">
        <?php foreach ($posts as $post): ?>
            <?php
                $author_pic = 'image/profile.png'; 
                $author = selectOne('users', ['username' => $post['username']]);
                if ($author && !empty($author['profile_picture'])) {
                    $author_pic = '../profiles/' . $author['profile_picture'];
                }
            ?>
            <div class="feed-box">
                <div class="feed-header">
                    <div class="user-row">
                        <img class="profile-avatar" src="<?php echo htmlspecialchars($author_pic); ?>" alt="Author Profile Picture">
                        <span class="username"><?php echo htmlspecialchars($post['username']); ?></span>
                    </div>
                    <img class="feed-image-square" src="<?php echo !empty($post['image']) ? '../Author/images/' . htmlspecialchars($post['image']) : 'image/type.avif'; ?>" alt="Post Image">
                </div>
                <div class="feed-title"><?php echo htmlspecialchars($post['title']); ?></div>
                <div class="feed-content"><?php echo nl2br(htmlspecialchars(shortText($post['content'], 500))); ?></div>
                
                <div class="feed-meta">
                    Created: <?php echo date('M j, Y H:i', strtotime($post['created_at'])); ?>
                    <?php if ($post['updated_at'] && $post['updated_at'] !== $post['created_at']): ?>
                        | Updated: <?php echo date('M j, Y H:i', strtotime($post['updated_at'])); ?>
                    <?php endif; ?>
                </div>
                <button class="view-comments-btn" data-postid="<?php echo $post['id']; ?>">View Comments</button>
                <div class="comments-section" id="comments-<?php echo $post['id']; ?>" style="display:none;">
                    <?php
                    $postComments = $commentsByPost[$post['id']] ?? [];
                    $commentTree = buildCommentTree($postComments);
                    $order = $commentTree[null] ?? [];
                    usort($order, function($a, $b) use ($commentTree) {
                        $aLatest = getLatestActivity($a, $commentTree);
                        $bLatest = getLatestActivity($b, $commentTree);
                        return $bLatest - $aLatest;
                    });
                    if (count($order) === 0) {
                        echo "<div style='color:#888;'>No comments yet.</div>";
                    } else {
                        displayComments($commentTree, $order);
                    }
                    ?>
                </div>
                <div style="margin-top:10px;text-align:right;">
                    <a href="postpage.php?id=<?php echo $post['id']; ?>" style="color:#2C3E50;font-size:14px;text-decoration:underline;">Go to post</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        document.querySelectorAll('.view-comments-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.comments-section').forEach(sec => {
                    if (sec !== document.getElementById('comments-' + this.dataset.postid)) {
                        sec.style.display = 'none';
                    }
                });
                const section = document.getElementById('comments-' + this.dataset.postid);
                section.style.display = (section.style.display === 'block') ? 'none' : 'block';
            });
        });
    </script>

</body>
</html>
