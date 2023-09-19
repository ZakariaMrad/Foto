<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FormHandler;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class AccountController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine,)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/account', name: 'app_account', methods: ['POST'])]
    public function account(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if ($this->jwtHandler->handle($data)) {
            return $this->json([
                'message' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->jwtHandler->decodedJWTToken['email']]);

        return $this->json([
            'decoded' => $this->jwtHandler->decodedJWTToken['email'],
            'user' => $user->getAll()

        ], JsonResponse::HTTP_OK);
    }

    #[Route('/account/login', name: 'app_account_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $form = $this->createForm(LoginFormType::class);
        $formHandler = new FormHandler($form);

        $data = json_decode($request->getContent(), true); // Decode JSON data from the request
        if ($formHandler->handle($data) == false) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $form->get('email')->getData()]);
        if (!$user) {
            return $this->json([
                'error' => ['Wrong email/password combination.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        if (!$passwordHasher->isPasswordValid($user, $form->get('password')->getData())) {
            return $this->json([
                'error' => ['Wrong email/password combination.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        return $this->json([
            "message" => "User logged in successfully.",
            "jwt_token" => $this->jwtHandler->create($user)
        ]);
    }

    #[Route('/account/register', name: 'app_account_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $formHandler = new FormHandler($form);

        $data = json_decode($request->getContent(), true); // Decode JSON data from the request
        
        if ($formHandler->handle($data) == false) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }


        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );
        $user->setCreationDate(new \DateTime());

        // Generate a JWT token
        $jwt_token = $this->jwtHandler->create($user);

        $this->em->persist($user); // Persist user to the database
        $this->em->flush(); // Save changes

        return $this->json([
            'message' => 'User registered successfully.',
            'jwt_token' => $jwt_token
        ]);
    }
}
