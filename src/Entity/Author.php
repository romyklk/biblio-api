<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['firstname', 'lastname'], message: 'Un auteur avec ce nom et ce prénom existe déjà.Veuillez en choisir un autre.')]
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['author:simple', 'author:full'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prénom ne peut pas être vide')]
    #[Assert\Length(min: 3, max: 80, minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères', maxMessage: 'Le prénom doit contenir au plus {{ limit }} caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9àâäéèêëïîôöùûüç\'\- ]+$/', message: 'Le prénom ne doit contenir que des lettres, des chiffres, des espaces, des apostrophes, des tirets')]
    #[Groups(['genre:full', 'editor:full', 'author:simple', 'author:full'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide')]
    #[Assert\Length(min: 3, max: 100, minMessage: 'Le nom doit contenir au moins {{ limit }} caractères', maxMessage: 'Le nom doit contenir au plus {{ limit }} caractères')]
    #[Groups(['genre:full', 'editor:full', 'author:simple', 'author:full'])]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'authors')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'La nationalité ne peut pas être vide')]
    #[Groups(['genre:full', 'editor:full', 'author:full'])]
    private ?Nationality $nationality = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
    #[Groups(['author:full'])]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return strtoupper($this->lastname);
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(?Nationality $nationality): static
    {
        $this->nationality = $nationality;

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
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
