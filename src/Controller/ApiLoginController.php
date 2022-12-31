<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\Request;


class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login')]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {

//        return $this->json([
//                'user' => $this->getUser() ? $this->getUser()->getId() : null]
//        );
//        $usery = new Security();
//
//        $usery->getUser();
//        var_dump($user);

        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = "asdasioduhasdughasduiashd"; // somehow create an API token for $user

        return $this->json([

            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
