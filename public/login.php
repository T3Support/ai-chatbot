<?php
require_once __DIR__ . '/../vendor/autoload.php';
use AIChatBot\AdminAuth;

session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw = $_POST['password'] ?? '';
    if (AdminAuth::login($pw)) {
        header('Location: admin.php');
        exit;
    } else {
        $error = "Incorrect password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Admin Login</title>
<style>
body { background: #f0f4f8; font-family: Arial;}
.login-box { max-width: 330px; margin: 100px auto; background: #fff; border-radius: 10px; box-shadow:0 2px 8px #dde; padding: 35px;}
input[type=password] {width:100%;padding:10px;margin:10px 0 15px 0;border:1px solid #ccd;border-radius:5px;}
button {background:#275eff;color:#fff;padding:10px 28px;border:none;border-radius:7px;font-size:1em;}
.error {color:crimson;}
</style>
</head>
<body>
<div class="login-box">
    <h2>Admin Login</h2>
    <?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
        <input type="password" name="password" placeholder="Admin password" required autofocus>
        <button type="submit">Log In</button>
    </form>
</div>
</body>
</html>
