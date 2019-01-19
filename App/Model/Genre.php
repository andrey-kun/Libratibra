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
    public $id;
    public $name;

    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM genres ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insert($name)
    {
        $db = static::getDB();

        if (self::isExists($name)) {
            throw new ReiterationException();
        }

        $stmt = $db->prepare("INSERT INTO genres (name) VALUES (:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();

        return new self($db->lastInsertId(), $name);
    }

    public static function getById($id)
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * FROM genres WHERE (id=:id)");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $foundGenre = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        if ($foundGenre === null || empty($foundGenre)) {
            return null;
        }

        return new self($id, $foundGenre['name']);
    }

    public function flush()
    {
        $db = static::getDB();

        $stmt = $db->prepare("UPDATE genres SET name=:name WHERE id=:id");
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();
    }

    public static function isExists($name)
    {
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * FROM genres WHERE (name=:name)");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $genresWithSameName = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($genresWithSameName);
    }

    public function remove()
    {
        $db = static::getDB();

        $stmt = $db->prepare("DELETE FROM genres WHERE id=:id");
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