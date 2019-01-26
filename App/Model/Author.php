<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 15.01.19
 * Time: 17:06
 */

namespace App\Model;

class Author extends Content
{
    public $rating;
    public $number_books;

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