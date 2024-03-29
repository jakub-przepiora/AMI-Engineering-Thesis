<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration" , methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
//        return $this->json('Registered SUCCESS new user');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {

        // 3) Encode the password (you could also do this via Doctrine listener)

        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        // 4) save the User!
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // ... do any other work - like sending them an email, etc
        // maybe set a "flash" success message for the user

        return $this->json(['status' => "Registered", 'user_id'=>$user->getId(),'token'=>$user->getTokenJWT()]);
//        return $this->json($user->getPassword());
//        }

//        return $this->json('Registered ERROR new user');
    }
}