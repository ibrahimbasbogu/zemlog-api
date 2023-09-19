<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Helper\UpsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ValidateClientController extends MainController
{
    #[Route('/api/user/validate-ups', name: 'app_api_user_validate_ups', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $user = $this->getUser();

        return UpsHelper::get('/security/v1/oauth/validate-client', [], [
            'client_id' => $_ENV['UPS_CLIENT_ID'],
            'redirect_uri' => 'https://www.adzcargo.com'
        ]);
    }
}
