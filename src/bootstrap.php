<?php

declare(strict_types=1);

// Проверка версии PHP
use App\ApplicationError;
use App\FrontController\Dispatcher;
use App\HttpError404Exception;

if (PHP_VERSION_ID < 80401) {
    print "PHP 8.4 Required";
    exit(1);
}

// Запись логов
error_reporting(E_ALL);
ini_set('log_errors', "on");
ini_set('error_log', __DIR__ . "/var/log/php/php-error.log");

// Установка локали
setlocale(LC_ALL, 'ru_RU.UTF-8');

// Константы
const APP_ROOT_DIR = __DIR__ . "/../";
const APP_AUTOLOAD_DIR = APP_ROOT_DIR . 'vendor/autoload.php';
const APP_SRC_DIR = APP_ROOT_DIR . 'src/';
const APP_LOCAL_CONFIG_DIR = APP_SRC_DIR . 'config.local.php';
const APP_GLOBAL_CONFIG_DIR = APP_SRC_DIR . 'config.global.php';

define("APP_USE_SSL", (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) OR (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'));
define("APP_SERVER_PORT", $_SERVER['SERVER_PORT'] ?? 80);

// Загрузка Composer's autoloader
if (!file_exists(APP_AUTOLOAD_DIR) || !is_readable(APP_AUTOLOAD_DIR)) {
    print "Composer autoloader not found";
    exit(1);
}

require_once APP_AUTOLOAD_DIR;

if (file_exists(APP_GLOBAL_CONFIG_DIR)) {
    // Если существует конфиг основной - подключаем (для production)
    if (!is_readable(APP_GLOBAL_CONFIG_DIR)) {
        print sprintf("Error: %s. not readable", APP_GLOBAL_CONFIG_DIR);
        exit(1);
    } else {
        require_once APP_GLOBAL_CONFIG_DIR;
    }
} else {
    // Нет основного конфига - подключаем
    if (!file_exists(APP_LOCAL_CONFIG_DIR) || !is_readable(APP_LOCAL_CONFIG_DIR)) {
        print sprintf("Error: %s not found or not readable", APP_LOCAL_CONFIG_DIR);
        exit(1);
    }
    require_once APP_LOCAL_CONFIG_DIR;
}

try {

    session_start();
    if (session_status() !== PHP_SESSION_ACTIVE) {
        throw new ApplicationError("Session start failed");
    }

    $dispatcher = new Dispatcher(parse_url(($_SERVER['REQUEST_URI'] ?? "/"), PHP_URL_PATH));

    if ($dispatcher->routeViaFiles()) {
        // Успешная маршрутизация через файлы, render-им
        $dispatcher->controller_entity->layout->render($dispatcher->controller_entity->view);
    } else {
        // Маршрутизация через файлы не удалась, выводим 404
        throw new HttpError404Exception("Page not found");
    }

} catch (Error | ApplicationError $e) {
    if (!headers_sent()) {
        header("HTTP/1.1 500 Internal Server Error", true, 500);
    }
    error_log(sprintf("%s: %s\nTrace: %s", $e::class, $e->getMessage(), $e->getTraceAsString()), E_USER_ERROR);
    print $e->getMessage();
    print $e->getTraceAsString();
    exit(1);

} catch (HttpError404Exception $e) {
    if (!headers_sent()) {
        header("HTTP/1.1 404 Not Found", true, 404);
    }
    print $e->getMessage();
    exit(1);
} catch (\App\HttpError401Exception $e) {
    if (!headers_sent()) {
        header("HTTP/1.1 401 Unauthorized", true, 401);
    }
    print $e->getMessage();
    exit(1);
}