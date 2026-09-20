<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

/** 读取 JSON 数据文件，不存在则返回空数组 */
function read_json_file($filename) {
    $path = DATA_DIR . '/' . $filename;
    if (!file_exists($path)) return [];
    $fp = fopen($path, 'r');
    if (!$fp) return [];
    flock($fp, LOCK_SH);
    $content = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

/** 写入 JSON 数据文件（带文件锁，避免并发写入损坏数据） */
function write_json_file($filename, $data) {
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0775, true);
    $path = DATA_DIR . '/' . $filename;
    $fp = fopen($path, 'c+');
    if (!$fp) return false;
    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

/** 读取前端 POST 过来的 JSON 请求体 */
function get_json_input() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** 输出 JSON 结果并结束脚本 */
function respond($arr) {
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

/** 简单邮箱格式校验 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/** 生成唯一 ID */
function gen_id() {
    return uniqid('', true);
}
