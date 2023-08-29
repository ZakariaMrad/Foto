<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
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
    private $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }

    #[Route('/account', name: 'app_account', methods: ['POST'])]
    public function index(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $em = $doctrine->getManager();

        $data = json_decode($request->getContent(), true);
        $token = $data['jwt_token'] ?? null;

        if (!$token) {
            return $this->json([
                'error' => 'JWT token not provided.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        try {
            $decodedJWTToken = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            // Handle token decode failure
            return $this->json([
                'error' => 'Invalid token',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$decodedJWTToken) {
            return $this->json([
                'error' => 'Invalid JWT token.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $username = $decodedJWTToken['username'] ?? 'Unknown User';

        return $this->json([
            'completeToken' => $decodedJWTToken,
            'user' => $username,
        ]);
    }

    #[Route('/account/login', name: 'app_account_login', methods: ['POST'])]
    public function login(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Get username and password from JSON data
        $username = $data['username'];
        $password = $data['password'];

        // Find the user by username (you might want to adjust this based on your User entity)
        $user = $em->getRepository(User::class)->findOneBy(['userName' => $username]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate a JWT token
        $jwt_token = $jwtManager->create($user);

        return $this->json([
            'username' => $user->getUsername(),
            'jwt_token' => $jwt_token
        ]);
    }

    #[Route('/account/register', name: 'app_account_register')]
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true); // Decode JSON data from the request
        $needCSRTToken = $request->query->get("need-csrf-token") == "true";

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Submit form with JSON data
        $form->submit($data);

        if ($needCSRTToken) {
            return $this->json([
                'csrf_token' => $form->createView()->children['_token']->vars['value']
            ], 200); // Return a JSON error response with a 400 status code
        }
        if (!$form->isSubmitted() || !$form->isValid()) {
            // Handle form validation errors and return error response
            $errors = [];
            foreach ($form->getErrors(true, true) as $error) {
                $fieldName = $error->getOrigin() ? $error->getOrigin()->getName() : '_global';
                $errors[$fieldName][] = $error->getMessage();
            }

            return $this->json([
                'errors' => $errors,
                'csrf_token' => $form->createView()->children['_token']->vars['value']
            ], 400); // Return a JSON error response with a 400 status code
        }

        // Rest of the code for successful registration
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );
        $em->persist($user); // Persist user to the database
        $em->flush(); // Save changes

        // Generate a JWT token
        $jwt_token = $jwtManager->create($user);

        return $this->json([
            'message' => 'User registered successfully.',
            'jwt_token' => $jwt_token
        ]);
    }


    private function getFormErrors($form)
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            if ($error->getOrigin()) {
                $fieldName = $error->getOrigin()->getName();
                $errors[$fieldName][] = $error->getMessage();
            } else {
                $errors['_global'][] = $error->getMessage();
            }
        }
        return $errors;
    }
}
