<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
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
    // Récupérer les éditeurs sans détail
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

    // Récupérer les éditeurs avec détail
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

    // Afficher un éditeur par son id
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

    // Ajouter un éditeur
    #[Route('/api/author/add', name: 'app_api_author_add', methods: ['POST'])]
    public function create(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        // On désérialise les données c'est à dire qu'on les transforme en objet Author
        $author = $serializer->deserialize($data, Author::class, 'json');

        $errors = $validator->validate($author);

        if (count($errors) > 0) {

            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManagerInterface->persist($author);
        $entityManagerInterface->flush();

        return $this->json(
            $author,
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
    public function update(AuthorRepository $authorRepository, SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validator, $id)
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
        $serializer->deserialize($data, Author::class, 'json', ['object_to_populate' => $author]);

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
