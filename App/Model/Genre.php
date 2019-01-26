<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 19:25
 */

namespace App\Model;

class Genre extends Content
{
    protected static function getRowNames()
    {
        return ['id', 'name'];
    }

    protected static function getTableName()
    {
        return "genres";
    }
}