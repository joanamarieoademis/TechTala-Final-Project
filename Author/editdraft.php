<?php
session_start();

include '../db.connect.php';
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = $_GET['id'] ?? null;

// Get the post (must belong to user and be a draft)
$post = selectOne('post', ['id' => $post_id, 'users_id' => $user_id]);

if (!$post || $post['status'] !== 'draft') {
    echo "This post either doesn't exist or is already published.";
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        header('Location: creations.php');
        exit();
    }

    if (isset($_POST['publish']) || isset($_POST['draft'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['format']);
        $status = isset($_POST['publish']) ? 'published' : 'draft';

        if (empty($title) || empty($content)) {
            $errors[] = "Title and content are required.";
        }

        $update_data = [
            'title' => $title,
            'content' => $content,
            'status' => $status
        ];

        // Handle image upload
        if (!empty($_FILES['fileUpload']['name'])) {
            $file_extension = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);
            $image_name = $user_id . '_' . time() . '.' . $file_extension;
            $destination = 'images/' . $image_name;

            if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $destination)) {
                // Delete old image if exists
                if (!empty($post['image']) && file_exists('images/' . $post['image'])) {
                    unlink('images/' . $post['image']);
                }
                $update_data['image'] = $image_name;
            } else {
                $errors[] = "Failed to upload image.";
            }
        } elseif ($status === 'published' && empty($post['image'])) {
            $errors[] = "An image is required to publish this post.";
        }

        if (empty($errors)) {
            $updated = update('post', $post_id, $update_data);
            if ($updated !== false) {
                if ($status === 'draft') {
                    header('Location: drafts.php');
                } else {
                    header('Location: creations.php');
                }
                exit();
            } else {
                $errors[] = "Failed to save post.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Draft | TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="editdraft.css">
</head>
<body>

    <?php include("header.php"); ?>

    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="editdraft.php?id=<?php echo $post_id; ?>" method="post" enctype="multipart/form-data" id="publish-form">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

            <label>Content</label>
            <div class="buttons">
                <button type="button" id="bold">B</button>
                <button type="button" id="italic">I</button>
                <button type="button" id="underline">U</button>
            </div>

            <textarea id="content" name="content" required><?php echo htmlspecialchars(remove($post['content'])); ?></textarea>
            <input type="hidden" id="format" name="format">

            <div id="preview" class="preview"></div>

            <label for="fileUpload">Upload or Replace Photo</label>
            <input type="file" id="fileUpload" name="fileUpload" accept="image/*" style="display: none;">

            <div class="upload-box" onclick="document.getElementById('fileUpload').click()">
                <?php if (!empty($post['image'])): ?>
                    <p>Current image (click to change)</p>
                    <img id="previewImage" src="images/<?php echo htmlspecialchars($post['image']); ?>" alt="Current image">
                <?php else: ?>
                    <p>Click to choose photo</p>
                    <img id="previewImage" src="" alt="Preview">
                <?php endif; ?>
            </div>

            <div class="actions">
                <button type="submit" name="draft" class="draft">Save as Draft</button>
                <button type="submit" name="publish" class="publish">Publish</button>
            </div>
        </form>
    </div>

    <script src="create_newblog.js"></script>

    <script>
        document.getElementById('publish-form').addEventListener('submit', function (e) {
            if (e.submitter && (e.submitter.name === 'publish' || e.submitter.name === 'draft')) {
                document.getElementById('format').value = document.getElementById('content').value;
            }
        });
    </script>

</body>
</html>
