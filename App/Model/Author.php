<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 17:06
 */

namespace App\Model;

use PDO;

class Author extends \Core\Model
{
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM authors ORDER BY rating');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}