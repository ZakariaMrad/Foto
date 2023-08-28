<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestJsonController extends AbstractController
{
    #[Route('/test', name: 'app_get_test_json', methods:['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'GET',
            'path' => 'src/Controller/TestJsonController.php',
        ]);
    }

    #[Route('/test', name: 'app_post_test_json', methods:['POST'])]
    public function testPost(Request $request): JsonResponse
    {
        
        return $this->json([
            'message' => 'Post',
            'path' => 'src/Controller/TestJsonController.php',
            'request'=> $request->request->all()
        ]);
    }
}
