<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SecurityService;
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
    public function __invoke(Request $request, SecurityService $securityService): JsonResponse
    {
        $user = $securityService->register($request->get("data"));
        $context = (new ObjectNormalizerContextBuilder())->withGroups('user:read')->toArray();
        
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        
        return new JsonResponse($serializer->normalize($user,null,$context), Response::HTTP_CREATED);
    }
}