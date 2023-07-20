<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Author;
use App\Entity\Editor;
use DateTimeImmutable;
use App\Entity\Nationality;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BiblioFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création d'un tableau de nationalités
        $tabNationality = [];

        // Ajout des nationalités dans la base de données
        for ($i = 0; $i < 20; $i++) {
            $nationality = new Nationality();
            $nationality->setLibelle($faker->country());
            $manager->persist($nationality);
            $tabNationality[] = $nationality;
        }

        // Création d'un tableau d'auteurs
        $authors = [];

        // Ajout des auteurs dans la base de données
        for ($i = 0; $i < 100; $i++) {
            $author = new Author();
            $author->setFirstname($faker->firstName());
            $author->setLastname($faker->lastName());
            $author->setNationality($faker->randomElement($tabNationality));
            $manager->persist($author);
            $authors[] = $author;
        }

        // Création d'un tableau de genres
        $bookGenres = [
            'Science-fiction',
            'Fantasy',
            'Mystère',
            'Thriller',
            'Horreur',
            'Romance',
            'Drame',
            'Aventure',
            'Action',
            'Historique',
            'Biographie',
            'Autobiographie',
            'Classique',
            'Conte',
            'Essai',
            'Poésie',
            'Humour',
            'Jeunesse',
            'Science',
            'Technologie',
            'Art',
            'Cuisine',
            'Voyage',
            'Psychologie',
            'Philosophie',
            'Religion',
            'Santé',
            'Développement personnel',
            'Économie',
            'Politique',
            'Écologie',
            'Éducation',
            'Sport',
            'Mode',
            'Musique',
            'Cinéma',
            'Théâtre',
            'Photographie',
            'Architecture',
            'Graphisme',
            'Informatique',
            'Science-fiction dystopique',
            'Fantasy épique',
            'Mystère policier',
            'Thriller psychologique',
            'Horreur surnaturelle',
            'Romance contemporaine',
            'Drame familial',
            'Aventure fantastique',
            'Action militaire',
            'Roman historique',
            'Biographie inspirante',
            'Classique intemporel',
            'Conte pour enfants',
            'Essai philosophique',
            'Poésie romantique',
            'Humour satirique',
            'Livre jeunesse illustré',
            'Science et découverte',
            'Technologie de pointe',
            'Art moderne',
            'Cuisine du monde',
            'Récit de voyage',
            'Psychologie du bonheur',
            'Philosophie de vie',
            'Religion et spiritualité',
            'Santé et bien-être',
            'Développement personnel',
            'Économie mondiale',
            'Politique internationale',
            'Écologie et environnement',
            'Éducation et apprentissage',
            'Sport et performance',
            'Mode et style',
            'Histoire de la musique',
            'Cinéma et grands réalisateurs',
            'Théâtre classique',
            'Photographie artistique',
            'Architecture moderne',
            'Graphisme créatif',
            'Informatique pratique'
        ];

        // Ajout des genres dans la base de données
        foreach ($bookGenres as $genre) {
            $bookGenre = new Genre();
            $bookGenre->setLibelle($genre);
            $manager->persist($bookGenre);
        }

        // Création d'un tableau des éditeurs
        $publishers = [
            "Editions Flammarion",
            "Gallimard",
            "Editions Albin Michel",
            "Editions Grasset",
            "Editions Hachette",
            "Editions Le Seuil",
            "Editions Robert Laffont",
            "Editions Fayard",
            "Editions Stock",
            "Editions Julliard",
            "Editions Actes Sud",
            "Editions Pocket",
            "Editions XO",
            "Editions Plon",
            "Editions Calmann-Lévy",
            "Editions Le Livre de Poche",
            "Editions Denoël",
            "Editions L'École des loisirs",
            "Editions Nathan",
            "Editions Dupuis",
            "Editions Dargaud",
            "Editions Le Lombard",
            "Editions Casterman",
            "Editions Glénat",
            "Editions Delcourt",
            "Editions Soleil",
            "Editions Rue de Sèvres",
            "Editions Le Cherche Midi",
            "Editions First",
            "Editions Michel Lafon",
            "Editions Les Arènes",
            "Editions La Martinière",
            "Editions Le Passage",
            "Editions La Découverte",
            "Editions Odile Jacob",
            "Editions Points",
            "Editions Rivages",
            "Editions Stock",
            "Editions Actes Sud",
            "Editions L'Olivier",
            "Editions Buchet Chastel",
            "Editions Zulma",
            "Editions Christian Bourgois",
            "Editions Verdier",
            "Editions P.O.L",
            "Editions Minuit",
            "Editions Seuil Jeunesse",
            "Editions Gallimard Jeunesse",
            "Editions Bayard Jeunesse",
            "Editions Pocket Jeunesse",
            "Editions Milan",
            "Editions Albin Michel Jeunesse",
            "Editions Nathan Jeunesse",
            "Editions Hatier",
            "Editions Larousse",
            "Editions Bordas",
            "Editions Dunod",
            "Editions Eyrolles",
            "Editions Le Robert",
            "Editions Larousse",
            "Editions Larousse",
            "Editions Flammarion",
            "Editions Ouest-France",
            "Editions Eyrolles",
            "Editions Albin Michel",
            "Editions Solar",
            "Editions La Martinière",
            "Editions du Chêne",
            "Editions Gallimard",
            "Editions Grasset",
            "Editions Julliard",
            "Editions Le Seuil",
            "Editions Stock",
            "Editions Flammarion",
            "Editions Albin Michel",
            "Editions Grasset",
            "Editions Hachette",
            "Editions Le Seuil",
            "Editions Robert Laffont",
            "Editions Fayard",
            "Editions Stock",
            "Editions Julliard",
            "Editions Actes Sud",
            "Editions Pocket",
            "Editions XO",
            "Editions Plon",
            "Editions Calmann-Lévy",
            "Editions Le Livre de Poche",
            "Editions Denoël",
            "Editions L'École des loisirs",
            "Editions Nathan",
            "Editions Dupuis",
            "Editions Dargaud",
            "Editions Le Lombard",
            "Editions Casterman",
            "Editions Glénat",
            "Editions Delcourt",
            "Editions Soleil",
            "Editions Rue de Sèvres",
            "Editions Le Cherche Midi",
            "Editions First",
            "Editions Michel Lafon",
            "Editions Les Arènes",
            "Editions La Martinière",
            "Editions Le Passage",
            "Editions La Découverte",
            "Editions Odile Jacob",
            "Editions Points",
            "Editions Rivages",
            "Editions Stock",
            "Editions Actes Sud",
            "Editions L'Olivier",
            "Editions Buchet Chastel",
            "Editions Zulma",
            "Editions Christian Bourgois",
            "Editions Verdier",
            "Editions P.O.L",
            "Editions Minuit",
        ];

        // Ajout des éditeurs dans la base de données
        foreach ($publishers as $publisher) {
            $editor = new Editor();
            $editor->setName($publisher);
            $manager->persist($editor);
        }

        // Enregistrement des nationalités, auteurs, genres et éditeurs dans la base de données
        $manager->flush();

        // Création des livres
        $authors = $manager->getRepository(Author::class)->findAll();
        $genres = $manager->getRepository(Genre::class)->findAll();
        $publishers = $manager->getRepository(Editor::class)->findAll();

        // Tableau de langues pour les livres
        $tabLanguage = [
            'Anglais',
            'Français',
            'Espagnol',
            'Allemand',
            'Italien',
            'Portugais',
            'Chinois',
            'Japonais',
            'Russe'
        ];

        // Création des livres
        for ($i = 0; $i < 500; $i++) {

            $startDate = strtotime('2000-01-01');
            $endDate = strtotime('2022-12-31');

            $randomTimestamp = random_int($startDate, $endDate);

            $randomDateTime = new DateTimeImmutable("@$randomTimestamp");

            $book = new Book();

            $author = $faker->randomElement($authors);
            $genre = $faker->randomElement($genres);
            $publisher = $faker->randomElement($publishers); // Sélection aléatoire d'un éditeur

            $book->setTitle($faker->sentence(rand(3, 5)))
                ->setAuthor($author)
                ->setEditor($publisher)
                ->setGenre($genre)
                ->setResume($faker->paragraph(rand(1, 2)))
                ->setPrice($faker->randomFloat(2, 5, 100))
                ->setIsbn($faker->isbn13())
                ->setCreatedAt($randomDateTime)
                ->setPublicationDate($faker->year())
                ->setLanguage($faker->randomElement($tabLanguage));

            // On ajoute le livre à l'auteur et à l'éditeur
            $author->addBook($book);
            $publisher->addBook($book);
            $genre->addBook($book);

            $manager->persist($book);
        }

        $manager->flush();
    }
}
