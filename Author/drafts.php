<?php
session_start();
include '../db.connect.php';
include '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle draft deletion
if (isset($_POST['delete_draft'])) {
    $post_id = (int)$_POST['draft_id'];
    $deleted = delete('post', $post_id);
    if ($deleted > 0) {
        $alert = "Draft deleted successfully.";
        $alert_type = "success";
    } else {
        $alert = "Failed to delete draft.";
        $alert_type = "error";
    }
}

// Only fetch non-deleted drafts
$drafts = selectAll('post', ['users_id' => $user_id, 'status' => 'draft', 'deleted' => 0]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="draft.css">
</head>
<body>

<?php include("header.php") ?>

<div class="contents">
    <div class="cont2">
        <h1>My Drafts<br></h1>
        <small>Click the title to edit</small>

        <?php if (isset($alert)): ?>
            <div class="alert <?= $alert_type ?>">
                <?= htmlspecialchars($alert) ?>
            </div>
        <?php endif; ?>

        <div class="boxes">
            <?php if (!empty($drafts)): ?>
                <?php foreach ($drafts as $post): ?>
                    <div class="recents">
                        <?php if (!empty($post['image'])): ?>
                            <img src="images/<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <?php else: ?>
                            <img src="image/images.jpg" alt="Default Image">
                        <?php endif; ?>

                        <h2>
                            <a href="editdraft.php?id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h2>

                        <p>
                            <?php 
                                $content = strip_tags($post['content']);
                                echo htmlspecialchars(substr($content, 0, 200));
                                if (strlen($content) > 200) echo '...';
                            ?>
                        </p>

                        <div class="draft-footer">
                            <div class="author">
                                <strong>Saved as Draft</strong>
                                <?php if (isset($post['created_at'])): ?>
                                    <span><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Delete Button -->
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this draft?');">
                                <input type="hidden" name="draft_id" value="<?= $post['id'] ?>">
                                <button type="submit" name="delete_draft" class="delete-button">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have no drafts yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="homepage.js"></script>
</body>
</html>
