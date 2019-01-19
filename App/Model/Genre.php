<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 19:25
 */

namespace App\Model;

use PDO;

class Genre extends \Core\Model
{
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM genres ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}