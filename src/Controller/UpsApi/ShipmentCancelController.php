<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Helper\UpsHelper;
use App\Service\UpsLoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentCancelController extends MainController
{
    #[Route('/api/user/shipments-cancel', name: 'app_api_user_shipment_cancel', methods: ['DELETE'])]
    public function index(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        $adminLogin = UpsLoginService::login();

        $headers = [
            'Authorization' => 'Bearer '. $adminLogin['access_token'],
            'transactionSrc' => 'testing',
            'transId' => 'PdZRe2yWfUPMfBosm10b0YvSbcvRNbHp'
        ];

        return UpsHelper::delete('/api/shipments/v1/void/cancel/'.$request->get('tracking_number'), [], $request->query->all(), $headers);
    }
}
