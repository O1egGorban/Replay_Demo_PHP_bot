<?php
require_once __DIR__ . '/vendor/autoload.php';

use Bot\Database\LogRepository;
use Bot\Database\DatabaseConnect;
use Bot\Telegram\TelegramConnect;
use Bot\Database\TgUserRepository;
use Bot\Router; 

$token = getenv('TELEGRAM_BOT_TOKEN');
$DB_HOST = getenv('DB_HOST');
$DB_NAME = getenv('DB_NAME');
$DB_USER = getenv('DB_USER');
$DB_PASS = getenv('DB_PASS');

$rawInput = file_get_contents('php://input');
$update = json_decode($rawInput, true);

if (!$update) {
    exit('Бот работает и готов принимать Webhook от Telegram!');
}


try {
    $telegram = new TelegramConnect($token, $update);
    $dbConnect = new DatabaseConnect($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
    
    $pdo = $dbConnect->connect();
    

    $logRepository = new LogRepository($pdo);
    $userRepository = new TgUserRepository($pdo);
    $router = new Router($telegram, $logRepository, $userRepository);
    $router->route();

} catch (\Throwable $e) { 
    $errorMsg = "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n";
    error_log($errorMsg);
}
?>
