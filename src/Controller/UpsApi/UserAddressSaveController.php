<?php

namespace App\Controller\UpsApi;

use App\Controller\MainController;
use App\Entity\UserAddress;
use App\Helper\UpsHelper;
use App\Repository\UserAddressRepository;
use App\Service\UpsLoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserAddressSaveController extends MainController
{
    #[Route('/api/user/address', name: 'app_api_user_address', methods: ['POST'])]
    public function index(Request $request, UserAddressRepository $userAddressRepository): JsonResponse
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        $obj = $userAddressRepository->findOneBy([
            'user' => $user->getId()
        ]);

        if (!$obj){
            $obj = new UserAddress();
        }

        $obj->setUser($user)
            ->setAddressInfo($content);

        $userAddressRepository->save($obj, true);

        $response = $this->serializer->serialize($user, 'json');

        return new JsonResponse($response, 200, [], true);
    }
}
