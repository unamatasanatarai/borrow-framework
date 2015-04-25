<?php
namespace Borrow;


class App{

    public function __construct()
    {
        define('STORAGE', ROOT . 'storage/');
        define('VIEWS', ROOT . 'app/resources/views/');
        define('PUBLIC', ROOT . 'public/');

        \Dotenv::load(ROOT);
        \Log::start(STORAGE . 'logs/');

        $this->errorReporting();
    }

    public function errorReporting()
    {
        $whoops = new \Whoops\Run;

        if (getenv('APP_DEBUG') === 'true') {
            ini_set('display_errors', 'On');
            if (CLI){
                $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler());
            }else{
                $handler = new \Whoops\Handler\JsonResponseHandler();
                $handler->onlyForAjaxRequests(true);
                $whoops->pushHandler($handler);
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
            }
        }
        else {
            ini_set('display_errors', 'Off');
            $whoops->pushHandler(function ($exception){
                http_response_code(500);
                exit('A website error has occurred.
                    Our geniuses have been notified of this issue. Come back soon, we will fix it, we promise.');
            });
        }

        $whoops->pushHandler(function($exception, $inspector, $run){
            \Log::error(\Whoops\Exception\Formatter::formatExceptionPlain(
                $inspector
            ));
            return \Whoops\Handler\Handler::DONE;
        });
        $whoops->register();
    }

    public function start()
    {
        Dispatcher::dispatch();
    }
}
