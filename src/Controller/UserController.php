<?php

namespace App\Controller;

use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Route("/api/user/login", name="api_user_login", methods={"POST"})
     */
    public function login()
    {
        return $this->userManager->login();
    }

    /**
     * @Route("/api/user/logout", name="api_user_logout", methods={"POST"})
     */
    public function logout()
    {
        return $this->userManager->logout();
    }

    /**
     * Get user datas
     *
     * @Route("/api/back/user/info", name="user_info", methods={"GET"})
     */
    public function userInfo()
    {
        return $this->userManager->userInfo();
    }

    /**
     * @Route("/api/back/user/update", name="user_update", methods={"POST"})
     */
    public function userUpdate(PublisherInterface $publisher)
    {
        $update = new Update("https://andrana.com/books/1",json_encode(['status' => 'OutOfStock']));
        $publisher($update);

        return $this->userManager->userUpdate();
    }
}
