<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\ComplaintSubject;
use App\Entity\Post;
use App\Entity\User;
use App\Form\FormHandler;
use App\Form\ReportFormType;
use App\Jwt\JWTHandler;
use App\Entity\Complaint;
use COM;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ComplaintController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine,)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/complaint', name: 'app_complaint_create', methods: ['POST'])]
    public function createPost(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $complaint = new Complaint();

        $form = $this->createForm(ReportFormType::class, $complaint);
        $formHandler = new FormHandler($form);
        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }
        $sender = $this->getUserById($data['idSender']);
        $recipient = $this->getUserById($data['idRecipient']);
        if ($sender->getIdUser() === $recipient->getIdUser()) {
            return $this->json([
                'error' => ['Erreur: Vous ne pouvez pas vous signaler vous-même.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $complaint->setSentDateTime(new \DateTime());
        $complaint->setSender($sender);
        $complaint->setRecipient($recipient);
        $complaint->setStatus(Complaint::STATUS_ACTIVE);

        $complaint = $this->setSubjectById($data['idSubject'], $data['subjectType'], $complaint);
        if ($complaint->getSubject()->isEmpty()) {
            return $this->json([
                'error' => ['Erreur: Le subject n\'existe pas.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }


        $this->em->persist($complaint);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Reporté avec succès.'
        ], JsonResponse::HTTP_OK);
    }


    #[Route('/complaint/{idComplaint}/archive', name: 'app_complaint_archive', methods: ['POST'])]
    public function archiveComplaint($idComplaint, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $complaint = $this->getComplaintById($idComplaint);
        $complaint->setStatus(Complaint::STATUS_ARCHIVED);
        $this->em->persist($complaint);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Plainte archivé avec succès.'
        ], JsonResponse::HTTP_OK);
    }
    #[Route('/complaint/{idComplaint}/activate', name: 'app_complaint_activate', methods: ['POST'])]
    public function activateComplaint($idComplaint, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $complaint = $this->getComplaintById($idComplaint);
        $complaint->setStatus(Complaint::STATUS_ACTIVE);
        $this->em->persist($complaint);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Plainte activé avec succès.'
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/complaint/{idComplaint}/deleteSubject', name: 'app_complaint_delete', methods: ['DELETE'])]
    public function deleteComplaintSubject($idComplaint, Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $complaint = $this->em->getRepository(Complaint::class)->findOneBy(['idComplaint' => $idComplaint]);
        if (!$complaint) {
            return $this->json([
                'error' => ['Erreur: Le report n\'existe pas.'],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $post = $complaint->getSubject()->getPost();
        if ($post) {
            $post->setIsDeleted(true); // Update the isDeleted property
            $this->em->persist($post);
        }
        
        $album = $complaint->getSubject()->getAlbum();
        if ($album) {
            $album->setIsDeleted(true); // Update the isDeleted property
            $this->em->persist($album);
        }
        
        $this->em->persist($complaint);
        $this->em->flush(); 

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Report supprimé avec succès.'
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/complaints/active', name: 'app_complaints_active_get', methods: ['GET'])]
    public function getActiveComplaints(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $complaints = $this->getComplaintByStatus(Complaint::STATUS_ACTIVE);

        //order the post by the inversed datetime (new post at the start of the array)
        usort($complaints, function (Complaint $a, Complaint $b) {
            return $b->getSentDateTime() <=> $a->getSentDateTime();
        });
        $complaintsObj = array_map(function (Complaint $complaint) {
            return $complaint->getAll();
        }, $complaints);


        return  $this->json([
            'jwtToken' => $newJWT,
            'complaints' => $complaintsObj
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/complaints/archived', name: 'app_complaints_archived_get', methods: ['GET'])]
    public function getArchivedComplaints(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $complaints = $this->getComplaintByStatus(Complaint::STATUS_ARCHIVED);

        //order the post by the inversed datetime (new post at the start of the array)
        usort($complaints, function (Complaint $a, Complaint $b) {
            return $b->getSentDateTime() <=> $a->getSentDateTime();
        });
        $complaintsObj = array_map(function (Complaint $complaint) {
            return $complaint->getAll();
        }, $complaints);


        return  $this->json([
            'jwtToken' => $newJWT,
            'complaints' => $complaintsObj
        ], JsonResponse::HTTP_OK);
    }



    private function getUserById(int $idUser): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['idUser' => $idUser]);
    }

    private function setSubjectById(string $idSubject, string $subjectType, Complaint $complaint): Complaint
    {
        $subject = new ComplaintSubject();
        switch ($subjectType) {
                // case 'comment':
                //     $subject = $this->setCommentSubject($subject, $idSubject);
                //     break;
            case 'post':
                $subject = $this->setPostSubject($subject, $idSubject);
                break;
            case 'album':
                $subject = $this->setAlbumSubject($subject, $idSubject);
                break;
        }
        return $complaint->setSubject($subject);
    }
    private function getComplaintById($idComplaint)
    {
        return $this->em->getRepository(Complaint::class)->findOneBy(['idComplaint' => $idComplaint]);
    }
    private function getComplaintByStatus($status)
    {
        return $this->em->getRepository(Complaint::class)->findBy(['status' => $status]);
    }

    // private function setCommentSubject(ComplaintSubject $subject, $idSubject): ComplaintSubject{
    //     $comment = $this->em->getRepository(Comment::class)->findOneBy(['idComment' => $idSubject]);
    //     return $subject->setComment($comment);
    // }
    private function setPostSubject(ComplaintSubject $subject, $idSubject): ComplaintSubject
    {
        $comment = $this->em->getRepository(Post::class)->findOneBy(['idPost' => $idSubject]);
        return $subject->setPost($comment);
    }
    private function setAlbumSubject(ComplaintSubject $subject, $idSubject): ComplaintSubject
    {
        $comment = $this->em->getRepository(Album::class)->findOneBy(['idAlbum' => $idSubject]);
        return $subject->setAlbum($comment);
    }
}
