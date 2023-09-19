<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Helper\UpsHelper;
use App\Service\UpsLoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentTrackingController extends MainController
{
    #[Route('/api/user/shipments-tracking', name: 'app_api_user_shipment_tracking', methods: ['GET'])]
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

        return UpsHelper::get('/api/track/v1/details/'.$request->get('trackNumber'), [], $request->query->all(), $headers);
    }
}
