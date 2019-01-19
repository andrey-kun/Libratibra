<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 19.01.19
 * Time: 20:47
 */

namespace App\Model;


use PDO;

abstract class Content extends \Core\Model
{
    public $id;
    public $name;

    private $isRemoved = false;

    protected function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM ' . static::getTableName());
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static abstract function getTableName();

    public static function getById($id)
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * FROM " . static::getTableName() . " WHERE (id=:id)");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $foundAuthor = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        if ($foundAuthor === null || empty($foundAuthor)) {
            return null;
        }

        return new static($id, $foundAuthor['name']);
    }

    public static function insert($name)
    {
        $db = static::getDB();

        if (static::isExists($name)) {
            throw new ReiterationException();
        }

        $stmt = $db->prepare("INSERT INTO " . static::getTableName() . " (name) VALUES (:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();

        return new static($db->lastInsertId(), $name);
    }

    public static function isExists($name)
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT name FROM " . static::getTableName() . " WHERE (name=:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $contentsWithSameName = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($contentsWithSameName);
    }

    public function flush()
    {
        $db = static::getDB();

        $stmt = $db->prepare("UPDATE " . static::getTableName() . " SET name=:name WHERE id=:id");
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();
    }

    public function remove()
    {
        if ($this->isRemoved) return;

        $db = static::getDB();

        $stmt = $db->prepare("DELETE FROM " . static::getTableName() . " WHERE id=:id");
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $this->isRemoved = true;
    }
}