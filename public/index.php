<?php

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    $di = new FactoryDefault();

    include APP_PATH . '/config/router.php';

    include APP_PATH . '/config/services.php';

    $config = $di->getConfig();

    $di->set(
        'db',
        function(){
            return new DbAdapter([
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'dbname' => 'JobsNow',  
            ]);
        }
    );

    include APP_PATH . '/config/loader.php';

    $application = new \Phalcon\Mvc\Application($di);

    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
