<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
#[UniqueEntity('title', message: 'Ce titre existe déjà')]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'title', type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Ne laisse pas ce champs vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le titre saisi {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères',
        )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    #[Assert\NotBlank(message: 'Ne laisse pas ce champs vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le synopsis saisi {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères',
        )]
    private ?string $synopsis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le lien de l\'image saisi {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères',
        )]

    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le pays saisi {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères',
        )]

    #[ORM\Column(nullable: true)]
    private ?int $year = null;

    #[ORM\OneToMany(mappedBy: 'program', targetEntity: Season::class, orphanRemoval: true)]
    private Collection $seasons;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
    }

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

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->setProgram($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getProgram() === $this) {
                $season->setProgram(null);
            }
        }

        return $this;
    }
}
