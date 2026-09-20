<?php
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok' => false, 'error' => 'method_not_allowed']);

$input = get_json_input();
$first_name = trim($input['first_name'] ?? '');
$last_name  = trim($input['last_name'] ?? '');
$email      = trim(strtolower($input['email'] ?? ''));
$country    = trim($input['country'] ?? '');
$password   = (string)($input['password'] ?? '');

// 校验必填字段
if ($first_name === '' || $last_name === '' || $email === '' || $password === '') {
    respond(['ok' => false, 'error' => 'required']);
}
if (!is_valid_email($email)) {
    respond(['ok' => false, 'error' => 'invalid_email']);
}
if (strlen($password) < 6) {
    respond(['ok' => false, 'error' => 'weak_password']);
}

$users = read_json_file('users.json');

// 检查邮箱是否已注册
foreach ($users as $u) {
    if (strtolower($u['email']) === $email) {
        respond(['ok' => false, 'error' => 'exists']);
    }
}

$newUser = [
    'id'         => gen_id(),
    'first_name' => $first_name,
    'last_name'  => $last_name,
    'email'      => $email,
    'country'    => $country,
    'password'   => password_hash($password, PASSWORD_DEFAULT),
    'created_at' => date('Y-m-d H:i:s'),
    'status'     => 'active',
];

$users[] = $newUser;

if (!write_json_file('users.json', $users)) {
    respond(['ok' => false, 'error' => 'server']);
}

respond([
    'ok'   => true,
    'user' => [
        'id'         => $newUser['id'],
        'first_name' => $newUser['first_name'],
        'last_name'  => $newUser['last_name'],
        'email'      => $newUser['email'],
    ],
]);
