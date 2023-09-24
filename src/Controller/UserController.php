<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends MainController
{
    #[Route('/api/user', name: 'app_user', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        $items = $userRepository->findByRequest($request);

        $response = $this->serializer->serialize($items, 'json');

        return new JsonResponse($response, 200, [], true);
    }
}
