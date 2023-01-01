<?php

namespace App\Controller;

//use Namshi\JOSE\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use \Firebase\JWT\JWT;



class ApiLoginController extends AbstractController
{

    private $jwtEncoder;


    #[Route('/api/login', name: 'app_api_login')]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {



        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $jwt = $this->generateJWT($user->getEmail());
        $token = "asdasioduhasdughasduiashd"; // somehow create an API token for $user
        $user->setTokenJWT($jwt);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $jwt,
        ]);
    }

    public function generateJWT($name) : String{
        // Set the secret key
        $secretKey = '1309u12eduHADdn9123AsD';

        // Set the token header
        $header = [
            'alg' => 'HS256', // Specifies the algorithm used to sign the JWT
            'typ' => 'JWT', // Specifies the type of the JWT
        ];

        // Set the token payload
        $payload = [
            'sub' => '1234567890', // The subject of the JWT
            'name' => $name, // Additional claims
            'iat' => time(), // The time the JWT was issued
            'exp' => time() + 3600, // The expiration time of the JWT
        ];

        // Encode the JWT
        return JWT::encode($payload, $secretKey, 'HS256', null, $header);

    }


    // function to authenticaion user

    #[Route('/api/checkjwt', name: 'app_api_checkjwt')]
    public function checkJWT(UserRepository $repository, Request $request) : JsonResponse {
        $data = json_decode($request->getContent(), true);
        $id = $data["id"];

        $user = $repository->find($id);
        $response = ['status'=>'false'];

        if ($user) {
            $token = $user->getTokenJWT();
            if($token === $data["token"]){
                $response = ['status'=>true];
            }
            else
                $response = ['status'=>false];
        } else {
            $response = ['status'=>false];
        }
//        var_dump($data);
        return $this->json($response);
    }
}
