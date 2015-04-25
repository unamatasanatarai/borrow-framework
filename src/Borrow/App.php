<?php
namespace Borrow;


class App{
    protected $_dispatcher;

    public function __construct()
    {
        define('STORAGE', ROOT . 'storage/');
        define('VIEWS', ROOT . 'app/resources/views/');
        define('PUBLIC', ROOT . 'public/');

        \Dotenv::load(ROOT);
        \Log::start(STORAGE . 'logs/');

        $this->_dispatcher = new Dispatcher();

    }

    public function start()
    {
        $this->_dispatcher->dispatch();
    }
}
