<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManager;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{

    // Récupérer les genres sans détail
    #[Route('/api-genre/simple', name: 'app_api_genre_simple', methods: ['GET'])]
    public function list(GenreRepository $genreRepository, SerializerInterface $serializer)
    {
        $genres = $genreRepository->findAll();
        $req = $serializer->serialize(
            $genres,
            'json',
            [
                'groups' => 'genre:simple'
            ]
        );

        return new JsonResponse($req, 200, [], true);
    }


    #[Route('/api-genre/full', name: 'app_api_genre', methods: ['GET'])]
    public function index(GenreRepository $genreRepository, SerializerInterface $serializer)
    {
        $genres = $genreRepository->findAll();
        $req = $serializer->serialize(
            $genres,
            'json',
            [
                // on précise le groupe de sérialisation à utiliser pour la réponse JSON . Ne pas oublier de mettre le use en haut du fichier pour utiliser le groupe de sérialisation dans l'annotation liée à l'entité Genre
                'groups' => 'genre:full'
                // on précise que l'on veut récupérer les données de la relation books

            ]
        );

        return new JsonResponse($req, 200, [], true);
    }

    // Afficher un genre par son id
    #[Route('/api-genre/{id}', name: 'app_api_genre_show', methods: ['GET'])]
    public function show(GenreRepository $genreRepository, SerializerInterface $serializer, $id)
    {
        $genre = $genreRepository->find($id);
        $req = $serializer->serialize(
            $genre,
            'json',
            [
                'groups' => 'genre:show'
            ]

        );

        //Response::HTTP_OK est une constante qui a pour valeur 200

        return new JsonResponse($req, Response::HTTP_OK, [], true);
    }

    //Créer un genre
    #[Route('/api-genre/add', name: 'add', methods: ['POST'])]
    public function create(SerializerInterface $serializer,Request $request,EntityManagerInterface $entityManagerInterface)
    {
        // Récupérer le contenu de la requête
        $data = $request->getContent();
        

        //Créer un genre  à partir des données JSON reçues dans la requête POST que l'on va désérialiser en objet Genre grâce à la méthode deserialize() du composant SerializerInterface
        $genre = $serializer->deserialize($data, Genre::class, 'json');

        // Enregistrer le genre en base de données
        $entityManagerInterface->persist($genre);
        $entityManagerInterface->flush();

        // On retourne une réponse en JSON avec le content type application/json
 /*        return new JsonResponse(
            'Le genre a bien été créé',
            Response::HTTP_CREATED,
            [
                'location' => "/api-genre/" . $genre->getId()
            ],
            true
        ); */

        return new JsonResponse(
            'Le genre a bien été créé', // message optionnel sinon on peut mettre null
            Response::HTTP_CREATED,
            [
                // on génère l'URL de la ressource créée grâce à la méthode generateUrl() du composant RouterInterface
                'location' => $this->generateUrl(
                    'app_api_genre_show',
                    [
                        'id' => $genre->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL // on précise que l'on veut une URL absolue et non relative (par défaut)
                )
            ],
            true
        );
        

    }



    // Modifier un genre
    #[Route('/api-genre/{id}', name: 'app_api_genre_update', methods: ['PUT'])]
    public function update(Genre $genre,SerializerInterface $serializerInterface, Request $request,EntityManagerInterface $entirtyManagerInterface)
    {
        $data = $request->getContent();

        //object_to_populate permet de dire au composant Serializer que l'on veut mettre à jour un objet existant et non en créer un nouveau (par défaut, si on ne précise pas cette option, le composant Serializer va créer un nouvel objet)
        $req = $serializerInterface->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);

        $entirtyManagerInterface->persist($req);
        $entirtyManagerInterface->flush();

        return new JsonResponse(
            'Le genre a bien été modifié',
            Response::HTTP_OK,
            [],
            true
        );

    }

    // Supprimer un genre
    #[Route('/api-genre/{id}', name: 'app_api_genre_delete', methods: ['DELETE'])]
    public function delete(GenreRepository $genreRepository,EntityManagerInterface $entityManagerInterface,$id)
    {
        $genre = $genreRepository->find($id);

        if(!$genre){
            return new JsonResponse(
                "Le genre n'existe pas",
                Response::HTTP_NOT_FOUND,
                []
            );
        }

        return new JsonResponse(
            'Le genre a bien été supprimé',
            Response::HTTP_OK,
            []
        );
    }

}
