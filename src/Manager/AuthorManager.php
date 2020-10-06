<?php


namespace App\Manager;


use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorManager extends BaseManager
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        RequestStack $requestStack,
        SessionInterface $session,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
        parent::__construct($em, $container, $requestStack, $session, $logger, $serializer);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allAuthor()
    {
        $maxPagination = (int)$this->getParameter('max_pagination');
        if($this->data->page == 1){
            $page = 0;
        }else {
            $page = ($this->data->page - 1) * $maxPagination ;
        }

        $result = [
            'total' => count($this->authorRepository->findAll()),
            'maxPagination' => $maxPagination,
            'authors' => $this->authorRepository->paginationAuthor($page,$maxPagination)
        ];
        return $this->success($result);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAuthor()
    {
        $author = $this->authorRepository->find($this->data->id);
        $action = 'edit';
        if(!$author){
            $author = new Author();
            $action = 'add';
        }
        $author->setName($this->data->name);
        $this->save($author);

        $result = [
            'action' => $action,
            'author' => $this->authorRepository->transform($author)
        ];
        return $this->success($result);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAuthor()
    {
        $author = $this->authorRepository->find($this->data->id);
        $this->remove($author);

        $result = [
            'id' => $this->data->id
        ];
        return $this->success($result);
    }
}