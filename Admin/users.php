<?php
session_start();

include ('../db.connect.php');
include ('../db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /TechTala/Authentication/homepage.html");
    exit();
}

// user deletion handler
if (isset($_POST['delete'])) {
    $user_id = (int)$_POST['user'];
    $delete = delete('users', $user_id);
    $alert = $delete > 0 ? "User deleted successfully!" : "Failed to delete user.";
    $alert_type = $delete > 0 ? 'success' : 'error';
}

// calling the users and role function
$users = users();
$roles = role('');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TechTala</title>
    <link rel="icon" href="../image/logo.png">
    <link rel="stylesheet" href="users.css">
</head>
<body>
    <?php include("header.php") ?>

    <div class="container">
        <h1>User Manager</h1>

        <?php if (isset($alert)): ?>
            <div class="alert alert-<?= $alert_type ?>">
                <?= htmlspecialchars($alert) ?>
            </div>
        <?php endif; ?>

        <div class="s-main">
            <img class="s-image" src="image/search.png" alt="">
            <input class="s-box" type="text" placeholder="Search">
        </div>

        <div class="users">
            <?php if (empty($users)): ?>
                <div class="no-users">No users found in the database.</div>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <div class="box-user">
                        <div id="<?= role($user['role']) ?>">

                            <!-- Displaying the users profile -->
                            <?php if (!empty($user['profile_picture'])): ?>
                                <img src="../profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                                
                            <?php else: ?>
                                <img src="image/profile.png" alt="Default Profile">
                            <?php endif; ?>
                            
                            <h3><?= htmlspecialchars($user['username']) ?></h3>
                            <h2 id="role"><?= htmlspecialchars(ucfirst($user['role'] ?? 'Reader')) ?></h2>
                            
                            <div class="user-info">
                                <small>@<?= htmlspecialchars($user['username']) ?></small>
                            </div>

                            <?php if ($user['role'] !== 'reader' && $user['role']  !== 'admin'): ?>
                                <div class="count">
                                    <p class="value"><?= $user['post_count'] ?></p>
                                    <p class="label">Posts</p>
                                </div>
                            <?php endif; ?>

                            <?php if ($user['role'] !== 'admin'): ?>
                                <div class="count">
                                    <p class="value"><?= $user['comment_count'] ?></p>
                                    <p class="label">Comments</p>
                                </div>
                            <?php endif; ?>

                            <form method="POST"  class="delete-form" onsubmit="return confirm('Delete this user and all their posts/comments?');">
                                <input type="hidden" name="user" value="<?= $user['user_id'] ?>">
                                <button type="submit" name="delete" class="user-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($users)): ?>
            <p class="total-users">
                Total users: <?= count($users) ?>
            </p>
        <?php endif; ?>
    </div>

    <script src="delete.js"></script>
</body>
</html>