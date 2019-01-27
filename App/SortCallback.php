<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 27.01.19
 * Time: 10:13
 */

namespace App;


class SortCallback
{
    public static function getFuncSortByField($field)
    {
        return function ($a, $b) use ($field) {
            // TODO: Заменить "ЯЯЯ" на последний символ последнего алфавита в Юникоде
            $name_a = isset($a[$field]) ? $a[$field] : 'ЯЯЯ';
            $name_b = isset($b[$field]) ? $b[$field] : 'ЯЯЯ';
            return $name_a <=> $name_b;
        };
    }

    public static function getFuncSortByFieldInArray($array, $id_name, $field)
    {
        return function ($a, $b) use ($array, $id_name, $field) {
            // TODO: Заменить "ЯЯЯ" на последний символ последнего алфавита в Юникоде
            $name_a = isset($array[$a[$id_name]][$field]) ? $array[$a[$id_name]][$field] : 'ЯЯЯ';
            $name_b = isset($array[$b[$id_name]][$field]) ? $array[$b[$id_name]][$field] : 'ЯЯЯ';
            return $name_a <=> $name_b;
        };
    }
}