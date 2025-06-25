<?php
session_start();
include '../db.connect.php';
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($post_id <= 0) {
    header('Location: homepage.php');
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment_text'])) {
    $comment_text = trim($_POST['comment_text']);
    $parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
    $username = $_SESSION['username'] ?? 'Anonymous';
    $user_id = $_SESSION['user_id'] ?? null;

    $data = [
        'username' => $username,
        'comment_text' => $comment_text,
        'created_at' => date('Y-m-d H:i:s'),
        'users_id' => $user_id,
        'post_id' => $post_id,
        'parent_id' => $parent_id
    ];
    insert('comments', $data);
    header("Location: view.php?id=$post_id");
    exit();
}

$post = selectOne('post', ['id' => $post_id]);
if (!$post) {
    header('Location: homepage.php');
    exit();
}

$comments = selectAll('comments', ['post_id' => $post_id]);

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

// Get all top-level comments 
$order = $commentTree[null] ?? [];

// Sort comments by latest activity 
usort($order, function($a, $b) use ($commentTree) {
    $aLatest = getLatestActivity($a, $commentTree);
    $bLatest = getLatestActivity($b, $commentTree);
    return $bLatest - $aLatest;
});

function displayComments($tree, $comments, $level = 0) {
    foreach ($comments as $comment) {
        echo '<div class="comm" style="margin-left:'.($level*30).'px">';
        echo '<strong style="font-size:13px; color: #333;">' . htmlspecialchars($comment['username']) . '</strong>';
        echo '<p style="font-size: 14px; color: #222; margin-top:5px;">' . nl2br(htmlspecialchars($comment['comment_text'])) . '</p>';
        echo '<span class="comment-date" style="font-size:13px; color: #555; margin-top:0;">' . date('M j, Y H:i', strtotime($comment['created_at'])) . '</span>';
        echo " ";
        echo '<a href="#" style="font-size:12px;" class="reply-link" data-commentid="'.$comment['id'].'">Reply</a>';
        echo '<form class="reply-form" method="post" style="display:none;margin-top:5px;" data-parentid="'.$comment['id'].'">
                <input type="hidden" name="parent_id" value="'.$comment['id'].'">
                <input type="text" style="border-radius:6px; padding:5px; font-size:12px;" name="comment_text" placeholder="Reply..." required>
                <button type="submit" style="margin-top: 10px;
                    padding: 5px 5px;
                    background-color: #2C3E50;
                    color: #fff;
                    border: none;
                    border-radius: 6px;
                    font-size: 10px;
                    cursor: pointer;
                    transition: 0.3s ease;">Post</button>
              </form>';
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
    <link rel="stylesheet" href="view.css">
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
            <div  class="content">
                <?php echo $post['content']; ?>
            </div>
            <h4><?php echo htmlspecialchars($post['username']); ?></h4>
            <span><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
        </div>

        <div class="comment-s">
            <div class="comments">
                <hr>
                <h2>Comments</h2>
                <?php displayComments($commentTree, $order); ?>
            </div>
            <form action="" method="post" autocomplete="off">
                <div class="c-section">
                    <label><span>Comment</span></label><br>
                    <input type="text" name="comment_text" id="type-comment" placeholder="Comment Here" autocomplete="off" required>
                    <input type="hidden" name="parent_id" value="">
                </div>
                <div class="post">
                    <button type="submit">Post</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.reply-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.nextElementSibling;
                form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
            });
        });
        document.querySelectorAll('.reply-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!this.querySelector('input[name="comment_text"]').value.trim()) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>