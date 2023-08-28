<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\JsonValidationService;



class AccountController extends AbstractController
{
    private $em = null;

    #[Route('/account', name: 'app_account', methods: ['GET,POST'])]
    public function index(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->em = $doctrine->getManager();

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AccountController.php',
        ]);
    }

    #[Route('/account/login', name: 'app_account_login', methods: ['POST'])]
    public function login(Request $request, ManagerRegistry $doctrine,): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/account/register', name: 'app_account_register')]
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true); // Decode JSON data from the request

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Submit form with JSON data
        $form->submit($data);

        if (!$form->isSubmitted()) {
            return $this->json([
                'error' => 'The form was not submitted.',
            ], 400); // Return a JSON error response with a 400 status code
        }

        if (!$form->isValid()) {
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

        return $this->json([
            'message' => 'User registered successfully.'
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
