<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends MainController
{
    #[Route('/api/user/profile', name: 'app_user_profile')]
    public function index(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $response = $this->serializer->serialize($user, 'json');

        return new JsonResponse($response, 200, [], true);
    }
}
