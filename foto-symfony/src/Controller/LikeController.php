<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Form\FormHandler;
use App\Form\LikeFormType;
use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/like', name: 'app_like_create', methods: ['POST'])]
    public function createLike(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);

        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        unset($data['idLike']);
        $like = new Like();
        $form = $this->createForm(LikeFormType::class, $like);
        $formHandler = new FormHandler($form);
        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $postId = $data['post']['idPost'];
        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $post = $this->getPostById($postId);
        if (!$post) {
            return $this->json([
                'error' => ['Erreur: Post non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }


        $user->addLike($like);

        $post->addLike($like);

        $this->em->persist($like);
        $this->em->flush();


        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Like créé avec succès',
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/like/{idLike}', name: 'app_like_delete', methods: ['DELETE'])]
    public function removeLike(Request $request ,$idLike): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);

        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $like = $this->em->getRepository(Like::class)->findOneBy(['idLike' => $idLike]);
        
        if (!$like) {
            return $this->json([
                'error' => ['Erreur: Like non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($like);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Like retiré avec succès',
        ], JsonResponse::HTTP_OK);
    }

    private function getUserById(int $idUser): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['idUser' => $idUser]);
    }

    private function getPostById(int $idPost): ?Post
    {
        return $this->em->getRepository(Post::class)->findOneBy(['idPost' => $idPost]);
    }
}
