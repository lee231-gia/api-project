<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/goals.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if ($path === '' || $path === '/index.php') {
    echo json_encode(['message' => 'Goal Tracker API is running']);
    exit;
}

if ($path === '/api/goals' || $path === '/goals.php') {
    (new GoalApi((new Database())->connect()))->handle();
    exit;
}

http_response_code(404);
echo json_encode(['message' => 'Endpoint not found']);
