<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StateController extends MainController
{
    #[Route('/api/state', name: 'app_state', methods: ['GET'])]
    public function index(Request $request, StateRepository $repository): JsonResponse
    {
        $items = $repository->findByRequest($request);
        $response = $this->serializer->serialize($items, 'json');
        return new JsonResponse($response, 200, [], true);
    }
}
