<?php

namespace App\Controller;

use App\Entity\Foto;
use App\Entity\Post;
use App\Entity\Album;
use App\Entity\User;
use App\Form\CreatePostFormType;
use App\Form\FormHandler;
use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine,)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/post', name: 'app_post_create', methods: ['POST'])]
    public function createPost(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $post = new Post();
        $form = $this->createForm(CreatePostFormType::class, $post);
        $formHandler = new FormHandler($form);
        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }
        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        if ($data['isFoto'] == "false") {

            $foto = $this->getFotoById($data['id']);
            if (!$foto) {
                return $this->json([
                    'error' => ['Erreur: Foto non trouvée.'],
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            $post->setFoto($foto);
        } else {
            $album = $this->getAlbumById($data['id']);
            if (!$album) {
                return $this->json([
                    'error' => ['Erreur: Album non trouvée.'],
                ], JsonResponse::HTTP_NOT_FOUND);
            }
        }
        $post->setCreationDate(new \DateTime());
        $post->setOwner($user);

        $this->em->persist($post);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Post créé avec succès.'
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/posts', name: 'app_post_get', methods: ['GET'])]
    public function getFotos(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $posts = $this->em->getRepository(Post::class)->findAll();

        //order the post by the inversed datetime (new post at the start of the array)
        usort($posts, function ($a, $b) {
            return $b->getCreationDate() <=> $a->getCreationDate();
        });
        $posts = array_map(function ($post) {
            return $post->getAll();
        }, $posts);


        return $this->json([
            'jwtToken' => $newJWT,
            'posts' => $posts
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
