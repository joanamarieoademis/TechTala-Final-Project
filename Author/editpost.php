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
$post_id = isset($_GET['id']) ? $_GET['id'] : null;

$post = selectOne('post', conditions: ['id' => $post_id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        header('Location: creations.php');
        exit();
    }

    if (isset($_POST['publish'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['format']) ;

        if (empty($title) || empty($content)) {
            $_SESSION['message'] = "Title and content are required.";
        } else {
            $update_data = [
                'title' => $title,
                'content' => $content
            ];

            // Uploading an image
            if (!empty($_FILES['fileUpload']['name'])) {
                $file_extension = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);
                $image_name = $user_id . '_' . $title . '.' . $file_extension;
                $destination = 'images/' . $image_name;

                if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $destination)) {
                    if (!empty($post['image']) && file_exists('images/' . $post['image'])) {
                        unlink('images/' . $post['image']);
                    }
                    $update_data['image'] = $image_name;
                } 
            }

            if (!isset($_SESSION['message'])) {
                $updated = update('post', $post_id, $update_data);

                if ($updated !== false && $updated >= 0) {
                    header('Location: creations.php');
                    exit();
                } 
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
    <title>TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="editpost.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="container">

    <form action="editpost.php?id=<?php echo $post_id; ?>" method="post" enctype="multipart/form-data" id="publish-form">
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
            <button type="submit" name="cancel" class="cancel">Cancel</button>
            <button type="submit" name="publish" class="publish">Save Changes</button>
        </div>
    </form>
</div>

    <script src="create_newblog.js"></script>
    <script>
        // Submitting the post
        document.getElementById('publish-form').addEventListener('submit', function (e) {
            if (e.submitter && e.submitter.name === 'publish') {
                document.getElementById('format').value = document.getElementById('content').value;
            }
        });
    </script>

</body>
</html>