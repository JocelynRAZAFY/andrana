<?php


namespace App\Manager;


use App\Entity\Article;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorManager extends BaseManager
{

    private AuthorRepository $authorRepository;

    private SerializerInterface $serializer;
    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        RequestStack $request,
        Security $security,
        NormalizerInterface $normalizer,
        AuthorRepository $authorRepository,
        SerializerInterface $serializer)
    {
        $this->authorRepository = $authorRepository;
        $this->serializer = $serializer;
        parent::__construct($em, $container, $request, $security, $normalizer);
    }


    public function allAuthor(): JsonResponse
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

    public function updateAuthor(): JsonResponse
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
            'author' => $this->normalize($author,['groups' => ['list_author']])
        ];
        return $this->success($result);
    }

    public function removeAuthor(): JsonResponse
    {
        $author = $this->authorRepository->find($this->data->id);
        $this->remove($author);

        $result = [
            'id' => $this->data->id
        ];
        return $this->success($result);
    }

    public function createAuthor()
    {
//        dd($this->dataJson);
//        $author = $this->serializer->deserialize($this->dataJson,Author::class,'json');

        $author = new Author();
        $author->setName($this->data->name);
        $author->setArticle($this->data->articles);
        $this->save($author);

        return $this->success(['author' => $this->normalize($author,['groups' => ['list_author']])]);
    }
}
