<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 19:25
 */

namespace App\Model;

use App\SortCallback;

class Genre extends Content
{
    public static function arraySort(&$models, $sort_name, $callback_func = null)
    {
        if ($callback_func !== null) {
            parent::arraySort($models, $sort_name, $callback_func);
            return;
        }

        switch (static::getTypeNameSorted($sort_name)) {
            default:
            case "genre_name":
                $callback_func = SortCallback::getFuncSortByField("name");
                break;
        }

        parent::arraySort($models, $sort_name, $callback_func);
    }

    protected static function getRowNames()
    {
        return ['id', 'name'];
    }

    protected static function getTableName()
    {
        return "genres";
    }
}