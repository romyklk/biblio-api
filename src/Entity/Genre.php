<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['libelle'], message: 'Un genre avec ce libellé existe déjà.Veuillez en choisir un autre.')]
#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['genre:simple','genre:read', 'genre:show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['genre:simple', 'genre:full', 'genre:show', 'editor:full'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50, minMessage: 'Le libellé doit contenir au moins {{ limit }} caractères', maxMessage: 'Le libellé doit contenir au plus {{ limit }} caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9àâäéèêëïîôöùûüç\'\- ]+$/', message: 'Le libellé ne doit contenir que des lettres, des chiffres, des espaces, des apostrophes, des tirets')]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: Book::class)]
    #[Groups(['genre:full'])]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setGenre($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getGenre() === $this) {
                $book->setGenre(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->libelle;
    }
}
