<?php
require_once __DIR__ . '/../vendor/autoload.php';
use AIChatBot\AdminAuth;
use AIChatBot\ChatBot;
AdminAuth::check();
$bot = new ChatBot();

$msg = '';
if (isset($_GET['msg'])) $msg = $_GET['msg'];

$files = $bot->listFiles();
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Admin Portal</title>
<style>
body {background:#f6f9ff;font-family:Arial;}
.container{max-width:700px;margin:40px auto;background:#fff;padding:28px;border-radius:12px;box-shadow:0 2px 16px #ccd;}
h2{margin-top:0;}
.logout{float:right;}
.files-table{width:100%;border-collapse:collapse;}
.files-table th, .files-table td {padding:8px 10px;border-bottom:1px solid #e3e9f0;}
form.upload{margin-bottom:28px;}
button{background:#2d6aff;color:#fff;padding:7px 22px;border:none;border-radius:6px;cursor:pointer;}
</style>
</head>
<body>
<div class="container">
    <a class="logout" href="logout.php">Logout</a>
    <h2>Admin Portal: Manage Assistant Files</h2>
    <?php if ($msg): ?><div style="color:green"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form class="upload" method="POST" action="upload.php" enctype="multipart/form-data">
        <label>Upload new file for AI assistant:</label>
        <input type="file" name="file" required>
        <button type="submit" name="action" value="upload">Upload</button>
    </form>
    <h3>Current Assistant Files</h3>
    <table class="files-table">
        <tr><th>File ID</th><th>Name</th><th>Bytes</th><th>Status</th><th>Actions</th></tr>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?= htmlspecialchars($file['id']) ?></td>
                <td><?= htmlspecialchars($file['filename']) ?></td>
                <td><?= number_format($file['bytes']) ?></td>
                <td><?= htmlspecialchars($file['status']) ?></td>
                <td>
                    <form method="POST" action="upload.php" style="display:inline">
                        <input type="hidden" name="file_id" value="<?= htmlspecialchars($file['id']) ?>">
                        <button type="submit" name="action" value="delete" onclick="return confirm('Delete this file?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <hr>
    <a href="index.php">Back to Chat</a>
</div>
</body>
</html>
