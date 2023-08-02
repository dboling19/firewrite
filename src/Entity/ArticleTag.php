<?php

namespace App\Entity;

use App\Repository\ArticleTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;


#[ORM\Entity(repositoryClass: ArticleTagRepository::class)]
class ArticleTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: "Article", inversedBy: "tags")]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $article;

    #[ORM\ManyToOne(targetEntity: "Tag", inversedBy: "articles")]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $tag;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?Datetime $datetime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
    }
}
