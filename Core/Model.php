<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 13.01.19
 * Time: 18:34
 */

namespace Core;

use App\Config;
use PDO;

abstract class Model
{
    public $id;
    public $isRemoved = false;

    protected abstract static function getTableName();

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

    public static function getById($id)
    {
        $database = static::getDB();

        $statement = $database->prepare("SELECT * FROM " . static::getTableName() . " WHERE (id=:id)");
        $statement->bindParam(":id", $id);
        $statement->execute();
        $found_models = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($found_models === null || empty($found_models)) {
            return null;
        }

        return new static($id, $found_models[0]);
    }

    public static function getByColumn($column_name, $value)
    {
        $database = static::getDB();

        $statement = $database->prepare("SELECT * FROM " . static::getTableName()
            . " WHERE ($column_name=:value)");
        $statement->bindParam(":value", $value);
        $statement->execute();
        $found_models = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($found_models === null || empty($found_models)) {
            return null;
        }

        $models = [];

        foreach ($found_models as $model_param) {
            $models[] = new static($model_param['id'], $model_param);
        }

        return $models;
    }

    protected static function getDB()
    {
        static $db = null;
        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $db;
    }

    public static function insert($values)
    {
        $database = static::getDB();

        $bind = ':' . implode(',:', array_keys($values));
        $statement = $database->prepare("INSERT INTO " . static::getTableName()
            . '(' . implode(',', array_keys($values)) . ') '
            . 'values (' . $bind . ')');
        $statement->execute(array_combine(explode(',', $bind), array_values($values)));

        $values['id'] = $database->lastInsertId();
        return $values;
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

    protected function __construct($id, array $model_fields)
    {
        $this->id = $id;
        foreach ($model_fields as $field => $value) {
            $this->$field = $value;
        }
    }
}