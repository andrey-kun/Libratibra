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
        $data = $statement->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
        foreach ($data as $id => &$content) {
            $content['id'] = $id;
        }
        return $data;
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

        return new static($id, $found_content);
    }

    public static function insert($model_fields)
    {
        $database = static::getDB();

        if (static::isExists($model_fields['name'])) {
            throw new ReiterationException();
        }

        $bind = ':' . implode(',:', array_keys($model_fields));
        $statement = $database->prepare("INSERT INTO " . static::getTableName()
            . '(' . implode(',', array_keys($model_fields)) . ') '
            . 'values (' . $bind . ')');
        $statement->execute(array_combine(explode(',', $bind), array_values($model_fields)));

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

    public function update($model_fields)
    {
        $database = static::getDB();

        $bind = ':' . implode(',:', array_keys($model_fields));
        $statement = $database->prepare("UPDATE INTO " . static::getTableName()
            . " SET " . $this->pdoSet($model_fields)
            . "WHERE id=:id");
        $statement->bindParam(":id", $this->id);
        $statement->execute($model_fields);
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

    private function pdoSet($allowed, $values = 0, $source = array())
    {
        $set = '';
        $values = array();
        if (!$source) $source = &$_POST;
        foreach ($allowed as $field) {
            if (isset($source[$field])) {
                $set .= "`" . str_replace("`", "``", $field) . "`" . "=:$field, ";
                $values[$field] = $source[$field];
            }
        }
        return substr($set, 0, -2);
    }
}