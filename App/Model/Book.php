<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 13.01.19
 * Time: 18:30
 */

namespace App\Model;

use App\SortCallback;

class Book extends Content
{
    public $author_id;
    public $genre_id;
    public $rating;

    public static function insert($values): object
    {
        $book = parent::insert($values);

        $book_author = Author::getById($book->author_id);

        if ($book_author !== null) $book_author->update(null);

        return $book;
    }

    public static function getByAuthor($author_id)
    {
        return parent::getByColumn('author_id', $author_id);
    }

    public static function getByGenre($genre_id)
    {
        return parent::getByColumn('genre_id', $genre_id);
    }

    public static function getByContent($content)
    {
        $books = [];

        foreach (Author::getByContent($content) as $author) {
            $books = array_merge($books, self::getByAuthor($author['id']));
        }

        foreach (Genre::getByContent($content) as $genre) {
            $books = array_merge($books, self::getByGenre($genre['id']));
        }

        $books = array_merge($books, parent::getByContent($content));

        return $books;
    }

    public static function arraySort(&$models, $sort_name, $callback_func = null, $authors = null, $genres = null)
    {
        if ($callback_func !== null) {
            parent::arraySort($models, $sort_name, $callback_func);
            return;
        }

        switch (static::getTypeNameSorted($sort_name)) {
            default:
            case "book_name":
                $callback_func = SortCallback::getFuncSortByField("name");
                break;
            case "book_rating":
                $callback_func = SortCallback::getFuncSortByField("rating");
                break;
            case "author_name":
                $callback_func = SortCallback::getFuncSortByFieldInArray($authors, "author_id", "name");
                break;
            case "author_rating":
                $callback_func = SortCallback::getFuncSortByFieldInArray($authors, "author_id", "rating");
                break;
            case "genre_name":
                $callback_func = SortCallback::getFuncSortByFieldInArray($genres, "genre_id", "name");
                break;
        }

        parent::arraySort($models, $sort_name, $callback_func);
    }

    protected static function getRowNames()
    {
        return ['id', 'name', 'rating', 'author_id', 'genre_id'];
    }

    protected static function getTableName()
    {
        return "books";
    }

    public function remove()
    {
        $book_author = Author::getById($this->author_id);

        parent::remove();

        if ($book_author !== null) $book_author->update(null);
    }

    public function update($values)
    {
        parent::update($values);

        $book_author = Author::getById($this->author_id);
        if ($book_author !== null) $book_author->update(null);
    }
}