<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/admin_guard.php';

$checks = [];

$checks['php_version'] = PHP_VERSION;
$checks['php_version_ok'] = version_compare(PHP_VERSION, '7.0.0', '>=');

$checks['data_dir_exists'] = is_dir(DATA_DIR);
$checks['data_dir_writable'] = is_writable(DATA_DIR);

$testFile = DATA_DIR . '/.write_test';
$writeOk = @file_put_contents($testFile, 'test');
$checks['data_dir_actual_write_test'] = $writeOk !== false;
if ($writeOk !== false) @unlink($testFile);

$checks['session_working'] = isset($_SESSION['sgbc_admin']);
$checks['json_extension'] = extension_loaded('json');
$checks['users_json_readable'] = file_exists(DATA_DIR . '/users.json') && is_readable(DATA_DIR . '/users.json');
$checks['providers_json_readable'] = file_exists(DATA_DIR . '/providers.json') && is_readable(DATA_DIR . '/providers.json');
$checks['posts_json_readable'] = file_exists(DATA_DIR . '/posts.json') && is_readable(DATA_DIR . '/posts.json');

respond(['ok' => true, 'checks' => $checks]);
