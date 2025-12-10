<?php

class Logger {
    private static $logDir;

    public static function init() {
        self::$logDir = __DIR__ . '/../logs/';
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }

    public static function log($message, $level = 'INFO') {
        self::init();
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $logFile = self::$logDir . "app_{$date}.log";
        
        $logMessage = "[{$time}] [{$level}] {$message}" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }

    public static function info($message) {
        self::log($message, 'INFO');
    }

    public static function warning($message) {
        self::log($message, 'WARNING');
    }
}
