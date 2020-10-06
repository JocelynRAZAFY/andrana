<?php

namespace App\Controller;

use App\Manager\ArticleManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @var ArticleManager
     */
    private $articleManager;

    /**
     * ArticleController constructor.
     * @param ArticleManager $articleManager
     */
    public function __construct(ArticleManager $articleManager)
    {
        $this->articleManager = $articleManager;
    }

    /**
     * @Route("/api/article/all", name="all_article", methods={"POST"})
     */
    public function allArticle()
    {
        return $this->articleManager->allArticle();
    }

    /**
     * @Route("/api/article/update", name="update_article", methods={"POST"})
     */
    public function updateArticle()
    {
        return $this->articleManager->updateArticle();
    }

    /**
     * @Route("/api/article/remove", name="remove_article", methods={"POST"})
     */
    public function removeArticle()
    {
        return $this->articleManager->removeArticle();
    }

}
