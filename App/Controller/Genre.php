<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 16.01.19
 * Time: 21:46
 */

namespace App\Controller;

class Genre extends Content
{
    public static function getModel()
    {
        return \App\Model\Genre::class;
    }

    public function getMessage($id, ...$args)
    {
        $messages = [
            'addSuccess' => "Жанр «%s» успешно добавлен.",
            'addTitle' => "Добавление жанра…",
            'alreadyExists' => "Жанр «%s» уже существует!",
            'delSuccess' => "Жанр «%s» удалён",
            'delTitle' => "Удаление жанра",
            'delCaption' => "Удалить жанр «%s»?",
            'editTitle' => "Редактирование жанра…",
            'editCaption' => "Редактирование жанра «%s»…",
            'matchingNames' => "Вы дали жанру «%s» такое же имя!",
            'missingId' => "Жанр с ID %d не существует, свяжитесь с администратором.",
            'renameSuccess' => "Жанр «%s» переименован в «%s»",
        ];
        return vsprintf($messages[$id], $args);
    }

    public static function getActionUrl()
    {
        return "/genre";
    }
}