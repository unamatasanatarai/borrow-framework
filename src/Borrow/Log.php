<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    static $log;

    static public function start($path){
        self::$log = new Logger('borrow');
        if (CLI){
            self::$log->pushHandler(new StreamHandler($path . 'cli_' . date('Ymd') . '.log', Logger::DEBUG));
        }else{
            self::$log->pushHandler(new StreamHandler($path . 'debug_' . date('Ymd') . '.log', Logger::DEBUG));
        }
    }

    static public function info($msg, $vars = ''){
        if (!is_array($vars)){
            $vars = array($vars);
        }
        self::$log->addInfo($msg, $vars);
    }

    static public function error($msg, $vars = ''){
        if (!is_array($vars)){
            $vars = array($vars);
        }
        self::$log->addError($msg, $vars);
    }
}
