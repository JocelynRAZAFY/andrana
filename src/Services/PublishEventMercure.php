<?php


namespace App\Services;



use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class PublishEventMercure
{

    private $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function __invoke(PublisherInterface $publisher)
    {
        $update = new Update("https://varotra.com/books/1",json_encode(['status' => 'OutOfStock']));
        $publisher($update);
    }

    public function publish(PublisherInterface $publisher)
    {
        $update = new Update("https://example.com/books/1",json_encode(['status' => 'OutOfStock']));
        $publisher($update);
    }
}