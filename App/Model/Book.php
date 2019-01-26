<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 13.01.19
 * Time: 18:30
 */

namespace App\Model;

class Book extends Content
{
    public $author_id;
    public $genre_id;
    public $rating;

    protected static function getTableName()
    {
        return "books";
    }

    public static function getByGenre($genre_id)
    {
        return parent::getByColumn('genre_id', $genre_id);
    }

    public function update($model_fields)
    {
        $database = static::getDB();

        $model_fields['id'] = $this->id;
        foreach ($model_fields as $field => $value) {
            $this->$field = $value;
        }

        $statement = $database->prepare("UPDATE " . static::getTableName()
            . " SET name=:name, rating=:rating, author_id=:author_id, genre_id=:genre_id"
            . " WHERE id=:id");
        $statement->execute($model_fields);
    }
}