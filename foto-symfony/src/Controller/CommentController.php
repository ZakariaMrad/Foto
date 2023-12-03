<?php

namespace App\Controller;

use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CreateCommentFormType;
use App\Form\FormHandler;

class CommentController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/comments', name: 'app_comment_create', methods: ['POST'])]
    public function createComment(Request $request, SluggerInterface $slugger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);

        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $postId = $data['post']['idPost'];
        unset($data['post']);
        unset($data['user']);
        unset($data['idComment']);

        $comment = new Comment();
        $form = $this->createForm(CreateCommentFormType::class, $comment);
        $formHandler = new FormHandler($form);
        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $post = $this->getPostById($postId);

        $user->addComment($comment);

        $post->addComment($comment);

        $this->em->persist($comment);
        $this->em->flush();


        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Commentaire créé avec succès',
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
