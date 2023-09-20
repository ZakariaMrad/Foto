<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CustomErrorController extends AbstractController
{
    public function showInternalServerError(): JsonResponse
    {
        return $this->json([
            'Error' => 'Internal Server Error',
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
