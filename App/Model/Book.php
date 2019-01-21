<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 13.01.19
 * Time: 18:30
 */

namespace App\Model;

class Book extends Content
{
    public $authorId;
    public $genresId;

    public static function insert($model_fields)
    {
        return parent::insert($model_fields);
    }

    protected static function getTableName()
    {
        return "books";
    }
}