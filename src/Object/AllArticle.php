<?php


namespace App\Object;


use App\Entity\Article;
use App\Entity\Author;

class AllArticle
{
    public $total;

    public $max;

    public $articles;

    public $authors;

    public function __construct(int $total, int $max,  $articles,  $authors)
    {;
        $this->total = $total;
        $this->max = $max;
        $this->articles = $articles;
        $this->authors = $authors;
    }
}
