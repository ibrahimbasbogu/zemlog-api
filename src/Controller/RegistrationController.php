<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends MainController
{
    #[Route('/auth/user/registration', name: 'app_auth_user_registration', methods: ['POST'])]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $repository): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        if (!isset($content['email']) || !isset($content['password'])){
            throw new BadRequestHttpException("Parameter is invalid");
        }

        $user = $repository->findOneBy([
            'email' => $content['email']
        ]);

        if ($user){
            throw new BadRequestHttpException("The e-mail you entered is registered in the system. Please register with another email or try to login.");
        }

        $user = new User();

        $hashPass = $passwordHasher->hashPassword($user, $content['password']);

        $user->setEmail($content['email'])
            ->setFirstName($content['first_name'])
            ->setLastName($content['last_name'])
            ->setPhoneNumber(!isset($content['phone_number']) ? null : $content['phone_number'])
            ->setPassword($hashPass)
            ->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $response = $this->serializer->serialize($user,'json');

        return new JsonResponse($response,200,[],true);
    }
}
