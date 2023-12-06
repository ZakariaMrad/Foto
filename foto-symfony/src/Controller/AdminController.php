<?php

namespace App\Controller;

use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class AdminController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine,)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/admin', name: 'app_admin', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $user = $this->getUserByEmail($this->jwtHandler->decodedJWTToken['email']);
        if (!$user->isIsAdmin()) {
            return $this->json([
                'error' => 'You are not an admin',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        return $this->json([
            'adminJWT'=> $this->jwtHandler->createAdminToken($user),
            'jwtToken' => $newJWT,
        ]);
    }

    private function getUserByEmail(string $email)
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }
}
