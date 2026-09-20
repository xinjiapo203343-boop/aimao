<?php
require_once __DIR__ . '/helpers.php';

function default_content() {
    return [
        'whatsapp_number' => '33765114441',
        'footer_copyright' => '© 2026 SG BizConnect Pte. Ltd. · Reg. No. 202XXXXXX · UEN: 202XXXXXX',
        'hero_stats' => [
            ['num' => '2,400+', 'label_key' => 'stat1'],
            ['num' => '18,000+', 'label_key' => 'stat2'],
            ['num' => '40+', 'label_key' => 'stat3'],
            ['num' => 'SGD 12M+', 'label_key' => 'stat4'],
        ],
        'top10' => [
            ['id' => 't1', 'name' => 'StarTeam Corporate Services', 'spec' => 'E-commerce & Tech Compliance · Secretary', 'rating' => '4.9', 'reviews' => '312'],
            ['id' => 't2', 'name' => 'Raffles Secretarial & Nominee SG', 'spec' => 'Nominee Director · Family Office · Holding Cos', 'rating' => '4.8', 'reviews' => '228'],
            ['id' => 't3', 'name' => 'Apex Asia Corporate Pte Ltd', 'spec' => 'Foreign Entity Setup · Tax & Audit · Incorporation', 'rating' => '4.8', 'reviews' => '187'],
            ['id' => 't4', 'name' => 'Orchard Legal & Secretarial', 'spec' => 'Startup Incorporation · Equity Docs · Shareholder Agreements', 'rating' => '4.7', 'reviews' => '154'],
            ['id' => 't5', 'name' => 'Marina Bay Business Hub', 'spec' => 'Bank Account Introduction · DBS / OCBC Green Channel', 'rating' => '4.7', 'reviews' => '131'],
            ['id' => 't6', 'name' => 'SG EntrePass Advisory Group', 'spec' => 'EP · EntrePass · Dependent Pass · MOM Applications', 'rating' => '4.6', 'reviews' => '98'],
        ],
    ];
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $content = read_json_file('content.json');
    if (empty($content)) $content = default_content();
    respond(['ok' => true, 'content' => $content]);
}

if ($method === 'POST') {
    // 修改内容需要管理员登录
    require_once __DIR__ . '/admin_guard.php';

    $input = get_json_input();
    $content = read_json_file('content.json');
    if (empty($content)) $content = default_content();

    if (isset($input['whatsapp_number'])) {
        $content['whatsapp_number'] = trim($input['whatsapp_number']);
    }
    if (isset($input['footer_copyright'])) {
        $content['footer_copyright'] = trim($input['footer_copyright']);
    }
    if (isset($input['hero_stats']) && is_array($input['hero_stats'])) {
        $content['hero_stats'] = $input['hero_stats'];
    }
    if (isset($input['top10']) && is_array($input['top10'])) {
        $content['top10'] = $input['top10'];
    }

    if (!write_json_file('content.json', $content)) {
        respond(['ok' => false, 'error' => 'server']);
    }
    respond(['ok' => true, 'content' => $content]);
}

respond(['ok' => false, 'error' => 'method_not_allowed']);
