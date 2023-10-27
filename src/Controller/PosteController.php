<?php

namespace App\Controller;

use App\Service\MailService;
use App\Service\PosteService;
use App\Service\SecurityService;
use App\Service\SerializerService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class PosteController extends AbstractController
{
    public function __invoke(Request $request, PosteService $service, MailService $serviceMailService, Security $security, SerializerService $serializerService): JsonResponse
    {
        // Ajout d'un poste
        $poste = $service->add($request->get("data"));

        if($poste){
            try {
                // Envoie de mail après une insertion reussie
                $serviceMailService->sendMail($security->getToken()->getUser());

            } catch (TransportExceptionInterface $e) {
                return new JsonResponse(["error" => $e->getMessage(), "description" => "Poste crée mais le mail n'a pas été envoyé "], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        
        return new JsonResponse($serializerService->serialize($poste, "poste:read"), Response::HTTP_CREATED);
    }
}