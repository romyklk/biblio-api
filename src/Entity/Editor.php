<?php

namespace App\Entity;

use App\Repository\EditorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['name'], message: 'Un éditeur avec ce nom existe déjà.Veuillez en choisir un autre.')]
#[ORM\Entity(repositoryClass: EditorRepository::class)]
class Editor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['editor:simple', 'editor:full'])]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide')]
    #[Assert\Length(min: 3, max: 80, minMessage: 'Le nom doit contenir au moins {{ limit }} caractères', maxMessage: 'Le nom doit contenir au plus {{ limit }} caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9àâäéèêëïîôöùûüç\'\- ]+$/', message: 'Le nom ne doit contenir que des lettres, des chiffres, des espaces, des apostrophes, des tirets')]
    #[ORM\Column(length: 255)]
    #[Groups(['editor:simple', 'editor:full', 'author:full'])]
    private ?string $name = null;
    
    #[Groups(['editor:full'])]
    #[ORM\OneToMany(mappedBy: 'editor', targetEntity: Book::class)]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $book->setEditor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getEditor() === $this) {
                $book->setEditor(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getTotalBooks(): int
    {
        return count($this->books);
    }
}
