<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 17:06
 */

namespace App\Model;

use App\SortCallback;

class Author extends Content
{
    public $rating;
    public $number_books;

    public static function arraySort(&$models, $sort_name, $callback_func = null)
    {
        if ($callback_func !== null) {
            parent::arraySort($models, $sort_name, $callback_func);
            return;
        }

        switch (static::getTypeNameSorted($sort_name)) {
            default:
            case "author_name":
                $callback_func = SortCallback::getFuncSortByField("name");
                break;
            case "author_rating":
                $callback_func = SortCallback::getFuncSortByField("rating");
                break;
            case "author_number_books":
                $callback_func = SortCallback::getFuncSortByField("number_books");
                break;
        }

        parent::arraySort($models, $sort_name, $callback_func);
    }

    protected static function getRowNames()
    {
        return ['id', 'name', 'rating', 'number_books'];
    }

    protected static function getTableName()
    {
        return "authors";
    }

    public function update(?array $values)
    {
        $database = static::getDB();
        $statement = $database->prepare("SELECT COUNT(name) FROM books WHERE author_id=?");
        $statement->execute([$this->id]);
        $values['number_books'] = $statement->fetchColumn();

        $statement = $database->prepare("SELECT AVG(rating) FROM books WHERE author_id=?");
        $statement->execute([$this->id]);
        $values['rating'] = $statement->fetchColumn();

        parent::update($values);
    }
}