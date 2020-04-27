<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

class RootController extends AbstractController
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    private $request;

    public function __construct(
        RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("/{vueRouting}", requirements={"vueRouting"="^(?!api|_(profiler|wdt)).*"}, name="vue_routing")
     * @return Response
     */
    public function index(): Response
    {
        $host = $this->request->getScheme().'://'.$this->request->getHost();

        return $this->render('base.html.twig',
            [
                'host' => $host
            ]);
    }
}
