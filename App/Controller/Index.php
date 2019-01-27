<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: andrey-kun
 * Date: 12.01.19
 * Time: 19:38
 */

namespace App\Controller;

use App\Config;
use App\Model\Author;
use App\Model\Book;
use App\Model\Genre;
use Core\Controller;
use Core\View;

class Index extends Controller
{
    public function indexAction()
    {
        $search_query = (isset($_GET['search_query']) && $_GET['search_query'] !== "") ? $_GET['search_query'] : null;

        if ($search_query !== null) {
            $books = Book::getByContent($search_query);
            $authors = Author::getByContent($search_query);
            $genres = Genre::getByContent($search_query);
        } else {
            $books = Book::getAll();
            $authors = Author::getAll();
            $genres = Genre::getAll();
        }

        $sorting_authors = (isset($_GET['sorting_authors']))
            ? $_GET['sorting_authors']
            : "author_name_ascending";
        $sorting_books = (isset($_GET['sorting_books']))
            ? $_GET['sorting_books']
            : "book_name_ascending";
        $sorting_genres = (isset($_GET['sorting_genres']))
            ? $_GET['sorting_genres']
            : "genre_name_ascending";

        Author::arraySort($authors, $sorting_authors);
        Book::arraySort($books, $sorting_books, null, $authors, $genres);
        Genre::arraySort($genres, $sorting_genres);

        View::renderTemplate('Index.html', [
            'projectName' => Config::PROJECT_NAME,
            'books' => $books,
            'authors' => $authors,
            'genres' => $genres,
            'search_query' => $search_query,
            'sort_params' => [
                'sorting_authors' => $sorting_authors,
                'sorting_books' => $sorting_books,
                'sorting_genres' => $sorting_genres,
            ],
        ]);
    }
}