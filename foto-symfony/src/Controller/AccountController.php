<?php

namespace App\Controller;

use App\Entity\Foto;
use App\Entity\Friend;
use App\Entity\User;
use App\Form\CreateFotoFormType;
use App\Form\FormHandler;
use App\Form\LoginFormType;
use App\Form\ModifyAccountFormType;
use App\Form\RegistrationFormType;
use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

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
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->jwtHandler->decodedJWTToken['email']]);


        return $this->json([
            'user' => $user->getAll(),
            'jwtToken' => $newJWT
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/account/search', name: 'app_user_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $searchValue = $request->query->get('searchValue') ?? '';

        $users = $this->em->getRepository(User::class)->getUserBySearchValue($searchValue);
        $users = array_map(function ($user) {
            return $user->getAll();
        }, $users);


        return $this->json([
            'jwtToken' => $newJWT,
            'accounts' => $users
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/account/isAdmin/{idUser}', name: 'app_user_is_admin', methods: ['GET'])]
    public function isAdmin($idUser, Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $user = $this->getUserById($idUser);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }


        return $this->json([
            'jwtToken' => $newJWT,
            'isAdmin' => $user->isIsAdmin()
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
                'error' => ['Courriel ou mot de passe incorrect.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        if (!$passwordHasher->isPasswordValid($user, $form->get('password')->getData())) {
            return $this->json([
                'error' => ['Courriel ou mot de passe incorrect.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        if ($user->getBlock()) {
            return $this->json([
                'error' => ['Votre compte est bloqué, Raison: ' . $user->getBlock()->getReason() . '.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        return $this->json([
            "message" => "Utilisateur connecté avec succès.",
            "jwtToken" => $this->jwtHandler->create($user->getEmail(), $user->getIdUser())
        ]);
    }

    #[Route('/account/register', name: 'app_account_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $formHandler = new FormHandler($form);

        $data = json_decode($request->getContent(), true); // Decode JSON data from the request

        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }


        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );
        $user->setCreationDate(new \DateTime());

        $this->em->persist($user); // Persist user to the database
        $this->em->flush(); // Save changes
        // Generate a JWT token
        $jwtToken = $this->jwtHandler->create($user->getEmail(), $user->getIdUser());


        return $this->json([
            'message' => 'Compte créé avec succès.',
            'jwtToken' => $jwtToken
        ]);
    }

    #[Route('/account/find/{id}', name: 'app_other_user_account', methods: ['GET'])]
    public function getOtherUserAccount($id): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['idUser' => $id]);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'user' => $user->getAll(),
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/account/follow/{id}', name: 'app_follow_other_user', methods: ['POST'])]
    public function follow($id, Request $request): JsonResponse
    {
        $friend = $this->em->getRepository(User::class)->findOneBy(['idUser' => $id]);
        if (!$friend) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }



        //TODO : add friend to user friend list
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->jwtHandler->decodedJWTToken['email']]);

        $newFriend = new Friend(); 

        $user->addFriend($newFriend->addUser($friend));
        $this->em->persist($user); // Persist user to the database
        $this->em->flush(); // Save changes


        return $this->json([
            'user' => $user->getAll(),
            'jwtToken' => $newJWT
        ], JsonResponse::HTTP_OK);
    }
    private function getFriendById(int $idFriend): ?Friend
    {
        return $this->em->getRepository(Friend::class)->findOneBy(['id' => $idFriend]);
    }


    #[Route('/account/modify', name: 'app_account_modify', methods: ['POST'])]
    public function modifyAccount(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);

        if ($data['email'] != $this->jwtHandler->decodedJWTToken['email']) {
            return $this->json([
                'message' => 'Impossible de modifier le compte.'
            ], JsonResponse::HTTP_OK);
        }

        $formValues = [
            'bio' => $data['bio'],
            'picturePath' => $data['picturePath'],
        ];

        $form = $this->createForm(ModifyAccountFormType::class);
        $formHandler = new FormHandler($form);

        if (!$formHandler->handle($formValues)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }

        $user->setBio($data['bio']);
        $user->setPicturePath($data['picturePath']);
        $this->em->persist($user); // Persist user to the database
        $this->em->flush(); // Save changes
        // Generate a JWT token

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Post créée avec succès.',
            'account' => $user->getAll()
        ], JsonResponse::HTTP_OK);
    }


    private function getUserById(int $idUser): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['idUser' => $idUser]);
    }

    // private function getConnectedAccount(): ?User
    // {
    //     return $this->em->getRepository(User::class)->findBy();
    // }
}
