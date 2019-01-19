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
    public $id;
    public $name;

    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM authors');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insert($name)
    {
        $db = static::getDB();

        if (self::isExists($name)) {
            throw new ReiterationException();
        }

        $stmt = $db->prepare("INSERT INTO authors (name) VALUES (:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();

        return new self($db->lastInsertId(), $name);
    }

    public static function getById($id)
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * FROM authors WHERE (id=:id)");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $foundAuthor = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        if ($foundAuthor === null || empty($foundAuthor)) {
            return null;
        }

        return new self($id, $foundAuthor['name']);
    }

    public function flush()
    {
        $db = static::getDB();

        $stmt = $db->prepare("UPDATE authors SET name=:name WHERE id=:id");
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();
    }

    public static function isExists($name)
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * FROM authors WHERE (name=:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $authorsWithSameName = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($authorsWithSameName);
    }

    public function remove()
    {
        $db = static::getDB();

        $stmt = $db->prepare("DELETE FROM authors WHERE id=:id");
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $this->id = $this->name = null;
    }

    private function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}