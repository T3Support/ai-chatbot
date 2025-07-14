<?php
require_once __DIR__ . '/../vendor/autoload.php';
use AIChatBot\AdminAuth;
use AIChatBot\ChatBot;
AdminAuth::check();
$bot = new ChatBot();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'upload' && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        if ($file['size'] > 524288000) { // 500MB max for safety
            $msg = "File too large.";
        } elseif (!preg_match('/\\.(pdf|txt|csv|docx|json)$/i', $file['name'])) {
            $msg = "Invalid file type.";
        } else {
            $bot->uploadFile($file['tmp_name'], $file['name']);
            $msg = "File uploaded.";
        }
        header("Location: admin.php?msg=" . urlencode($msg));
        exit;
    }
    if ($_POST['action'] === 'delete' && !empty($_POST['file_id'])) {
        $bot->deleteFile($_POST['file_id']);
        $msg = "File deleted.";
        header("Location: admin.php?msg=" . urlencode($msg));
        exit;
    }
}
header("Location: admin.php");
exit;
