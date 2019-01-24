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
        $books = Book::getAll();
        $authors = Author::getAll();
        $genres = Genre::getAll();

        if (isset($_GET['sort'])) {
            $sortName = $_GET['sort'];
        } else {
            $sortName = null;
        }

        if ($sortName !== null) {
            if (substr_count($sortName, 'Descending') > 0) {
                $books = array_reverse($books);
            }
        }

        View::renderTemplate('Index.html', [
            'projectName' => Config::PROJECT_NAME,
            'books' => $books,
            'authors' => $authors,
            'genres' => $genres
        ]);
    }
}