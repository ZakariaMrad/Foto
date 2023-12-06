<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserBlock;
use App\Form\FormHandler;
use App\Form\UserBlockFormType;
use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserBlockController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine,)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/userblocks', name: 'app_userblock_get', methods: ['GET'])]
    public function getFotos(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $userblocks = $this->em->getRepository(UserBlock::class)->findAll();

        //order the post by the inversed datetime (new post at the start of the array)
        usort($userblocks, function (UserBlock $a, UserBlock $b) {
            return $b->getBlockDateTime() <=> $a->getBlockDateTime();
        });
        $userblocksObj = array_map(function (UserBlock $userblock) {
            return $userblock->getAll();
        }, $userblocks);


        return  $this->json([
            'jwtToken' => $newJWT,
            'userblocks' => $userblocksObj
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/userblock/search', name: 'app_block_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $searchValue = $request->query->get('searchValue')?? '';
        $userBlocks = $this->em->getRepository(UserBlock::class)->findAll();
        $users = $this->em->getRepository(User::class)->getUserBySearchValue($searchValue);
        $userBlocks = array_filter($userBlocks, function ($userBlock) use ($users) {
            foreach ($users as $user) {
                if ($userBlock->getUser()->getIdUser() === $user->getIdUser()) {
                    // User is blocked, include in the result
                    return true;
                }
            }
            // User is not blocked, don't include in the result
            return false;
        });
        
        $userBlocksObjs = array_map(function ($userBlock) {
            return $userBlock->getAll();
        }, $userBlocks);


        return $this->json([
            'jwtToken' => $newJWT,
            'blockedUsers' => $userBlocksObjs
        ], JsonResponse::HTTP_OK);
    }
    #[Route('/userblock/{idUserBlock}', name: 'app_userblock_delete', methods: ['DELETE'])]
    public function deleteComplaint($idUserBlock, Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $complaint = $this->em->getRepository(UserBlock::class)->findOneBy(['idUserBlock' => $idUserBlock]);
        if (!$complaint) {
            return $this->json([
                'error' => ['Erreur: Cette utilisateur n\'est pas bloqué.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->em->remove($complaint);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Utilisateur débloqué avec succès.'
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/userblock', name: 'app_user_block', methods: ['POST'])]
    public function createBlock(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => ['Erreur: L\'utilisateur n\'existe pas!.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $existingUserBlock = $this->getUserBlockById($data['idUser']);

        if ($existingUserBlock) {
            return $this->json([
                'error' => ['Erreur: L\'utilisateur est déjà bloqué.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $userBlock = new UserBlock();

        $userBlock->setBlockDateTime(new \DateTime());

        $form = $this->createForm(UserBlockFormType::class, $userBlock);
        $formHandler = new FormHandler($form);
        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }

        $user = $this->getUserById($data['idUser']);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: L\'utilisateur n\'existe pas.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $userBlock->setUser($user);


        $this->em->persist($userBlock);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Bloqué avec succès.'
        ], JsonResponse::HTTP_OK);
    }


    private function getUserById(int $idUser): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['idUser' => $idUser]);
    }
    private function getUserBlockById(int $idUser): ?UserBlock
    {
        $user = $this->getUserById($idUser);
        return $this->em->getRepository(UserBlock::class)->findOneBy(['user' => $user]);
    }
}
