<?php
// src/Controller/SecurityController.php
namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{

    private $passwordEncoder;


    /**
     * @Route("/login", name="app_login")
     */
    public function login( AuthenticationUtils $authenticationUtils, #[CurrentUser] ?User $user ): Response
    {
//        $request = json_decode($request->getContent(), true);
//
//        $username = $request['username'];
//        $password = $request['password'];
//        $currentUser = new User();
//        $currentUser->
//
//        $user = $this->loadUserByUsername($username);
//        if (!$user instanceof UserInterface) {
//            return new JsonResponse(['error' => 'Invalid username or password'], 401);
//        }
//
//        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
//            return new JsonResponse(['error' => 'Invalid username or password'], 401);
//        }
//
//        // Create a JWT with the user's information
//        $payload = [
//            'user_id' => $user->getId(),
//            'username' => $user->getUsername(),
//            'exp' => time() + 3600, // one hour expiration time
//        ];
//        $jwt = JWT::encode($payload, $secretKey);

//        return new JsonResponse(['jwt' => $jwt]);



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



    public function loadUserByUsername($username)
    {
        $stmt = $this->conn->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('username = :username')
            ->setParameter('username', $username)
            ->execute();

        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/logout.html.twig');
    }
}