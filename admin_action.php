<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/admin_guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok' => false, 'error' => 'method_not_allowed']);

$input = get_json_input();
$action = $input['action'] ?? '';
$id     = $input['id'] ?? '';
$type   = $input['type'] ?? ''; // 'user' or 'provider'

if ($id === '' || !in_array($type, ['user', 'provider', 'post'])) {
    respond(['ok' => false, 'error' => 'invalid_request']);
}

$fileMap = ['user' => 'users.json', 'provider' => 'providers.json', 'post' => 'posts.json'];
$file = $fileMap[$type];
$rows = read_json_file($file);
$found = false;

foreach ($rows as $i => $row) {
    if ($row['id'] === $id) {
        $found = true;
        if ($action === 'delete') {
            array_splice($rows, $i, 1);
        } elseif ($action === 'approve' && $type === 'provider') {
            $rows[$i]['status'] = 'approved';
        } elseif ($action === 'reject' && $type === 'provider') {
            $rows[$i]['status'] = 'rejected';
        } elseif ($action === 'suspend' && $type === 'user') {
            $rows[$i]['status'] = 'suspended';
        } elseif ($action === 'activate' && $type === 'user') {
            $rows[$i]['status'] = 'active';
        } elseif ($action === 'hide' && $type === 'post') {
            $rows[$i]['hidden'] = true;
        } elseif ($action === 'unhide' && $type === 'post') {
            $rows[$i]['hidden'] = false;
        } else {
            respond(['ok' => false, 'error' => 'unknown_action']);
        }
        break;
    }
}

if (!$found) respond(['ok' => false, 'error' => 'not_found']);
if (!write_json_file($file, $rows)) respond(['ok' => false, 'error' => 'server']);

respond(['ok' => true]);
