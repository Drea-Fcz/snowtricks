<?php

namespace App\Entity;

use App\Repository\TrickMediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickMediaRepository::class)]
class TrickMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $url = null;

    #[ORM\Column]
    private ?bool $isImage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $embed = null;

    #[ORM\ManyToOne(inversedBy: 'trickMedia')]
    private ?Trick $trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function isIsImage(): ?bool
    {
        return $this->isImage;
    }

    public function setIsImage(bool $isImage): self
    {
        $this->isImage = $isImage;

        return $this;
    }

    public function getEmbed(): ?string
    {
        return $this->embed;
    }

    public function setEmbed(?string $embed): self
    {
        $this->embed = $embed;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
