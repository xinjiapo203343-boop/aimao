<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/admin_guard.php';

$users = read_json_file('users.json');
$providers = read_json_file('providers.json');

// 不把密码哈希返回给前端
$safeUsers = array_map(function($u) {
    unset($u['password']);
    return $u;
}, $users);

$pendingCount = 0;
foreach ($providers as $p) {
    if ($p['status'] === 'pending') $pendingCount++;
}

respond([
    'ok' => true,
    'users' => array_reverse($safeUsers),
    'providers' => array_reverse($providers),
    'stats' => [
        'total_users' => count($users),
        'total_providers' => count($providers),
        'pending_providers' => $pendingCount,
    ],
]);
