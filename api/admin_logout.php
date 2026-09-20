<?php
require_once __DIR__ . '/helpers.php';
session_start();
$_SESSION = [];
session_destroy();
respond(['ok' => true]);
