<?php

namespace App\Controller;

use App\Manager\AuthorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{

    private $authorManager;

    public function __construct(AuthorManager $authorManager)
    {
        $this->authorManager = $authorManager;
    }

    /**
     * @Route("/api/author/all", name="all_author", methods={"POST"})
     */
    public function allAuthor()
    {
        return $this->authorManager->allAuthor();
    }

    /**
     * @Route("/api/author/update", name="update_author", methods={"POST"})
     */
    public function updateAuthor()
    {
        return $this->authorManager->updateAuthor();
    }

    /**
     * @Route("/api/author/remove", name="remove_author", methods={"POST"})
     */
    public function removeAuthor()
    {
        return $this->authorManager->removeAuthor();
    }

}
