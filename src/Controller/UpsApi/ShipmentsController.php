<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Entity\User;
use App\Entity\UserShipment;
use App\Helper\UpsHelper;
use App\Repository\UserRepository;
use App\Repository\UserShipmentRepository;
use App\Service\UpsLoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentsController extends MainController
{
    #[Route('/api/user/shipments', name: 'app_api_user_shipment', methods: ['GET'])]
    public function index(Request $request, UserShipmentRepository $userShipmentRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getRoles() == ['ROLE_CLIENT']){
            $request->query->set('user_id', $user->getId());
        }

        $items = $userShipmentRepository->findByRequest($request);

        $response = $this->serializer->serialize($items, 'json');

        return new JsonResponse($response, 200, [], true);
    }

    #[Route('/api/user/shipments', name: 'app_api_user_shipment_store', methods: ['POST'])]
    public function store(Request $request, UserShipmentRepository $userShipmentRepository): JsonResponse
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        $shipmentRequestData = $content['ShipmentRequest']['Shipment'];

        $body = $content;
        $shipmentData = $content['ShipmentRequest']['Shipment'];
        $body['ShipmentRequest']['Request'] = [
            "SubVersion" => "1801",
            "RequestOption" => "nonvalidate",
            "TransactionReference" => [
                "CustomerContext" => ""
            ]
        ];

        $shipmentData['Shipper'] = [
            "Name" => "ShipperName",
            "AttentionName" => "ShipperZs Attn Name",
            "TaxIdentificationNumber" => "123456",
            "Phone" =>  [
                "Number" => "1115554758",
                "Extension" => " ",
            ],
            "ShipperNumber" => "B8294C",
            "FaxNumber" => "8002222222",
            "Address" => [
                "AddressLine" => "MüCAHITLER MAHALLESI 52008 NOLU",
                "City" => "SEHİTKAMİL",
                "StateProvinceCode" => "GA",
                "PostalCode" => "27000",
                "CountryCode" => "TR",
            ]
        ];

        $shipmentData['Service'] = [
            "Code" => "65",
            "Description" => "UPS Saver"
        ];

        $shipmentData['PaymentInformation'] = [
            "ShipmentCharge" => [
                "Type" => "01",
                "BillShipper" =>  [
                    "AccountNumber" => "B8294C"
                ]
            ]
        ];

        $shipmentData['LabelSpecification'] = [
            "LabelImageFormat" =>  [
                "Code" => "JPG",
                "Description" => "JPG"
            ]
        ];

        $body['ShipmentRequest']['Shipment'] = $shipmentData;

        $adminLogin = UpsLoginService::login();

        $headers = [
            'Authorization' => 'Bearer '. $adminLogin['access_token']
        ];

        $response = UpsHelper::post('/api/shipments/v1/ship', $body, $request->query->all(), $headers);

        if ($response->getStatusCode() != 200){
            return $response;
        }

        $responseContent = json_decode($response->getContent(), true);

        $shipmentResult = $responseContent['ShipmentResponse']['ShipmentResults'];

        $trackingNumber = $shipmentResult['PackageResults']['TrackingNumber'];

        $obj = new UserShipment();

        $shipmentResult['ShipmentDescription'] = $shipmentData['Description'];

        $obj->setUser($user)
            ->setShipFrom($shipmentRequestData['ShipFrom'])
            ->setShipTo($shipmentRequestData['ShipTo'])
            ->setPackage($shipmentRequestData['Package'])
            ->setShipmentResult($shipmentResult)
            ->setTrackingNumber($trackingNumber);

        $userShipmentRepository->save($obj, true);

        return $response;
    }
}
