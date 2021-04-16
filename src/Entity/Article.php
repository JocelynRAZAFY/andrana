<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"show_article","list_article","list_author","show_author"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"show_article","list_article","list_author","show_author"})
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"show_article","list_author","show_author"})
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="article")
     * @Groups({"show_article","list_article"})
     */
    private Author $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
