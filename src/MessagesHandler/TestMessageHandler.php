<?php


namespace App\MessagesHandler;


use App\Entity\Article;
use App\Messages\TestMessage;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TestMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;

    private AuthorRepository $authorRepository;

    private ArticleRepository $articleRepository;

    public function __construct(EntityManagerInterface $em,
                                AuthorRepository $authorRepository,
                                ArticleRepository $articleRepository)
    {
        $this->em = $em;
        $this->authorRepository = $authorRepository;
        $this->articleRepository = $articleRepository;
    }

    public function __invoke(TestMessage $testMessage)
    {
        $word = $testMessage->getWord();

        $author = $this->authorRepository->find(2);
        $article = $this->articleRepository->findOneBy(['author' => $author, 'title' => $word]);
        if(!$article){
            $article = new Article();
            $article->setAuthor($author);
            $article->setTitle($word);
            $article->setDescription($word);
            $this->em->persist($article);
            $this->em->flush();
        }
    }
}
