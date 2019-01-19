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

class Genre extends \Core\Controller
{
    public function addAction()
    {
        $name = @$_GET['name'];
        $genre = null;
        $errorMessage = null;

        if ($name !== null && $name !== "") {
            try {
                $genre = \App\Model\Genre::insert($name);
            } catch (ReiterationException $e) {
                $errorMessage = "Жанр «${name}» уже существует!";
            }
        }

        View::renderTemplate('genre/Add.html', [
            'projectName' => Config::PROJECT_NAME,
            'addedGenre' => $genre,
            'errorMessage' => $errorMessage
        ]);
    }

    public function editAction()
    {
        $id = $this->route_params['id'];
        $newName = @$_GET['newName'];
        $message = null;

        $editableGenre = \App\Model\Genre::getById($id);

        if ($editableGenre === null) {
            $message = "Жанр с ID $id не существует, свяжитесь с администратором.";
        }

        if ($id !== null && $newName !== null) {
            if (\App\Model\Genre::isExists($newName)) {
                $message = "Уже существует жанр «${newName}»!";
            } elseif ($editableGenre->name === $newName) {
                $message = "Вы дали жанру «${newName}» такое же имя!";
            } else {
                $message = "Жанр переименован с «" . $editableGenre->name . "» на «${newName}»";
                $editableGenre->name = $newName;
                $editableGenre->flush();
            }
        }

        View::renderTemplate('genre/Edit.html', [
            'projectName' => Config::PROJECT_NAME,
            'editableGenre' => $editableGenre,
            'message' => $message
        ]);
    }

    public function delAction()
    {
        $id = $this->route_params['id'];
        $isAgree = isset($_GET['agree']);
        $message = null;

        $removableGenre = \App\Model\Genre::getById($id);

        if ($removableGenre === null) {
            $message = "Жанр с ID $id не существует, свяжитесь с администратором.";
        } elseif ($isAgree) {
            $message = "Жанр «" . $removableGenre->name . "» удалён";
            $removableGenre->remove();
        }

        View::renderTemplate('genre/Delete.html', [
            'projectName' => Config::PROJECT_NAME,
            'removableGenre' => $removableGenre,
            'message' => $message
        ]);
    }
}