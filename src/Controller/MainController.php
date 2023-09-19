<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected TranslatorInterface $translator;

    protected SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->serializer = $serializer;
    }
}
