<?php

namespace App\Controller;

use App\Entity\BestPriceList;
use App\Repository\BestPriceListRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BestPriceController extends MainController
{
    #[Route('/api/best-price', name: 'app_best_price', methods: ['GET'])]
    public function index(Request $request, BestPriceListRepository $repository): JsonResponse
    {
        $user = $request->get('user_id', null);
        $type = $request->get('type', null);

        $bestPrice = $repository->findOneBy([
            'user' => $user,
            'type' => $type
        ]);

        $response = $this->serializer->serialize($bestPrice, 'json');

        return new JsonResponse($response, 200, [], true);
    }

    #[Route('/api/best-price', name: 'app_best_price_store', methods: ['POST'])]
    public function store(Request $request, BestPriceListRepository $repository, UserRepository $userRepository): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $ephValue = $content['eph'];
        $fuelSurcharge = $content['yakit'];
        $userId = $content['user_id'];
        $type = $content['type'];

        $user = null;
        if ($userId !== '"*"'){
            $user = $userRepository->find($userId);
        }

        $jsonData = [];
        foreach ($content['datas'] as $data) {
            $obj['data'] = $data['data'];
            $obj['bolge1'] = $data['bolge1'] * $ephValue * $fuelSurcharge;
            $obj['bolge2'] = $data['bolge2'] * $ephValue * $fuelSurcharge;
            $obj['bolge3'] = $data['bolge3'] * $ephValue * $fuelSurcharge;
            $obj['bolge4'] = $data['bolge4'] * $ephValue * $fuelSurcharge;
            $obj['bolge5'] = $data['bolge5'] * $ephValue * $fuelSurcharge;
            $obj['bolge6'] = $data['bolge6'] * $ephValue * $fuelSurcharge;
            $obj['bolge7'] = $data['bolge7'] * $ephValue * $fuelSurcharge;
            $obj['bolge8'] = $data['bolge8'] * $ephValue * $fuelSurcharge;
            $obj['bolge9'] = $data['bolge9'] * $ephValue * $fuelSurcharge;

            $jsonData[] = $obj;
        }

        /** @var BestPriceList $bestPrice */
        $bestPrice = $repository->findOneBy([
            'user' => $user,
            'type' => $type
        ]);

        if (!$bestPrice) {
            $bestPrice = new BestPriceList();
        }

        $bestPrice->setUser($user)
            ->setDatas($jsonData)
            ->setEphValue($ephValue)
            ->setType($type)
            ->setFuelSurcharge($fuelSurcharge);

        $repository->save($bestPrice, true);

        $response = $this->serializer->serialize($bestPrice, 'json');

        return new JsonResponse($response, 200, [], true);
    }
}
