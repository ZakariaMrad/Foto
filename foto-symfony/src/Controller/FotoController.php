<?php

namespace App\Controller;

use App\Entity\Foto;
use App\Entity\User;
use App\Form\CreateFotoFormType;
use App\Form\FormHandler;
use App\Jwt\JWTHandler;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FotoController extends AbstractController
{
    private $em = null;
    private $jwtHandler;

    public function __construct(JWTEncoderInterface $jwtEncoder, ManagerRegistry $doctrine,)
    {
        $this->em = $doctrine->getManager();
        $this->jwtHandler = new JWTHandler($jwtEncoder);
    }

    #[Route('/foto', name: 'app_foto_create', methods: ['POST'])]
    public function createFoto(Request $request, SluggerInterface $slugger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        unset($data['description']);
        unset($data['idFoto']);
        unset($data['path']);

        $foto = new Foto();
        $form = $this->createForm(CreateFotoFormType::class, $foto);
        $formHandler = new FormHandler($form);
        if (!$formHandler->handle($data)) {
            return $this->json($formHandler->errors, JsonResponse::HTTP_BAD_REQUEST); // Return a JSON error response with a 400 status code
        }

        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);
        if (!$user) {
            return $this->json([
                'error' => 'Erreur: Compte non trouvé.',
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $foto->setUploadDate(new \DateTime());
        $foto->setUser($user);

        $base64String = $data['base64image'];
        list($type, $base64String) = explode(';', $base64String);
        list(, $base64String)      = explode(',', $base64String);
        $fotoImage = base64_decode($base64String);

        $safeFilename = $slugger->slug($data['name']);
        $newFilename = $safeFilename . "-" . uniqid() . "." . explode('/', $type)[1];
        $path = $this->getParameter('foto_image_directory') . "/" . $newFilename;
        $success = file_put_contents($path, $fotoImage);

        $foto->setPath($_ENV['BASE_URL']."/img/foto/".$newFilename);
        
        $this->em->persist($foto);
        $this->em->flush();

        return $this->json([
            'jwtToken' => $newJWT,
            'message' => 'Foto créée avec succès.'
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/fotos', name: 'app_foto_get', methods: ['GET'])]
    public function getFotos(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $fotos = $user->getFotos();
        $fotosArray = [];


        foreach ($fotos as $foto) {
            $fotosArray[] = $foto->getAll();
        }

        return $this->json([
            'jwtToken' => $newJWT,
            'fotos' => $fotosArray
        ], JsonResponse::HTTP_OK);
    }
    
    #[Route('/fotosbyid', name: 'app_foto_get_id', methods: ['GET'])]
    public function getFotosById(Request $request): JsonResponse
    {
        $data["jwtToken"] = $request->query->get('jwtToken');
        [$hasSucceded, $data, $newJWT] = $this->jwtHandler->handle($data);
        if (!$hasSucceded) {
            return $this->json([
                'error' => $this->jwtHandler->error,
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        
        $user = $this->getUserById($this->jwtHandler->decodedJWTToken['idUser']);
        if (!$user) {
            return $this->json([
                'error' => ['Erreur: Compte non trouvé.'],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $idFotos = $request->query->get('idFotos');
        $idFotos = substr($idFotos, 1, -1);
        $idFotos = explode(',', $idFotos);
        //TODO: check if user has access to Foto

        $fotos=[];
        foreach($idFotos as $idFoto) {
            $foto = $this->em->getRepository(Foto::class)->findOneBy(['idFoto' => $idFoto]);
            if (!$foto) {
                return $this->json([
                    'error' => ['Erreur: Foto avec id '. $idFoto.' non trouvée.'],
                ], JsonResponse::HTTP_NOT_FOUND);
            }
            $fotos[] = $foto;
        }

        foreach ($fotos as $foto) {
            $fotosArray[] = $foto->getAll();
        }

        return $this->json([
            'jwtToken' => $newJWT,
            'fotos' => $fotosArray
        ], JsonResponse::HTTP_OK);
    }

    private function getUserById(int $idUser): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['idUser' => $idUser]);
    }
}
