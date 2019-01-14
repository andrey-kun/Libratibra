<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 12.01.19
 * Time: 19:38
 */

namespace App\Controller;

use App\Config;
use App\Model\Book;
use Core\View;

class Index extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('index.html', [
            'projectName' => Config::PROJECT_NAME,
            'books' => Book::getAll()
        ]);
    }
}