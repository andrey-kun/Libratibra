<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 12.01.19
 * Time: 19:31
 */

require_once dirname(__DIR__) . "/vendor/autoload.php";

error_reporting(E_ALL);

$router = new Core\Router();

$router->add('/', ['controller' => 'Index', 'action' => 'index']);
$router->add('/{controller}/{action}');
$router->add('/author/{action}/{id:\d+}', ['controller' => 'Author', 'action' => '{action}']);
$router->add('/genre/{action}/{id:\d+}', ['controller' => 'Genre', 'action' => '{action}']);

try {
    $router->dispatch(@$_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    die($e->getMessage());
}