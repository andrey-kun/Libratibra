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

class Author extends Controller
{
    private const ACTION_URL = "/author";

    public function getMessage($id, ...$args)
    {
        $messages = [
            'addSuccess' => "Автор «%s» успешно добавлен.",
            'addTitle' => "Добавление автора…",
            'alreadyExists' => "Автор «%s» уже существует!",
            'deleteSuccess' => "Автор «%s» удалён",
            'deleteTitle' => "Удаление автора",
            'deleteCaption' => "Удалить автора «%s»?",
            'editTitle' => "Редактирование автора…",
            'editCaption' => "Редактирование автора «%s»…",
            'editSuccess' => "Автор «%s» обновлён",
            'listAuthorEmpty' => 'Список авторов пуст!',
            'missingId' => "Автора с ID %d не существует, свяжитесь с администратором.",
            'renameSuccess' => "Автор «%s» переименован в «%s»",
        ];
        return vsprintf($messages[$id], $args);
    }

    public static function getTemplateLinks()
    {
        return null;
    }

    public function addAction()
    {
        $content = $message = null;
        $values = Util::getValuesFromGet('name');

        if (!Util::isExistsEmptyValues($values)) {
            try {
                $content = \App\Model\Author::insert($values);
                $message = static::getMessage('addSuccess', $values['name']);
            } catch (ReiterationException $exception) {
                $message = static::getMessage('alreadyExists', $values['name']);
            }
        }

        $template_params = [
            'actionUrl' => self::ACTION_URL . '/add',
            'caption' => static::getMessage('addTitle'),
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => static::getMessage('addTitle'),
        ];

        View::renderTemplate('content/Add.html', $template_params);
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

        $content = \App\Model\Author::getById($id);

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
        $values = Util::getValuesFromGet('name');

        $content = \App\Model\Author::getById($id);

        if ($content === null) {
            $message = static::getMessage('missingId', $id);
        } elseif (!Util::isExistsEmptyValues($values)) {
            if (\App\Model\Genre::isNameExists($values['name'])) {
                $message = static::getMessage('alreadyExists', $values['name']);
            } else {
                $message = static::getMessage('editSuccess', $content->name, $values['name']);
                $content->update($values);
            }
        }

        $template_params = [
            'actionUrl' => self::ACTION_URL . '/edit/' . $id,
            'caption' => (isset($content)) ? static::getMessage('editCaption', $content->name) : null,
            'content' => $content,
            'message' => $message,
            'projectName' => Config::PROJECT_NAME,
            'templateLinks' => static::getTemplateLinks(),
            'title' => static::getMessage('editTitle'),
        ];

        View::renderTemplate('content/Edit.html', $template_params);
    }
}