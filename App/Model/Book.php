<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 13.01.19
 * Time: 18:30
 */

namespace App\Model;

class Book extends Content
{
    public $author_id;
    public $genre_id;
    public $rating;

    protected static function getRowNames()
    {
        return ['id', 'name', 'rating', 'author_id', 'genre_id'];
    }

    protected static function getTableName()
    {
        return "books";
    }

    public static function insert($values): object
    {
        $book = parent::insert($values);

        $book_author = Author::getById($book->author_id);
        $book_author->update(null);

        return $book;
    }

    public function remove()
    {
        parent::remove();

        $book_author = Author::getById($this->author_id);
        $book_author->update(null);
    }

    public static function getByAuthor($author_id)
    {
        return parent::getByColumn('author_id', $author_id);
    }

    public static function getByGenre($genre_id)
    {
        return parent::getByColumn('genre_id', $genre_id);
    }

    public function update($values)
    {
        parent::update($values);

        $book_author = Author::getById($this->author_id);
        $book_author->update(null);
    }
}