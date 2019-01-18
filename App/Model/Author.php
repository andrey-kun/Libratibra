<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 17:06
 */

namespace App\Model;

use App\ReiterationException;
use PDO;

class Author extends \Core\Model
{
    public $name;

    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM authors');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($name)
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * FROM authors WHERE (name=:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $authorsWithSameName = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($authorsWithSameName)) {
            throw new ReiterationException();
        }

        $stmt = $db->prepare("INSERT INTO authors (name) values (:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();

        return new self($name);
    }

    private function __construct($name)
    {
        $this->name = $name;
    }
}