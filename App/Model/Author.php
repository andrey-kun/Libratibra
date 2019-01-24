<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 17:06
 */

namespace App\Model;

class Author extends Content
{
    protected static function getTableName()
    {
        return "authors";
    }

    public function update($model_fields)
    {
        $database = static::getDB();

        $model_fields['id'] = $this->id;
        foreach ($model_fields as $field => $value) {
            $this->$field = $value;
        }

        $statement = $database->prepare("UPDATE " . static::getTableName()
            . " SET name=:name"
            . " WHERE id=:id");
        $statement->execute($model_fields);
    }
}