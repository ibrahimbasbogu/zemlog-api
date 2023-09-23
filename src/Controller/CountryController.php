<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends MainController
{
    #[Route('/api/country', name: 'app_country', methods: ['GET'])]
    public function index(Request $request, CountryRepository $repository): JsonResponse
    {
        $items = $repository->findByRequest($request);
        $response = $this->serializer->serialize($items, 'json');
        return new JsonResponse($response, 200, [], true);
    }
}
