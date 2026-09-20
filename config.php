<?php
/**
 * SG BizConnect - 全局配置文件
 * ============================
 * 【重要】部署到服务器后，请立即修改下面的管理员密码 ADMIN_PASSWORD_PLAIN！
 * 修改方法：直接把引号里的文字改成你自己的强密码即可，无需其他操作，
 * 系统会自动加密处理，你不需要手动生成哈希值。
 */

// 管理员登录账号
define('ADMIN_USERNAME', 'admin');

// 管理员登录密码（请修改为你自己的强密码，建议包含大小写字母、数字和符号）
define('ADMIN_PASSWORD_PLAIN', 'Qaz123!!!');

// 系统自动加密上面的密码，用于安全比对，无需手动修改这一行
define('ADMIN_PASSWORD_HASH', password_hash(ADMIN_PASSWORD_PLAIN, PASSWORD_DEFAULT));

// 数据存放目录（已通过 data/.htaccess 保护，禁止外部直接访问）
define('DATA_DIR', __DIR__ . '/data');

// 是否开启调试模式（部署到正式服务器后，请设置为 false，避免向访客暴露报错信息）
define('DEBUG_MODE', false);

// 时区设置
date_default_timezone_set('Asia/Singapore');

// 错误显示设置
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
