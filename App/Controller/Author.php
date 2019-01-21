<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 16.01.19
 * Time: 21:46
 */

namespace App\Controller;

class Author extends Content
{
    public static function getModel()
    {
        return \App\Model\Author::class;
    }

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
            'matchingNames' => "Вы дали автору «%s» такое же имя, которое он несёт.",
            'missingId' => "Автора с ID %d не существует, свяжитесь с администратором.",
            'renameSuccess' => "Автор «%s» переименован в «%s»",
        ];
        return vsprintf($messages[$id], $args);
    }

    public static function getActionUrl()
    {
        return "/author";
    }

    public static function getTemplateLinks()
    {
        return null;
    }
}