<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 16.01.19
 * Time: 21:46
 */

namespace App\Controller;


use App\Config;
use App\ReiterationException;
use Core\View;

class Author extends \Core\Controller
{
    public function addAction()
    {
        $name = @$_GET['name'];
        $author = null;
        $errorMessage = null;

        if ($name !== null && $name !== "") {
            try {
                $author = \App\Model\Author::create($name);
            } catch (ReiterationException $e) {
                $errorMessage = "Книга «${name}» уже существует!";
            }
        }

        View::renderTemplate('author/Add.html', [
            'projectName' => Config::PROJECT_NAME,
            'addedAuthor' => $author,
            'errorMessage' => $errorMessage
        ]);
    }
}