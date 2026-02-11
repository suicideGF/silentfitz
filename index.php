<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/src/helpers.php';
require_once __DIR__ . '/src/endpoints/datadome.php';
require_once __DIR__ . '/src/endpoints/prelogin.php';
require_once __DIR__ . '/src/endpoints/login.php';
require_once __DIR__ . '/src/endpoints/codm.php';
require_once __DIR__ . '/src/endpoints/account.php';
require_once __DIR__ . '/src/endpoints/fullcheck.php';
require_once __DIR__ . '/src/endpoints/hash.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($request_uri === '/' || $request_uri === '') {
    json_response([
        'status' => 'online',
        'version' => '1.0.0',
        'endpoints' => [
            'POST /api/datadome' => 'Get DataDome cookie',
            'POST /api/prelogin' => 'Prelogin (get v1, v2)',
            'POST /api/login' => 'Login (get sso_key)',
            'POST /api/hash' => 'Hash password with v1, v2',
            'POST /api/codm/token' => 'Get CODM access token',
            'POST /api/codm/callback' => 'Process CODM callback',
            'POST /api/codm/userinfo' => 'Get CODM user info',
            'POST /api/account/init' => 'Get account details',
            'POST /api/check' => 'Full account check (all-in-one)'
        ]
    ]);
}

if ($request_uri !== '/' && $request_uri !== '') {
    verify_api_key();
}

if ($method !== 'POST' && $request_uri !== '/' && $request_uri !== '') {
    json_response(['error' => 'Method not allowed. Use POST.'], 405);
}

switch ($request_uri) {
    case '/api/datadome':
        handle_datadome();
        break;

    case '/api/prelogin':
        handle_prelogin();
        break;

    case '/api/login':
        handle_login();
        break;

    case '/api/hash':
        handle_hash_password();
        break;

    case '/api/codm/token':
        handle_codm_token();
        break;

    case '/api/codm/callback':
        handle_codm_callback();
        break;

    case '/api/codm/userinfo':
        handle_codm_userinfo();
        break;

    case '/api/account/init':
        handle_account_init();
        break;

    case '/api/check':
        handle_full_check();
        break;

    default:
        json_response(['error' => 'Endpoint not found'], 404);
        break;
}
