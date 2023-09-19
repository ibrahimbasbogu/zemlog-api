<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Helper\UpsHelper;
use App\Service\UpsLoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressValidationController extends MainController
{
    #[Route('/api/user/address-validation', name: 'app_api_user_address_validation', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        $adminLogin = UpsLoginService::login();

        $headers = [
            'Authorization' => 'Bearer '. $adminLogin['access_token']
        ];

        return UpsHelper::get('/api/addressvalidation/v1/2', $content, $request->query->all(), $headers);
    }
}
