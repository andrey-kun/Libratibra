<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 13.01.19
 * Time: 18:30
 */

namespace App\Model;

use PDO;

class Book extends \Core\Model
{
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT name, rating FROM books ORDER BY rating');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}