<?php
require_once __DIR__ . '/helpers.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok' => false, 'error' => 'method_not_allowed']);

$input = get_json_input();
$username = trim($input['username'] ?? '');
$password = (string)($input['password'] ?? '');

if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
    $_SESSION['sgbc_admin'] = true;
    $_SESSION['admin_login_time'] = time();
    respond(['ok' => true]);
}

respond(['ok' => false, 'error' => 'invalid_credentials']);
