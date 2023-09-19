<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Helper\UpsHelper;
use App\Service\UpsLoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentLabelRecoveryController extends MainController
{
    #[Route('/api/user/shipments-label-recovery', name: 'app_api_user_shipment_label_recovery', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        $adminLogin = UpsLoginService::login();

        $headers = [
            'Authorization' => 'Bearer '. $adminLogin['access_token']
        ];

        return UpsHelper::post('/api/labels/v1/recovery', $content, $request->query->all(), $headers);
    }
}
