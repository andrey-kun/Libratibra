<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 19.01.19
 * Time: 20:47
 */

declare(strict_types=1);

namespace App\Model;


use App\ReiterationException;
use Core\Model;
use PDO;

abstract class Content extends Model
{
    public $id;
    public $name;

    private $isRemoved = false;

    protected function __construct($id, array $model_fields)
    {
        $this->id = $id;
        foreach ($model_fields as $field => $value) {
            $this->$field = $value;
        }
    }

    public static function getAll()
    {
        $database = static::getDB();
        $statement = $database->query('SELECT * FROM ' . static::getTableName());
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static abstract function getTableName();

    public static function getById($id)
    {
        $database = static::getDB();

        $statement = $database->prepare("SELECT * FROM " . static::getTableName() . " WHERE (id=:id)");
        $statement->bindParam(":id", $id);
        $statement->execute();
        $found_content = @$statement->fetchAll(PDO::FETCH_ASSOC)[0];

        if ($found_content === null || empty($found_content)) {
            return null;
        }

        return new static($id, $found_content['name']);
    }

    public static function insert($model_fields)
    {
        $database = static::getDB();

        if (static::isExists($model_fields['name'])) {
            throw new ReiterationException();
        }

        $statement = $database->prepare("INSERT INTO " . static::getTableName() . " (name) VALUES (:name)");
        $statement->execute($model_fields);

        return new static($database->lastInsertId(), $model_fields);
    }

    public static function isExists(string $name)
    {
        $database = static::getDB();

        $statement = $database->prepare("SELECT name FROM " . static::getTableName() . " WHERE (name=:name)");
        $statement->bindParam(":name", $name);
        $statement->execute();
        $array_contents = $statement->fetchAll(PDO::FETCH_ASSOC);

        return !empty($array_contents);
    }

    public function flush()
    {
        $database = static::getDB();

        $statement = $database->prepare("UPDATE " . static::getTableName() . " SET name=:name WHERE id=:id");
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":name", $this->name);
        $statement->execute();
    }

    public function remove()
    {
        if ($this->isRemoved) return;

        $database = static::getDB();

        $statement = $database->prepare("DELETE FROM " . static::getTableName() . " WHERE id=:id");
        $statement->bindParam(":id", $this->id);
        $statement->execute();

        $this->isRemoved = true;
    }
}