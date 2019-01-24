<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 16.01.19
 * Time: 21:46
 */

namespace App\Controller;

use App\Config;
use App\ReiterationException;
use App\Util;
use Core\Controller;
use Core\View;

class Book extends Controller
{
    private const ACTION_URL = "/book";

    public function addAction()
    {
        $content = $message = null;
        $values = Util::getValuesFromGet('name', 'rating', 'author_id', 'genre_id');

        $authors = \App\Model\Author::getAll();
        $genres = \App\Model\Genre::getAll();

        if (empty($authors) && empty($genres)) {
            $message = $this->getMessage('listAuthorsGenresEmpty');
        } elseif (empty($authors)) {
            $message = $this->getMessage('listAuthorsEmpty');
        } elseif (empty($genres)) {
            $message = $this->getMessage('listGenresEmpty');
        }

        if (!Util::isExistsEmptyValues($values)) {
            try {
                $content = \App\Model\Book::insert($values);
                $message = static::getMessage('addSuccess', $values['name']);
            } catch (ReiterationException $exception) {
                $message = static::getMessage('alreadyExists', $values['name']);
            }
        }

        $template_params = [
            'actionUrl' => self::ACTION_URL . '/add',
            'authors' => $authors,
            'caption' => static::getMessage('addTitle'),
            'content' => $content,
            'genres' => $genres,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => static::getMessage('addTitle'),
        ];

        View::renderTemplate('content/Add.html', $template_params);
    }

    public function getMessage($id, ...$args)
    {
        $messages = [
            'addSuccess' => "Книга «%s» успешно добавлена.",
            'addTitle' => "Добавление книги…",
            'alreadyExists' => "Книга «%s» уже существует!",
            'deleteSuccess' => "Книга «%s» удалёна",
            'deleteTitle' => "Удаление книги",
            'deleteCaption' => "Удалить книгу «%s»?",
            'editTitle' => "Редактирование книги…",
            'editCaption' => "Редактирование книги «%s»…",
            'editSuccess' => "Книга «%s» обновлена",
            'listAuthorsEmpty' => 'Список авторов пуст!',
            'listAuthorsGenresEmpty' => 'Списки авторов и жанров пусты!',
            'listGenresEmpty' => 'Список жанров пуст!',
            'missingId' => "Книги с ID %d не существует, свяжитесь с администратором.",
            'renameSuccess' => "Книга «%s» переименована в «%s»",
        ];
        return vsprintf($messages[$id], $args);
    }

    public static function getTemplateLinks()
    {
        $links = [
            'addFields' => 'content/book/AddFields.html',
            'editFields' => 'content/book/EditFields.html',
        ];
        return $links;
    }

    public function deleteAction()
    {
        if (isset($this->route_params['id'])) {
            $id = $this->route_params['id'];
        } else {
            $id = null;
        }

        $message = null;
        $is_agree = isset($_GET['agree']);

        $content = \App\Model\Book::getById($id);

        if ($content === null) {
            $message = static::getMessage('missingId', $id);
        } elseif ($is_agree) {
            $message = static::getMessage('deleteSuccess', $content->name);
            $content->remove();
        }


        $template_params = [
            'actionUrl' => self::ACTION_URL . '/delete/' . $id,
            'caption' => (isset($content)) ? static::getMessage('deleteCaption', $content->name) : null,
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => static::getMessage('deleteTitle'),
        ];

        View::renderTemplate('content/Delete.html', $template_params);
    }

    public function editAction()
    {
        if (isset($this->route_params['id'])) {
            $id = $this->route_params['id'];
        } else {
            $id = null;
        }
        $message = null;
        $values = Util::getValuesFromGet('name', 'rating', 'author_id', 'genre_id');

        $content = \App\Model\Book::getById($id);

        $authors = \App\Model\Author::getAll();
        $genres = \App\Model\Genre::getAll();

        if ($content === null) {
            $message = static::getMessage('missingId', $id);
        } elseif (!Util::isExistsEmptyValues($values)) {
            if (\App\Model\Book::isNameExists($values['name']) && $content->name !== $values['name']) {
                $message = static::getMessage('alreadyExists', $values['name']);
            } else {
                $message = static::getMessage('editSuccess', $content->name, $values['name']);
                $content->update($values);
            }
        }

        $template_params = [
            'actionUrl' => self::ACTION_URL . '/edit/' . $id,
            'authors' => $authors,
            'caption' => (isset($content)) ? static::getMessage('editCaption', $content->name) : null,
            'content' => $content,
            'genres' => $genres,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => static::getMessage('editTitle'),
        ];

        View::renderTemplate('content/Edit.html', $template_params);
    }


}