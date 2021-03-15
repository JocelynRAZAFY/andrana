<?php


namespace App\Manager;


use App\Entity\Article;
use App\Object\AllArticle;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleManager extends BaseManager
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    private $normalizer;

    /**
     * ArticleManager constructor.
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     * @param SessionInterface $session
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     * @param ArticleRepository $articleRepository
     * @param AuthorRepository $authorRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        RequestStack $requestStack,
        SessionInterface $session,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        ArticleRepository $articleRepository,
        AuthorRepository $authorRepository,
        NormalizerInterface $normalizer)
    {
        $this->articleRepository = $articleRepository;
        $this->authorRepository = $authorRepository;
        $this->normalizer = $normalizer;
        parent::__construct($em, $container, $requestStack, $session, $logger, $serializer);
    }

    /**
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function allArticle()
    {
        $maxPagination = (int)$this->getParameter('max_pagination');
        if($this->data->page == 1){
            $page = 0;
        }else {
            $page = ($this->data->page - 1) * $maxPagination ;
        }

        $articles = $this->articleRepository->paginationArticle($page,$maxPagination);
        $authors = $this->normalizer->normalize($this->authorRepository->findAll(),null,['groups' => ['list_author']]);

        $result = [
            'total' => count($this->articleRepository->findAll()),
            'maxPagination' => $maxPagination,
            'articles' => $articles,
            'authors' => $authors
        ];
        return $this->success($result);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateArticle()
    {
        $article = $this->articleRepository->find($this->data->id);
        $author = $this->authorRepository->find($this->data->author->id);
        $action = 'edit';
        if(!$article){
            $article = new Article();
            $action = 'add';
        }
        $article->setTitle($this->data->title);
        $article->setAuthor($author);
        $article->setDescription($this->data->description);
        $this->save($article);

        $result = [
            'action' => $action,
            'article' => $this->normalizer->normalize($article,null,['groups' => ['list_article']])
        ];
        return $this->success($result);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeArticle()
    {
        $article = $this->articleRepository->find($this->data->id);
        $this->remove($article);

        $result = [
            'id' => $this->data->id
        ];
        return $this->success($result);
    }
}
