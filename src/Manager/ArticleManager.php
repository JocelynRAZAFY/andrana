<?php


namespace App\Manager;


use App\Entity\Article;
use App\EventSubscriber\Events;
use App\Messages\TestMessage;
use App\Object\AllArticle;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleManager extends BaseManager
{

    private ArticleRepository $articleRepository;

    private AuthorRepository $authorRepository;

    private MessageBusInterface $bus;

    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        RequestStack $requestStack,
        Security $security,
        NormalizerInterface $normalizer,
        MessageBusInterface $bus)
    {
        $this->bus = $bus;
        parent::__construct($em, $container, $requestStack, $security, $normalizer);
    }


    public function allArticle(): JsonResponse
    {
        $maxPagination = (int)$this->getParameter('max_pagination');
        if($this->data->page == 1){
            $page = 0;
        }else {
            $page = ($this->data->page - 1) * $maxPagination ;
        }

        $articles = $this->articleRepository->paginationArticle($page,$maxPagination);
        $authors = $this->normalize($this->authorRepository->findAll(),null,['groups' => ['list_author']]);

        $result = [
            'total' => count($this->articleRepository->findAll()),
            'maxPagination' => $maxPagination,
            'articles' => $articles,
            'authors' => $authors
        ];
        return $this->success($result);
    }

    public function updateArticle(): JsonResponse
    {
        $article = $this->articleRepository->find($this->data->id);
        $author = $this->authorRepository->find($this->data->author->id);
        $action = 'edit';
        if(!$article){
            $article = new Article();
            $action = 'add';
            $article->setAuthor($author);
        }
        $article->setTitle($this->data->title);
        $article->setDescription($this->data->description);
        $this->save($article);

        $result = [
            'action' => $action,
            'article' => $this->normalize($article,['groups' => ['list_article']])
        ];
        return $this->success($result);
    }

    public function removeArticle(): JsonResponse
    {
        $article = $this->articleRepository->find($this->data->id);
        $this->remove($article);

        $result = [
            'id' => $this->data->id
        ];
        return $this->success($result);
    }

    public function testArticle(): JsonResponse
    {
        $faker = Factory::create();
        $words = $faker->words($nb = 10, $asText = false);
        $result = [];
        foreach ($words as $word){
            $testMessage = new TestMessage($word);
            $this->bus->dispatch($testMessage);
            $result[] = $word;
        }

        return $this->success($result);
    }
}
