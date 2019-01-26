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
    public $name;

    public static function insert($values): object
    {
        if (static::isNameExists($values['name'])) {
            throw new ReiterationException();
        }

        return parent::insert($values);
    }

    public static function isNameExists(string $name)
    {
        $database = static::getDB();

        $statement = $database->prepare("SELECT name FROM " . static::getTableName() . " WHERE (name=:name)");
        $statement->bindParam(":name", $name);
        $statement->execute();
        $array_contents = $statement->fetchAll(PDO::FETCH_ASSOC);

        return !empty($array_contents);
    }
}