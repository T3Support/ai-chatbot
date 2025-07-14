<?php
require_once __DIR__ . '/../vendor/autoload.php';

use AIChatBot\ChatBot;

session_start();
if (!isset($_SESSION['chatbot_session_id'])) {
    $_SESSION['chatbot_session_id'] = bin2hex(random_bytes(8));
}
$session_id = $_SESSION['chatbot_session_id'];

$answer = '';
$question = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    if ($question) {
        $bot = new ChatBot();
        $answer = $bot->ask($question, $session_id);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI ChatBot (OpenAI File Search)</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f6fc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 50px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px #dde; padding: 30px; }
        h2 { margin-top: 0; }
        textarea { width: 100%; height: 80px; border-radius: 6px; border: 1px solid #ccd; padding: 8px; font-size: 1em; }
        button { background: #275eff; color: #fff; border: none; padding: 12px 28px; border-radius: 7px; font-size: 1em; margin-top: 12px; cursor: pointer; }
        .qa { margin-bottom: 22px; }
        .answer { background: #f7faff; padding: 14px; border-radius: 7px; margin-top: 15px; border-left: 3px solid #275eff44;}
    </style>
</head>
<body>
<div class="container">
    <h2>AI ChatBot (OpenAI File Search)</h2>
    <form method="POST" autocomplete="off">
        <div class="qa">
            <label for="question"><strong>Your question:</strong></label><br>
            <textarea id="question" name="question" required><?= htmlspecialchars($question) ?></textarea>
        </div>
        <button type="submit">Ask</button>
    </form>
    <?php if ($answer): ?>
        <div class="answer">
            <strong>AI:</strong><br>
            <?= nl2br(htmlspecialchars($answer)) ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
