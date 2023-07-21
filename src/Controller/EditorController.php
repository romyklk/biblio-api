<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditorController extends AbstractController
{
    // Récupérer les éditeurs sans détail
    #[Route('/api/editor/simple', name: 'app_api_editor_simple', methods: ['GET'])]
    public function list(EditorRepository $editorRepository, SerializerInterface $serializer)
    {
        $editors = $editorRepository->findAll();
        $req = $serializer->serialize(
            $editors,
            'json',
            [
                'groups' => 'editor:simple'
            ]
        );

        return new JsonResponse($req, 200, [], true);
    }

    // Récupérer les éditeurs avec détail
    #[Route('/api/editor/full', name: 'app_api_editor_simple', methods: ['GET'])]
    public function fullList(EditorRepository $editorRepository, SerializerInterface $serializer)
    {
        $editors = $editorRepository->findAll();
        $req = $serializer->serialize(
            $editors,
            'json',
            [
                'groups' => 'editor:full'
            ]
        );

        return new JsonResponse($req, 200, [], true);
    }

    // Afficher un éditeur par son id
    #[Route('/api/editor/{id}', name: 'app_api_editor_show', methods: ['GET'])]
    public function showById(EditorRepository $editorRepository, SerializerInterface $serializer, $id)
    {
        $editor = $editorRepository->find($id);
        if(!$editor){
            return new JsonResponse(
                "L'éditeur demandé n'existe pas",
                Response::HTTP_NOT_FOUND,
                [],
                true
            );
        }

        $req = $serializer->serialize(
            $editor,
            'json',
            [
                'groups' => 'editor:simple'
            ]
        );

        return new JsonResponse($req, 200, [], true);
    }

    // Ajouter un éditeur
    #[Route('/api/editor/add', name: 'app_api_editor_add', methods: ['POST'])]
    public function create(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        // On désérialise les données c'est à dire qu'on les transforme en objet Editor
        $editor = $serializer->deserialize($data, Editor::class, 'json');

        $errors = $validator->validate($editor);

        if(count($errors) > 0){

            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManagerInterface->persist($editor);
        $entityManagerInterface->flush();

        return $this->json(
            $editor,
            Response::HTTP_CREATED,
            [
                'location' => $this->generateUrl(
                    'app_api_editor_show',
                    [
                        'id' => $editor->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );
    }

    // Modifier un éditeur
    #[Route('/api/editor/{id}', name: 'app_api_editor_update', methods: ['PUT'])]
    public function update(EditorRepository $editorRepository, SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validator, $id)
    {
        $editor = $editorRepository->find($id);
        if(!$editor){
            return new JsonResponse(
                "L'éditeur demandé n'existe pas",
                Response::HTTP_NOT_FOUND,
                [],
                true
            );
        }

        $data = $request->getContent();
        $serializer->deserialize($data, Editor::class, 'json', ['object_to_populate' => $editor]);

        $errors = $validator->validate($editor);

        if(count($errors) > 0){
            $errorsJson = $serializer->serialize($errors, 'json');
            return $this->json($errorsJson, Response::HTTP_BAD_REQUEST);
        }

        $entityManagerInterface->persist($editor);
        $entityManagerInterface->flush();

        return $this->json(
            "L'éditeur a bien été modifié",
            Response::HTTP_OK,
            [
                'location' => $this->generateUrl(
                    'app_api_editor_show',
                    [
                        'id' => $editor->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );
    }

    // Supprimer un éditeur
    #[Route('/api/editor/{id}', name: 'app_api_editor_delete', methods: ['DELETE'])]
    public function delete(EditorRepository $editorRepository, EntityManagerInterface $entityManagerInterface, $id)
    {
        $editor = $editorRepository->find($id);
        if(!$editor){
            return new JsonResponse(
                "L'éditeur demandé n'existe pas",
                Response::HTTP_NOT_FOUND,
                [],
                true
            );
        }

        $entityManagerInterface->remove($editor);
        $entityManagerInterface->flush();

        return new JsonResponse(
            "L'éditeur a bien été supprimé",
            Response::HTTP_OK,
            []
        );
    }

}
