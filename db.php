<?php

class Database
{
    public function connect(): mysqli
    {
        $this->loadEnv();

        $host = getenv('DB_HOST') ?: '127.0.0.1'; $port = (int) (getenv('DB_PORT') ?: 3306);
        $name = getenv('DB_NAME') ?: 'goal_tracker_db'; $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = new mysqli($host, $user, $pass, $name, $port);
        $conn->set_charset('utf8mb4');
        return $conn;
    }

    private function loadEnv(): void
    {
        $file = __DIR__ . '/.env';
        if (!file_exists($file)) return;

        foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
            if (trim($line) === '' || strpos(trim($line), '#') === 0) continue;
            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            putenv(trim($key) . '=' . trim($value));
        }
    }
}
