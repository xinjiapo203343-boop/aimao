<?php
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok' => false, 'error' => 'method_not_allowed']);

$input = get_json_input();
$email    = trim(strtolower($input['email'] ?? ''));
$password = (string)($input['password'] ?? '');

if ($email === '' || $password === '') {
    respond(['ok' => false, 'error' => 'required']);
}

$users = read_json_file('users.json');
foreach ($users as $u) {
    if (strtolower($u['email']) === $email) {
        if (password_verify($password, $u['password'])) {
            respond([
                'ok'   => true,
                'user' => [
                    'id'         => $u['id'],
                    'first_name' => $u['first_name'],
                    'last_name'  => $u['last_name'],
                    'email'      => $u['email'],
                ],
            ]);
        }
        break;
    }
}

respond(['ok' => false, 'error' => 'invalid_credentials']);
