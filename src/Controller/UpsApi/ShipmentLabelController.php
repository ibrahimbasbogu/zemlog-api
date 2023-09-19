<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Helper\UpsHelper;
use App\Repository\UserShipmentRepository;
use App\Service\UpsLoginService;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;
use Knp\Snappy\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentLabelController extends MainController
{
    #[Route('/api/user/shipments-label', name: 'app_api_user_shipment_label', methods: ['GET'])]
    public function index(Request $request, UserShipmentRepository $userShipmentRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$request->get('trackNumber')){
            throw new BadRequestHttpException("'trackNumber' Parameter is invalid");
        }

        $shipment = $userShipmentRepository->findOneBy([
            'user' => $user->getId(),
            'trackingNumber' => $request->get('trackNumber')
        ]);

        $result = $shipment->getShipmentResult()['PackageResults'];

        $base64ImageFile = 'data:image/jpeg;base64,'. $result['ShippingLabel']['GraphicImage'];

        return new JsonResponse([
            'base64_image_file' => $base64ImageFile
        ]);
    }
}
