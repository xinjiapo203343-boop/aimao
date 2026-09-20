<?php
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok' => false, 'error' => 'method_not_allowed']);

$input = get_json_input();
$company_name  = trim($input['company_name'] ?? '');
$provider_type = trim($input['provider_type'] ?? '');
$email         = trim(strtolower($input['email'] ?? ''));
$phone         = trim($input['phone'] ?? '');
$uen           = trim($input['uen'] ?? '');
$description   = trim($input['description'] ?? '');

if ($company_name === '' || $email === '' || $phone === '') {
    respond(['ok' => false, 'error' => 'required']);
}
if (!is_valid_email($email)) {
    respond(['ok' => false, 'error' => 'invalid_email']);
}

$providers = read_json_file('providers.json');

$newProvider = [
    'id'            => gen_id(),
    'company_name'  => $company_name,
    'provider_type' => $provider_type,
    'email'         => $email,
    'phone'         => $phone,
    'uen'           => $uen,
    'description'   => $description,
    'status'        => 'pending', // pending / approved / rejected
    'created_at'    => date('Y-m-d H:i:s'),
];

$providers[] = $newProvider;

if (!write_json_file('providers.json', $providers)) {
    respond(['ok' => false, 'error' => 'server']);
}

respond(['ok' => true]);
