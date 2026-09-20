<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/admin_guard.php';

$posts = read_json_file('posts.json');
respond(['ok' => true, 'posts' => array_reverse($posts)]);
