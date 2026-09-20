<?php
require_once __DIR__ . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $posts = read_json_file('posts.json');
    // 只把未被隐藏的话题展示给公开访客
    $visible = array_values(array_filter($posts, function($p) {
        return empty($p['hidden']);
    }));
    respond(['ok' => true, 'posts' => array_reverse($visible)]);
}

if ($method === 'POST') {
    $input = get_json_input();
    $title   = trim($input['title'] ?? '');
    $content = trim($input['content'] ?? '');
    $tag     = trim($input['tag'] ?? '');
    $author  = trim($input['author'] ?? '');

    if ($title === '' || $content === '') {
        respond(['ok' => false, 'error' => 'required']);
    }
    if (mb_strlen($title) > 200 || mb_strlen($content) > 5000) {
        respond(['ok' => false, 'error' => 'too_long']);
    }

    $posts = read_json_file('posts.json');

    $colors = ['#DC2626','#7C3AED','#059669','#D97706','#2563EB'];
    $newPost = [
        'id'         => gen_id(),
        'author'     => $author !== '' ? $author : 'Guest',
        'avatar'     => mb_substr($author !== '' ? $author : 'G', 0, 1),
        'color'      => $colors[array_rand($colors)],
        'title'      => $title,
        'content'    => $content,
        'tag'        => $tag,
        'comments'   => 0,
        'likes'      => 0,
        'views'      => 1,
        'hidden'     => false,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    $posts[] = $newPost;
    if (!write_json_file('posts.json', $posts)) {
        respond(['ok' => false, 'error' => 'server']);
    }

    respond(['ok' => true, 'post' => $newPost]);
}

respond(['ok' => false, 'error' => 'method_not_allowed']);
