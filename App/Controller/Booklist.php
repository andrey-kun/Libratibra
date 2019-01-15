<?php
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
use Core\View;

class Booklist extends \Core\Controller
{
    public function indexAction()
    {
        $books = Book::getAll();
        $authors = Author::getAll();
        $genres = Genre::getAll();

        $sortName = $_GET['sort'];

        if (substr_count($sortName, 'Descending') > 0) {
            $books = array_reverse($books);
        }

        View::renderTemplate('booklist.html', [
            'projectName' => Config::PROJECT_NAME,
            'books' => $books,
            'authors' => $authors,
            'genres' => $genres
        ]);
    }
}