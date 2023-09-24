<?php

namespace App\Controller;

use App\Entity\BestPriceList;
use App\Entity\Country;
use App\Entity\User;
use App\Repository\BestPriceListRepository;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use function GuzzleHttp\describe_type;

class GetOfferController extends MainController
{
    #[Route('/api/get-offer', name: 'app_get_offer', methods: ['GET'])]
    public function index(Request $request, BestPriceListRepository $repository ,CountryRepository $countryRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $countryId = $request->get('country_id', null);
        $desi = $request->get('desi', null);

        if ($desi > 10.0){
            $desi = ceil($desi);
        } else {
            $fark = ceil($desi) - $desi;

            if ($fark > 0.5) {
                $desi = ceil($desi) - 0.5;
            } else if ($fark == 0.5) {
                $desi = floatval($desi);
            } else {
                $desi = ceil($desi);
            }
        }

        /** @var Country $country */
        $country = $countryRepository->find($countryId);

        if (!$country){
            throw new NotFoundHttpException($this->translator->trans('NOT_FOUND'));
        }

        $zone = $country->getRegion();

        $zeloPrice = 0;

        $zeloBestPrice = $repository->findOneBy([
            'user' => $user,
            'type' => 1
        ]);

        if (!$zeloBestPrice){
            $zeloBestPrice = $repository->findOneBy([
                'user' => null,
                'type' => 1
            ]);
        }

        foreach ($zeloBestPrice->getDatas() as $data) {
            if ($data['data'] == $desi){
                $zeloPrice = $data['bolge'.$zone];
            }
        }

        $seh2Price = 0;
        $seh2BestPrice = $repository->findOneBy([
            'user' => $user,
            'type' => 2
        ]);

        if (!$seh2BestPrice){
            $seh2BestPrice = $repository->findOneBy([
                'user' => null,
                'type' => 2
            ]);
        }

        foreach ($seh2BestPrice->getDatas() as $data) {
            if ($data['data'] == $desi){
                $seh2Price = $data['bolge'.$zone];
            }
        }

        $items = [
            'desi' => $desi,
            'price' => min($zeloPrice, $seh2Price),
            'title' => 'UPS Service'
        ];

        $response = $this->serializer->serialize($items, 'json');
        return new JsonResponse($response, 200, [], true);
    }
}
