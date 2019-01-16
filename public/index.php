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

$router->add('', ['controller' => 'Booklist', 'action' => 'booklist']);
$router->add('{controller}/{action}');

try {
    $router->dispatch(@$_SERVER['QUERY_STRING']);
} catch (Exception $e) {
    die($e->getMessage());
}