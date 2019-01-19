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
                $author = \App\Model\Author::insert($name);
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

    public function editAction()
    {
        $id = $this->route_params['id'];
        $newName = @$_GET['newName'];
        $author = null;
        $message = null;

        $editableAuthor = \App\Model\Author::getById($id);

        if ($editableAuthor === null) {
            $message = "Автора с ID $id не существует, свяжитесь с администратором.";
        }

        if ($id !== null && $newName !== null) {
            if (\App\Model\Author::isExists($newName)) {
                $message = "Уже существует автор «${newName}»!";
            } elseif ($editableAuthor->name === $newName) {
                $message = "Вы дали автору «${newName}» такое же имя, которое он несёт.";
            } else {
                $message = "Автор переименован с «" . $editableAuthor->name . "» на «${newName}»";
                $editableAuthor->name = $newName;
                $editableAuthor->flush();
            }
        }

        View::renderTemplate('author/Edit.html', [
            'projectName' => Config::PROJECT_NAME,
            'editableAuthor' => $editableAuthor,
            'message' => $message
        ]);
    }
}