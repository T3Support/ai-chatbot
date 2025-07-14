<?php
require_once __DIR__ . '/../vendor/autoload.php';
use AIChatBot\AdminAuth;
AdminAuth::logout();
header('Location: login.php');
exit;
