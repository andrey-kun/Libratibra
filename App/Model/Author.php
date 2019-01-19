<?php
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
}