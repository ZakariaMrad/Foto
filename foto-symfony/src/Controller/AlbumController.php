<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Foto;
use App\Entity\User;
use App\Form\FormHandler;
use App\Form\CreateAlbumFormType;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Jwt\JWTHandler;

class AlbumController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/album', name: 'app_create_album', methods: ['POST'])]
    public function index(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $fotos = $data['fotos'];
        unset($data['fotos']);
        $collaboraters = $data['collaboraters'];
        unset($data['collaboraters']);
        $spectators = $data['spectators'];
        unset($data['spectators']);
        $grid = $data['grid'] ?? null;
        unset($data['grid']);
        unset($data['isPublic']);

        $album = new Album();
        $form = $this->createForm(CreateAlbumFormType::class, $album);
        $formHandler = new FormHandler($form);
        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }
        $album->setCreationDate(new \DateTime());
        $album->setOwner($this->getUserById($this->jwtHandler->decodedJWTToken['idUser']));
        foreach ($fotos as $foto) {
            $album->addFoto($this->getFotoById($foto['idFoto']));
        }
        foreach ($collaboraters as $collaborater) {
            $album->addCollaborator($this->getUserById($collaborater['idAccount']));
        }
        foreach ($spectators as $spectator) {
            $album->addSpectator($this->getUserById($spectator['idAccount']));
        }
        $album->setGrid($grid);
        $album->setIsPublic(true);
        
        $this->em->persist($album);
        $this->em->flush();
        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Album créé avec succès.'
        ], JsonResponse::HTTP_OK);
    }
    #[Route('/albums', name: 'app_album_get_all', methods: ['GET'])]
    public function getAlbums(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $albums = $user->getOwnedAlbums();
        $albumArray = array_map(function (Album $album) {
            return $album->getAll();
        }, $albums->toArray());
        return $this->json([
            'jwtToken' => $newJWT,
            'albums' => $albumArray,
        ], JsonResponse::HTTP_OK);
    }
    #[Route('/album/{idAlbum}', name: 'app_album_delete', methods: ['DELETE'])]
    public function deleteComplaint($idAlbum, Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $album = $this->em->getRepository(Album::class)->findOneBy(['idAlbum' => $idAlbum]);
        if (!$album) {
            return $this->json([
                'error' => ['Erreur: L\'album n\'existe pas.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->em->remove($album);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Album supprimé avec succès.'
        ], JsonResponse::HTTP_OK);
    }
    private function getUserById(int $idUser): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['idUser' => $idUser]);
    }
    private function getFotoById(int $idFoto): ?Foto
    {
        return $this->em->getRepository(Foto::class)->findOneBy(['idFoto' => $idFoto]);
    }
    private function getAlbumById(int $idAlbum): ?Album
    {
        return $this->em->getRepository(Album::class)->findOneBy(['idAlbum' => $idAlbum]);
    }
}
