<?php

namespace App\Controller;

use App\Service\SecurityService;
use App\Service\SerializerService;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class SecurityController extends AbstractController
{
    public function __invoke(Request $request, SecurityService $securityService, SerializerService $serializerService): JsonResponse
    {
        try {
            $user = $securityService->register($request->get("data"));
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage(), "Description" => "Erreur sur la creation d'utilisateur"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($serializerService->serialize($user, "user:read"), Response::HTTP_CREATED);
    }
}