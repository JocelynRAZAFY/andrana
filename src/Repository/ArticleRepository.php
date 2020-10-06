<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    private $authorRepository;

    public function __construct(
        ManagerRegistry $registry,
        AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
        parent::__construct($registry, Article::class);
    }

    public function transform(Article $article)
    {
        return [
          'id' => $article->getId(),
          'title' => $article->getTitle(),
          'description' => $article->getDescription(),
          'author' => $this->authorRepository->transform($article->getAuthor()),
        ];
    }

    /**
     * @param $items
     * @return array
     */
    public function transformAll($items)
    {
        $result = [];
        foreach ($items as $item){
            $result[] = $this->transform($item);
        }
        return $result;
    }

    public function paginationArticle(int $page, int $max)
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->setFirstResult($page)
            ->setMaxResults($max);

        $articles = $qb ->getQuery()->getResult();
        $result = [];
        foreach($articles as $article){
            $result[] = $this->transform($article);
        }

        return $result;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
