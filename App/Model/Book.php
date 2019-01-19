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
    protected static function getTableName()
    {
        return "books";
    }
}