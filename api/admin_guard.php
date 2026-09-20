<?php
// 引入此文件即可校验管理员是否已登录，未登录直接拒绝访问
session_start();
if (empty($_SESSION['sgbc_admin'])) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'unauthorized']);
    exit;
}
