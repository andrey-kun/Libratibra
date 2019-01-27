<?php
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 13.01.19
 * Time: 18:34
 */

namespace Core;

use App\Config;
use App\Util;
use PDO;

abstract class Model
{
    public $id;
    public $isRemoved = false;

    protected abstract static function getRowNames();

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

        $found_models[0]['id'] = $id;
        return new static($found_models[0]);
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
            $models[] = new static($model_param);
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

    public static function insert($values): object
    {
        $database = static::getDB();

        $bind = ':' . implode(',:', array_keys($values));
        $statement = $database->prepare("INSERT INTO " . static::getTableName()
            . '(' . implode(',', array_keys($values)) . ') '
            . 'values (' . $bind . ')');
        $statement->execute(array_combine(explode(',', $bind), array_values($values)));

        $values['id'] = $database->lastInsertId();
        return new static($values);
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

    public function update(?array $values)
    {
        $database = static::getDB();

        $values['id'] = $this->id;

        foreach (static::getRowNames() as $row_name) {
            if (isset($values[$row_name])) {
                // TODO: Replace variable variable, bad code style
                $this->$row_name = $values[$row_name];
            }
        }

        $statement = $database->prepare("UPDATE " . static::getTableName()
            . " SET " . self::pdoSet(static::getRowNames(), $values)
            . " WHERE id=:id");
        $statement->execute(Util::getValues($values, static::getRowNames()));
    }

    protected function __construct(array $model_fields)
    {
        foreach ($model_fields as $field => $value) {
            $this->$field = $value;
        }
    }

    private static function pdoSet($allowed, $source)
    {
        $set = '';
        foreach ($allowed as $field) {
            if (isset($source[$field])) {
                $set .= "`" . str_replace("`", "``", $field) . "`" . "=:$field, ";
            }
        }
        return substr($set, 0, -2);
    }

    protected static function getTypeNameSorted(string $sort_name)
    {
        return substr($sort_name, 0, strrpos($sort_name, '_'));
    }

    protected static function getSortDirection(string $sort_name)
    {
        return substr(strrchr($sort_name, "_"), 1);
    }

    public static function arraySort(&$models, $sort_name, $callback_func = null)
    {
        uasort($models, $callback_func);

        if (static::getSortDirection($sort_name) === "descending") {
            $models = array_reverse($models, true);
        }
    }
}