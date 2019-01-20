<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 16.01.19
 * Time: 21:46
 */

namespace App\Controller;

use App\Config;

class Book extends Content
{

    public static function getModel()
    {
        return \App\Model\Book::class;
    }

    public static function getActionUrl()
    {
        return "/book";
    }

    public static function getTemplateLinks()
    {
        $templateNames = [
            'addFields' => 'content/book/AddFields.html',
            'editFields' => 'content/book/EditFields.html',
        ];
        return $templateNames;
    }

    public function addAction()
    {
        $authors = \App\Model\Author::getAll();
        $genres = \App\Model\Genre::getAll();
        $message = null;

        if (empty($authors) && empty($genres)) {
            $message = $this->getMessage('listAuthorsGenresEmpty');
        } elseif (empty($authors)) {
            $message = $this->getMessage('listAuthorsEmpty');
        } elseif (empty($genres)) {
            $message = $this->getMessage('listGenresEmpty');
        }

        if ($message === null) {
            parent::addAction();
        } else {
            $templateParams = [
                'message' => $message,
                'projectName' => Config::PROJECT_NAME,
                'title' => static::getMessage('addTitle'),
            ];
            $this->renderTemplate('content/Add.html', $templateParams);
        }
    }

    public function getMessage($id, ...$args)
    {
        $messages = [
            'addSuccess' => "Книга «%s» успешно добавлена.",
            'addTitle' => "Добавление книги…",
            'alreadyExists' => "Книга «%s» уже существует!",
            'delSuccess' => "Книга «%s» удалёна",
            'delTitle' => "Удаление книги",
            'delCaption' => "Удалить книгу «%s»?",
            'editTitle' => "Редактирование книги…",
            'editCaption' => "Редактирование книги «%s»…",
            'listAuthorsEmpty' => 'Список авторов пуст!',
            'listAuthorsGenresEmpty' => 'Списки авторов и жанров пусты!',
            'listGenresEmpty' => 'Список жанров пуст!',
            'matchingNames' => "Вы дали книге «%s» такое же имя!",
            'missingId' => "Книги с ID %d не существует, свяжитесь с администратором.",
            'renameSuccess' => "Книга «%s» переименована в «%s»",
        ];
        return vsprintf($messages[$id], $args);
    }

    public function renderTemplate($path, $params)
    {
        $bookParams = [
            'authors' => \App\Model\Author::getAll(),
            'genres' => \App\Model\Genre::getAll(),
        ];
        $updParams = array_merge($bookParams, $params);
        parent::renderTemplate($path, $updParams);
    }
}