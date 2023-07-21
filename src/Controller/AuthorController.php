<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Nationality;
use App\Repository\AuthorRepository;
use App\Repository\NationalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    // Récupérer les auteurs sans détail
    #[Route('/api/author/simple', name: 'app_api_author_simple', methods: ['GET'])]
    public function list(AuthorRepository $authorRepository, SerializerInterface $serializer)
    {
        $authors = $authorRepository->findAll();
        
        $req = $serializer->serialize(
            $authors,
            'json',
            [
                'groups' => 'author:simple'
            ]
        );

        return new JsonResponse($req, 200, [], true);
    }

    // Récupérer les auteurs avec détail
    #[Route('/api/author/full', name: 'app_api_author_full', methods: ['GET'])]
    public function fullList(AuthorRepository $authorRepository, SerializerInterface $serializer)
    {
        $authors = $authorRepository->findAll();
        $req = $serializer->serialize(
            $authors,
            'json',
            [
                'groups' => 'author:full'
            ]
        );

        return new JsonResponse($req, 200, [],
            true
        );
    }

    // Afficher un auteur par son id
    #[Route('/api/author/{id}', name: 'app_api_author_show', methods: ['GET'])]
    public function showById(AuthorRepository $authorRepository, SerializerInterface $serializer, $id)
    {
        $author = $authorRepository->find($id);
        if (!$author) {
            return new JsonResponse(
                    "L'auteur demandé n'existe pas",
                    Response::HTTP_NOT_FOUND,
                    [],
                    true
                );
        }

        $req = $serializer->serialize(
            $author,
            'json',
            [
                'groups' => 'author:simple'
            ]
        );

        return new JsonResponse($req, 200, [],
            true
        );
    }

    // Ajouter un author
    #[Route('/api/author/add', name: 'app_api_author_add', methods: ['POST'])]
    public function create(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validator, NationalityRepository $nationalityRepository)
    {
        $data = $request->getContent(); // On récupère les données de la requête sous forme de JSON brut

        $dataTab = $serializer->decode($data, 'json'); // On récupère les données de la requête sous forme de tableau associatif pour pouvoir accéder à la nationalité qui est un objet inclus dans l'objet author

        $author = new Author();

        $authorNationality = $nationalityRepository->find($dataTab['nationality']['id']); // On récupère la nationalité de l'auteur

        $serializer->deserialize($data, Author::class, 'json', ['object_to_populate' => $author]); // On décode le JSON pour le transformer en objet Author

        $author->setNationality($authorNationality); // On ajoute la nationalité à l'auteur

        $errors = $validator->validate($author); // On valide les données

        if (count($errors) > 0) {
            $errorsJson = $serializer->serialize($errors, 'json'); // On transforme les erreurs en JSON
            return $this->json($errorsJson, Response::HTTP_BAD_REQUEST); // On retourne les erreurs en JSON
        }

        $entityManagerInterface->persist($author); // On persiste les données
        $entityManagerInterface->flush(); // On envoie les données en BDD

        return $this->json(
            "L'auteur a bien été ajouté",
            Response::HTTP_CREATED,
            [
                'location' => $this->generateUrl(
                    'app_api_author_show',
                    [
                        'id' => $author->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );

    }

    // Modifier un éditeur
    #[Route('/api/author/{id}', name: 'app_api_author_update', methods: ['PUT'])]
    public function update(AuthorRepository $authorRepository, SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validator, NationalityRepository $nationalityRepository, $id)
    {
        $author = $authorRepository->find($id);
        if (!$author) {
            return new JsonResponse(
                    "L'auteur demandé n'existe pas",
                    Response::HTTP_NOT_FOUND,
                    [],
                    true
                );
        }

        $data = $request->getContent();

        $dataTab = $serializer->decode($data, 'json'); // On récupère les données de la requête sous forme de tableau associatif pour pouvoir accéder à la nationalité qui est un objet inclus dans l'objet author
        $authorNationality = $nationalityRepository->find($dataTab['nationality']['id']); // On récupère la nationalité de l'auteur
        $serializer->deserialize($data, Author::class, 'json', ['object_to_populate' => $author]);
        $author->setNationality($authorNationality); // On ajoute la nationalité à l'auteur

        $errors = $validator->validate($author);

        if (count($errors) > 0) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return $this->json($errorsJson, Response::HTTP_BAD_REQUEST);
        }

        $entityManagerInterface->persist($author);
        $entityManagerInterface->flush();

        return $this->json(
            "L'auteur a bien été modifié",
            Response::HTTP_OK,
            [
                'location' => $this->generateUrl(
                    'app_api_author_show',
                    [
                        'id' => $author->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );
    }

    // Supprimer un éditeur
    #[Route('/api/author/{id}', name: 'app_api_author_delete', methods: ['DELETE'])]
    public function delete(AuthorRepository $authorRepository, EntityManagerInterface $entityManagerInterface, $id)
    {
        $author = $authorRepository->find($id);
        if (!$author) {
            return new JsonResponse(
                    "L'auteur demandé n'existe pas",
                    Response::HTTP_NOT_FOUND,
                    [],
                    true
                );
        }

        $entityManagerInterface->remove($author);
        $entityManagerInterface->flush();

        return new JsonResponse(
            "L'auteur a bien été supprimé",
            Response::HTTP_OK,
            []
        );
    }

}
